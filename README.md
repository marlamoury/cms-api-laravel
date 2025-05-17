# CMS API Laravel

API RESTful para gerenciamento de postagens com autenticação JWT, Swagger e Docker.

## 🚀 Requisitos

- Docker
- Docker Compose

## 🔧 Subindo a aplicação com Docker

```bash
docker-compose up --build
Acesse a API em http://localhost:8000.

📦 Instalação manual (caso não use Docker)
bash
Copy
Edit
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
📚 Documentação da API (Swagger)
Acesse:

bash
Copy
Edit
http://localhost:8000/api/documentation
🔑 Endpoints principais
POST /api/auth/login

POST /api/auth/register

GET|POST|PUT|DELETE /api/posts

GET|POST|PUT|DELETE /api/users

yaml
Copy
Edit

---

Se quiser, posso te enviar também:

- 🧪 Arquivo de collection do Postman
- 🧑‍💻 Anotações Swagger completas para as demais rotas
- 📱 Frontend React Native

Quer seguir com algum desses agora?