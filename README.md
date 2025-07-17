# SmartFrete API

API desenvolvida em Laravel com Docker.

## 🚀 Configuração Rápida

1. Clone o projeto:
```bash
git clone https://github.com/carolinerdelima/SmartFrete.git
cd SmartFrete
````

2. Copie o arquivo `.env.example`:

```bash
cp .env.example .env
```

3. Suba os containers Docker:

```bash
docker compose up -d --build
```

4. Instale as dependências PHP:

```bash
docker compose exec app bash
```

Dentro do container da aplicação:

```bash
composer install
```

5. Gere a chave da aplicação:

Dentro do container da aplicação:

```bash
php artisan key:generate
```

6. Ajuste permissões (se necessário):

Dentro do container da aplicação:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

7. Acesse a aplicação:

* [http://localhost](http://localhost)

8. Acesse o PHPMyAdmin:

* [http://localhost:8081](http://localhost:8081)

  * **Usuário:** inserir
  * **Senha:** inserir

---

## ⚙️ Comandos Úteis


* **Migrações:**

```bash
php artisan migrate
```

* **Seeders:**

```bash
php artisan db:seed
```

---

## 📁 Estrutura

TO DO
---

## 📌 Observação

Esse projeto é exclusivamente backend (API), sem front-end incluído.