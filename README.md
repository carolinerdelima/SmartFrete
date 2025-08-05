# 📦 SmartFrete API

API desenvolvida com Laravel, PostgreSQL e Docker.  
Simula e armazena cotações de frete com integração à API Frete Rápido.

---

## 🚀 Configuração Rápida

### 1. Clone o projeto:

bash
git clone https://github.com/carolinerdelima/SmartFrete.git
cd SmartFrete


### 2. Copie o arquivo .env:

bash
cp .env.example .env


> *⚠️ Dica:* Ajuste as variáveis do banco de dados e APP_URL para http://smartfrete.local se desejar usar URL amigável.

---

### 3. Adicione no arquivo /etc/hosts (Linux/macOS) ou C:\\Windows\\System32\\drivers\\etc\\hosts (Windows):

txt
127.0.0.1 smartfrete.local


---

### 4. Suba os containers Docker:

bash
docker compose up -d --build


---

### 5. Instale as dependências PHP:

bash
docker compose exec app bash
composer install


---

### 6. Gere a chave da aplicação:

bash
php artisan key:generate


---

### 7. Ajuste permissões (se necessário):

bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache


---

## ✅ Acesso

| Recurso                 | URL                                      |
|-------------------------|-------------------------------------------|
| Aplicação               | http://smartfrete.local                   |
| Swagger (Documentação)  | http://smartfrete.local/api/documentation |
> *Usuário e Senha do banco:* conforme .env (POSTGRES_USER, POSTGRES_PASSWORD)

---

## 🧪 Testes

bash
php artisan test


---

## 📚 Rotas Principais da API

### [POST] /api/quote

Simula e retorna uma cotação de frete.  
Requisição exemplo:

json
{
  "recipient": {
    "zipcode": "90200000"
  },
  "dispatchers": [
    {
      "volumes": [
        {
          "category": "1",
          "amount": 2,
          "sku": "AB123",
          "description": "Caixa de livros",
          "height": 0.2,
          "width": 0.3,
          "length": 0.4,
          "unitary_price": 45.50,
          "unitary_weight": 1.2
        }
      ]
    }
  ],
  "simulation_type": [0]
}


---

### [GET] /api/metrics

Consulta métricas das cotações salvas.  
Parâmetro opcional:

- last_quotes: limita a análise às últimas N cotações.

Exemplo:

http
GET /api/metrics?last_quotes=5


---


## ✨ Observações

- Este projeto é *exclusivamente backend (API)*.
- Não inclui front-end.
- A documentação da API é gerada automaticamente com Swagger.