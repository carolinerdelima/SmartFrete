# 📦 SmartFrete API

API desenvolvida com Laravel, PostgreSQL e Docker.  
Simula e armazena cotações de frete com integração à API Frete Rápido.

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.2**
- **Laravel 12**
- **PostgreSQL**
- **Docker**
- **Docker Compose**
- **L5 Swagger** – para documentação da API
- **PHPUnit** – para testes unitários

---
## 🚀 Configuração Rápida

### 1. Clone o projeto

```bash
git clone https://github.com/carolinerdelima/SmartFrete.git
cd SmartFrete
```

### 2. Copie o arquivo `.env`

Este projeto utiliza dois arquivos `.env` distintos para separar as variáveis de ambiente do Docker e da aplicação Laravel. Copie os arquivos:

```bash
cp .env.example .env
cp smartfrete/.env.example smartfrete/.env
```

---

### 3. Adicione no arquivo `/etc/hosts` (Linux/macOS)

```bash
sudo vim /etc/hosts
```

E insira o conteúdo seguinte:

```
127.0.0.1 smartfrete.local
```

---

### 4. Suba os containers Docker

```bash
docker compose up -d --build
```

---

### 5. Instale as dependências PHP

```bash
docker compose exec app bash
composer install
```

---

### 6. Gere a chave da aplicação

```bash
docker compose exec app bash
php artisan key:generate
```

---

### 7. Ajuste permissões (se necessário)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## ✅ Acesso

| Recurso                | URL                                         |
|------------------------|----------------------------------------------|
| Aplicação              | http://smartfrete.local/api                 |
| Swagger (Documentação) | http://smartfrete.local/api/documentation  |

> **Usuário e Senha do banco:** conforme `.env` (`DB_USERNAME`, `DB_PASSWORD`)

---

## 🧪 Testes

```bash
php artisan test
```

---

## 📚 Documentação das rotas

Para verificar a documentação, rode este comando dentro do container da aplicação:

```bash
php artisan l5-swagger:generate
```

Isso irá:

- Gerar a documentação Swagger em JSON (`storage/api-docs/swagger.json`)
- Deixá-la acessível via navegador em [http://smartfrete.local/api/documentation](http://smartfrete.local/api/documentation)

---

## ✨ Observações

- Este projeto é **exclusivamente backend (API)**.
- Não inclui front-end.
- A documentação da API é gerada automaticamente com Swagger.