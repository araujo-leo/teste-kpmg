# Teste KPMG - Laravel Application

Este é um projeto desenvolvido com Laravel, Inertia.js e Vue.js, utilizando o Laravel Sail para facilitar o ambiente de desenvolvimento local usando Docker.

## 🚀 Como Iniciar o Projeto

### 1. Clonar o repositório

```bash
git clone https://github.com/araujo-leo/teste-kpmg.git
cd teste-kpmg
```

### 2. Iniciar o projeto

Copie o arquivo de ambiente e inicie os containers via Sail:

```bash
cp .env.example .env
./vendor/bin/sail up -d
```

Em seguida, instale as dependências, gere a chave e rode as migrations (com seed para popular o banco):

```bash
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

Por fim, inicie as filas (necessárias para processamentos em background):

```bash
./vendor/bin/sail artisan queue:work
```

### 3. Como Logar

Acesse a aplicação em [http://localhost](http://localhost).

Utilize as seguintes credenciais de teste (criadas pelo comando de seed):

- **Email**: `test@example.com`
- **Senha**: `password`
