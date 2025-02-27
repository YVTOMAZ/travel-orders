<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Projeto de Ordem de Viagem - API

Este é um projeto de API para gerenciar ordens de viagem, com autenticação JWT e comunicação por email. A aplicação é baseada em **PHP (Laravel)** e utiliza **Docker** para configuração do ambiente.

## 🛠 Tecnologias Utilizadas

- **PHP** (Laravel)
- **MySQL** 8
- **Mailhog** para teste de emails
- **Docker** para containerização
- **JWT** para autenticação de usuários
- **APIDOG** Rotas prontas para facilitar os testes (Travel.apidog.json)

## 🎥 Vídeo Explicativo
Assista ao vídeo explicativo do funcionamento da API:

<div>
    <a href="https://www.loom.com/share/8c44c773d45844ec843df26d5b656b3d">
      <p></p>
    </a>
    <a href="https://www.loom.com/share/8c44c773d45844ec843df26d5b656b3d">
      <img style="max-width:300px;" src="https://cdn.loom.com/sessions/thumbnails/8c44c773d45844ec843df26d5b656b3d-d5a1dd9ef00f001a-full-play.gif">
    </a>
<div>

## 🚀 Como Rodar o Projeto

### 1. Clone o Repositório

Primeiro, faça o clone do repositório:

```bash
git clone https://github.com/seu-usuario/repositorio.git
cd repositorio
```
2. Instalar o Docker e Docker Compose
- Certifique-se de ter o Docker e o Docker Compose instalados na sua máquina.

3. Instalar Dependências
- Agora, instale as dependências do projeto. Este comando pode ser executado fora do Docker, diretamente na sua máquina:

```bash
composer install
```

4. Rodar o Docker Compose e levantar todos os serviços:

```bash
docker compose up -d
```
##### Esse comando vai:

- Criar e iniciar os containers para o PHP, MySQL e Mailhog.
A aplicação estará disponível em http://localhost:8000.

5. Rodar as Migrations
Para rodar as migrations do banco de dados, dentro do container PHP execute:

```bash
docker compose exec app php artisan migrate
```
6. Rodar os Testes
Para rodar os testes automatizados, use o comando abaixo dentro do container PHP:

```bash
docker compose exec app php artisan test
```

7. Acessar o Mailhog
Para verificar os emails enviados pela aplicação, acesse o Mailhog no navegador:
```
URL: http://localhost:8025
```
# 📡 Rotas da API
### Aqui estão as principais rotas da API e suas funcionalidades:

```
POST /api/login : 
Autentica o usuário e retorna um token JWT.
```
```
POST /api/register : 
Registra um novo usuário e retorna um token JWT.
```
```
POST /api/travel-orders : 
Cria uma nova ordem de viagem (requere autenticação).
```
```
GET /api/travel-orders : 
Lista as ordens de viagem do usuário autenticado.
```
```
GET /api/travel-orders/{id} : 
Exibe os detalhes de uma ordem de viagem.
```
```
PATCH /api/travel-orders/{id}/status : 
Atualiza o status de uma ordem de viagem (requere permissões adequadas).
```
### Exemplos de Requisições

#### Login
```
curl -X POST http://localhost:8000/api/login -d "email=user@exemplo.com&password=sua-senha"
```
#### Criar Ordem de Viagem
``` 
curl -X POST http://localhost:8000/api/travel-orders -H "Authorization: Bearer SEU_TOKEN" -d "requester=Tomaz&destination=Minas Gerais&departure_date=2025-06-01&return_date=2025-06-05"
```

⚙️ Configuração de Ambiente

As variáveis de ambiente para o MySQL são configuradas no arquivo .env.example já preenchido para facilitar a configuração, só renomear .env.example para .env:

```
MYSQL_DATABASE=corporate_travel
MYSQL_ROOT_PASSWORD=secret
```

## ✅ Testes
Os testes do projeto estão na pasta tests/Feature/. 
Eles cobrem os principais fluxos da API, como criação de ordens de viagem, atualizações de status e validação de permissões.

### Exemplos de Testes

#### Testar criação de ordem de viagem
```
docker compose exec app php artisan test --filter test_create_travel_order
```
#### Testar falha na atualização de status
```
docker compose exec app php artisan test --filter test_user_cannot_update_own_order_status
```

### Importante: 
Para rodar todos os comandos, incluindo as migrations e os testes, sempre execute-os dentro do Docker, usando os comandos mencionados acima, para garantir que as configurações do ambiente sejam carregadas corretamente.