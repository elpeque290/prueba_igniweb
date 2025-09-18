CryptoInvestment – Resumen breve (para README)

Qué es

SPA en Laravel + JS.

Consulta CoinMarketCap (CMC), guarda en SQLite y muestra cotizaciones + histórico (Chart.js).

Arranque rápido

composer install

cp .env.example .env

php artisan key:generate

.env mínimo (ajusta rutas/keys)
APP_URL=http://127.0.0.1:8000

APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=C:\Users\TU_USUARIO\Desktop\ss\cryptoinvestment\database\database.sqlite
CMC_API_KEY=TU_API_KEY_DE_CMC

Base de datos

Crear archivo vacío: database/database.sqlite

Migrar: php artisan migrate

Ejecutar

php artisan serve --host=127.0.0.1 --port=8000

Abrir: http://127.0.0.1:8000

Endpoints

Cotizaciones: GET /api/quotes?symbols=BTC,ETH,SOL

Histórico: GET /api/history?symbol=BTC&from=YYYY-MM-DD&to=YYYY-MM-DD

from/to también aceptan dd/mm/yyyy

Pruebas rápidas
PowerShell:
Invoke-RestMethod "http://127.0.0.1:8000/api/quotes?symbols=BTC,ETH,SOL
" | ConvertTo-Json -Depth 5
Invoke-RestMethod "http://127.0.0.1:8000/api/history?symbol=BTC&from=2025-09-10&to=2025-09-18
"

UI (uso)

Agrega símbolos (ej: SOL) y presiona “Agregar”.

Click en una fila para graficar.

Filtra por fechas y “Aplicar”.
