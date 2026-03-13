# Desafio Docker DIO

Projeto simples inspirado no repositorio base da DIO para demonstrar uma arquitetura de microsservicos com Docker.

## O que foi implementado

- `nginx` como proxy reverso e balanceador de carga
- duas instancias da aplicacao PHP (`app1` e `app2`)
- `mysql` para persistencia dos dados
- configuracao por variaveis de ambiente, sem IPs fixos

## Arquitetura

`nginx:4500` -> `app1` / `app2` -> `mysql`

Ao acessar a aplicacao, um novo registro e salvo no banco e a pagina mostra qual container respondeu a requisicao.

## Como executar

```bash
docker compose up --build
```

Depois, acesse:

```bash
http://localhost:4500
```

## Estrutura

- `docker-compose.yml`: orquestra os servicos
- `dockerfile`: imagem do `nginx`
- `php-app/Dockerfile`: imagem da aplicacao PHP
- `index.php`: pagina principal
- `banco.sql`: script inicial do banco

## Ideias de melhoria

- adicionar endpoint de healthcheck
- criar pagina com listagem dos registros salvos
- publicar o projeto no GitHub com prints da aplicacao rodando
