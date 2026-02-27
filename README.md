# Pendulo Radar

![Pendulo Radar Background](bg-pendulo.png)

## Descrição
Projeto para gerenciamento e acompanhamento de rotas, motoristas, passageiros e veículos, facilitando o controle e a operação de transportes em viagens intermunicipais.

## Vídeos de Demonstração
- [Demonstração - Passageiro](https://drive.google.com/file/d/1oO53q3OinGoK6Lf2RY8HPkeyyMc6lyqn/view?usp=drivesdk)
- [Demonstração - Motorista](https://drive.google.com/file/d/1QQ1kof0fZz41rNsns7Qa7aa-UvSk0BG3/view?usp=drivesdk)

## Como Rodar o Projeto

### Usando Docker
1. Certifique-se de ter o Docker instalado.
2. Execute o comando:
	```bash
	docker build -t pendulo-radar .
	docker run -p 10000:10000 pendulo-radar
	```
3. O sistema estará disponível em `http://localhost:10000`.


### Manual (sem Docker)
1. Instale as dependências:
	```bash
	composer install
	npm install
	```
2. Configure o arquivo `.env`:
	- Copie o exemplo:
	  ```bash
	  cp .env.example .env
	  ```
	- Edite as variáveis do banco de dados conforme seu ambiente:
	  ```env
	  DB_CONNECTION=mysql
	  DB_HOST=127.0.0.1
	  DB_PORT=3306
	  DB_DATABASE=laravel
	  DB_USERNAME=root
	  DB_PASSWORD=senha_do_banco
	  ```
	- Gere a chave da aplicação:
	  ```bash
	  php artisan key:generate
	  ```
3. Execute as migrações:
	```bash
	php artisan migrate --force
	```
4. Inicie o servidor:
	```bash
	php artisan serve
	```
5. O sistema estará disponível em `http://localhost:8000`.

## Tecnologias Utilizadas
### Backend
- PHP 8.2
- Laravel 12
- Composer
- MySQL
- Docker

### Frontend
- JavaScript (ES Modules)
- Vite
- TailwindCSS
- Axios

### Dev/Build
- Laravel Vite Plugin
- Concurrently


