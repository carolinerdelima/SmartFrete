# 📦 SmartFrete API

API desenvolvida com Laravel, PostgreSQL e Docker.  
Simula e armazena cotações de frete com integração à API Frete Rápido.

---

## 🚀 Configuração Rápida

### 1. Clone o projeto:

```
git clone https://github.com/carolinerdelima/SmartFrete.git
cd SmartFrete
```

### 2. Copie o arquivo .env:

Este projeto utiliza dois arquivos .env distintos para separar as variáveis de ambiente do Docker e da aplicação Laravel. Copie os arquivos:

```
cp .env.example .env
cp smartfrete/.env.example smartfrete/.env
```

> *⚠️ Dica:* Ajuste as variáveis do banco de dados e APP_URL para http://smartfrete.local se desejar usar URL amigável.

---

### 3. Adicione no arquivo /etc/hosts (Linux/macOS) 


```
sudo vim /etc/hosts
```

e insira o conteúdo seguinte:
127.0.0.1 smartfrete.local

---

### 4. Suba os containers Docker:

```
docker compose up -d --build
```

---

### 5. Instale as dependências PHP:

```
docker compose exec app bash
composer install
```

---

### 6. Gere a chave da aplicação:

```
docker compose exec app bash
php artisan key:generate
```

---

### 7. Ajuste permissões (se necessário):

```
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## ✅ Acesso

| Recurso                 | URL                                      |
|-------------------------|-------------------------------------------|
| Aplicação               | http://smartfrete.local/api                  |
| Swagger (Documentação)  | http://smartfrete.local/api/documentation |
> *Usuário e Senha do banco:* conforme .env (DB_USERNAME, DB_PASSWORD)

---

## 🧪 Testes

```
php artisan test
```

---

## 📚 Documentação das rotas

Para verificar a documentação, rode esse comando dentro do container da aplicação:

```
php artisan l5-swagger:generate
```

Ele vai:

  - Ler suas anotações nos controllers,
  - Gerar a documentação Swagger em JSON (storage/api-docs/swagger.json),
  - Deixá-la acessível via navegador em http://smartfrete.local/api/documentation.
---


## ✨ Observações

- Este projeto é *exclusivamente backend (API)*.
- Não inclui front-end.
- A documentação da API é gerada automaticamente com Swagger.