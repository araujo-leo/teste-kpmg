# Teste KPMG - Laravel Application

Este é um projeto desenvolvido com Laravel, Inertia.js e Vue.js, utilizando o Laravel Sail para facilitar o ambiente de desenvolvimento local usando Docker.

## Como Iniciar o Projeto

> **Aviso:** Certifique-se de que as portas utilizadas pelos serviços do Laravel Sail (como 80, 3306, 8025 e 5173) estejam livres e não estejam sendo usadas por outras aplicações no seu sistema.

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

### 4. E-mails e Notificações (Mailpit)

Todos os e-mails e notificações processados pelo sistema (como o enriquecimento de dados de tickets via fila) são interceptados localmente pelo Mailpit.
Para visualizar os e-mails disparados, acesse a interface do Mailpit em: [http://localhost:8025](http://localhost:8025).
