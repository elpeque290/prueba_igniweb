## CryptoInvestment

App Laravel que consulta CoinMarketCap, persiste en SQLite y expone endpoints:
- GET /api/quotes?symbols=BTC,ETH,SOL
- GET /api/history?symbol=BTC&from=YYYY-MM-DD&to=YYYY-MM-DD

### Puesta en marcha
1) composer install
2) cp .env.example .env
3) php artisan key:generate
4) php artisan migrate
5) php artisan serve --host=127.0.0.1 --port=8000

