# Promobit Backend Challenges

Este projeto é uma API RESTful em PHP que gerencia produtos, categorias e tags. Ele pode ser executado localmente usando Docker Compose, o que facilita a configuração do ambiente de desenvolvimento.

## Pré-requisitos

Antes de começar, certifique-se de que você tem os seguintes softwares instalados em sua máquina:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Como rodar o projeto

Siga os passos abaixo para configurar e executar o projeto:

### 1. Clone o repositório

Primeiro, clone o repositório para o seu ambiente local:

```bash
git clone https://github.com/alef-sena/Studies.PHP.git
cd Studies.PHP
```

### 2. Configure o ambiente

O projeto já vem com um arquivo `docker-compose.yml` configurado para rodar a API PHP com um banco de dados MySQL.

Crie um arquivo na raiz do projeto chamado `.env` e adicione as seguintes instruções:

```txt
MYSQL_HOST=<db_host>
MYSQL_DATABASE=<database_name>
MYSQL_USER=<username>
MYSQL_PASSWORD=<password>
MYSQL_ROOT_PASSWORD=<database_root_password>
```

Os valores serão fornecidos pelo proprietário do projeto.

### 3. Inicie os contêineres

No diretório raiz do projeto, execute o seguinte comando para iniciar os contêineres:

```bash
docker-compose up -d
```

Isso irá:

1. Construir a imagem do PHP com as dependências necessárias.
2. Iniciar um contêiner MySQL.
3. Iniciar um contêiner PHP com a API.

### 4. Acesse a API

A API estará disponível em `http://localhost:8081`.

Você pode testar os endpoints usando ferramentas como [Postman](https://www.postman.com/), [Insomnia](https://insomnia.rest/download) ou [cURL](https://curl.se/).

Acesse a [especificação da API](https://github.com/alef-sena/Studies.PHP/blob/first-learnings/api_spec.yaml) para verificar as rotas disponíveis.

#### Exemplo de requisição

Para listar todos os produtos:

```bash
curl -X GET http://localhost:8000/products
```

### 5. Parar os contêineres

Para parar os contêineres, execute:

```bash
docker-compose down
```

Isso irá parar e remover os contêineres, mas manterá os volumes (como o banco de dados) intactos.

### 6. Reiniciar os contêineres

Se você precisar reiniciar os contêineres, execute:

```bash
docker-compose restart
```

### 7. Acessar o banco de dados

O banco de dados MySQL está disponível na porta `3306`. Você pode acessá-lo usando um cliente MySQL, como o [MySQL Workbench](https://www.mysql.com/products/workbench/), [DBeaver](https://dbeaver.io/) ou pelo próprio [CLI](https://dev.mysql.com/doc/mysql-shell/8.0/en/mysql-shell-install-linux-quick.html).

```bash
docker compose exec db mysql -u <username> -p
```

O `username` e a senha serão fornecidos pelo proprietário do projeto.

### 8. Ver logs

Para ver os logs do contêiner PHP, execute:

```bash
docker-compose logs -f app
```

Para ver os logs do MySQL, execute:

```bash
docker-compose logs -f db
```

## Endpoints da API

A API possui os seguintes endpoints:

### Produtos

- `GET /products`: Listar todos os produtos.
- `GET /products/{id}`: Obter detalhes de um produto.
- `POST /products`: Criar um novo produto.
- `PUT /products/{id}`: Atualizar um produto (substituição completa).
- `PATCH /products/{id}`: Atualizar parcialmente um produto.
- `DELETE /products/{id}`: Excluir um produto.
- `GET /products/{product_id}/tags`: Listar tags de um produto.
- `POST /products/{product_id}/tags/{tag_id}`: Adicionar uma tag a um produto.

### Categorias

- `GET /categories`: Listar todas as categorias.
- `GET /categories/{id}`: Obter detalhes de uma categoria.
- `POST /categories`: Criar uma nova categoria.
- `PUT /categories/{id}`: Atualizar uma categoria (substituição completa).
- `PATCH /categories/{id}`: Atualizar parcialmente uma categoria.
- `DELETE /categories/{id}`: Excluir uma categoria.
- `GET /categories/{category_id}/products`: Listar produtos de uma categoria.

### Tags

- `GET /tags`: Listar todas as tags.
- `GET /tags/{id}`: Obter detalhes de uma tag.
- `POST /tags`: Criar uma nova tag.
- `PUT /tags/{id}`: Atualizar uma tag (substituição completa).
- `PATCH /tags/{id}`: Atualizar parcialmente uma tag.
- `DELETE /tags/{id}`: Excluir uma tag.

## Contribuição

Se você quiser contribuir para o projeto, siga os passos abaixo:

1. Faça um fork do repositório.
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`).
3. Commit suas mudanças (`git commit -m 'Adiciona nova feature'`).
4. Push para a branch (`git push origin feature/nova-feature`).
5. Abra um Pull Request.

---

Este `README.md` fornece todas as instruções necessárias para configurar e rodar o projeto localmente usando Docker Compose. Se precisar de mais detalhes ou tiver dúvidas, sinta-se à vontade para abrir uma issue no repositório.
