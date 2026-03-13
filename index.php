<?php
declare(strict_types=1);

ini_set('display_errors', '1');
header('Content-Type: text/html; charset=UTF-8');

$dbHost = getenv('DB_HOST') ?: 'db';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPassword = getenv('DB_PASSWORD') ?: 'root';
$dbName = getenv('DB_NAME') ?: 'meubanco';
$appName = getenv('APP_NAME') ?: 'app';
$hostName = gethostname() ?: 'unknown-host';

$link = @new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($link->connect_errno) {
    http_response_code(500);
    echo '<h1>Erro de conexao com o banco</h1>';
    echo '<p>Mensagem: ' . htmlspecialchars($link->connect_error, ENT_QUOTES, 'UTF-8') . '</p>';
    exit();
}

$link->query(
    'CREATE TABLE IF NOT EXISTS dados (
        AlunoID INT,
        Nome VARCHAR(50),
        Sobrenome VARCHAR(50),
        Endereco VARCHAR(150),
        Cidade VARCHAR(50),
        Host VARCHAR(50)
    )'
);

$valorId = random_int(1, 999);
$valorTexto = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

$stmt = $link->prepare(
    'INSERT INTO dados (AlunoID, Nome, Sobrenome, Endereco, Cidade, Host)
     VALUES (?, ?, ?, ?, ?, ?)'
);

if ($stmt) {
    $stmt->bind_param('isssss', $valorId, $valorTexto, $valorTexto, $valorTexto, $valorTexto, $hostName);
    $stmt->execute();
}

$totalRegistros = 0;
$result = $link->query('SELECT COUNT(*) AS total FROM dados');
if ($result instanceof mysqli_result) {
    $row = $result->fetch_assoc();
    $totalRegistros = (int) ($row['total'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Desafio Docker DIO</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #d7e1ec);
            color: #1f2937;
        }

        main {
            max-width: 720px;
            margin: 48px auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
        }

        h1 {
            margin-top: 0;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .card {
            background: #f8fafc;
            border: 1px solid #dbe3ec;
            border-radius: 12px;
            padding: 16px;
        }

        .label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 6px;
        }

        .value {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <main>
        <h1>Microsservicos com Docker</h1>
        <p>Projeto simples para o desafio da DIO com balanceamento entre duas aplicacoes PHP e persistencia em MySQL.</p>

        <div class="grid">
            <section class="card">
                <span class="label">Aplicacao</span>
                <span class="value"><?php echo htmlspecialchars($appName, ENT_QUOTES, 'UTF-8'); ?></span>
            </section>
            <section class="card">
                <span class="label">Container</span>
                <span class="value"><?php echo htmlspecialchars($hostName, ENT_QUOTES, 'UTF-8'); ?></span>
            </section>
            <section class="card">
                <span class="label">PHP</span>
                <span class="value"><?php echo htmlspecialchars(phpversion(), ENT_QUOTES, 'UTF-8'); ?></span>
            </section>
            <section class="card">
                <span class="label">Registros no banco</span>
                <span class="value"><?php echo $totalRegistros; ?></span>
            </section>
        </div>
    </main>
</body>
</html>
