<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>CryptoInvestment</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * {
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      margin: 0;
      padding: 20px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      color: #333;
    }
    
    h1 {
      color: white;
      text-align: center;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 2rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .input-section {
      background: white;
      padding: 1.5rem;
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      margin-bottom: 2rem;
      backdrop-filter: blur(10px);
    }
    
    #addSym {
      padding: 12px 16px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 16px;
      width: 300px;
      margin-right: 12px;
      transition: all 0.3s ease;
    }
    
    #addSym:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    #btnAdd {
      padding: 12px 24px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    #btnAdd:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .pill {
      display: inline-block;
      padding: 8px 16px;
      border-radius: 20px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      margin: 4px 8px 4px 0;
      font-weight: 500;
      font-size: 14px;
      box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    .row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
    }
    
    @media(max-width: 900px) {
      .row {
        grid-template-columns: 1fr;
      }
    }
    
    .table-container {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    th {
      padding: 16px 20px;
      color: white;
      font-weight: 600;
      text-align: left;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    td {
      padding: 16px 20px;
      border-bottom: 1px solid #f1f3f4;
      font-size: 15px;
      font-weight: 500;
    }
    
    tbody tr {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    tbody tr:hover {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
      transform: translateX(4px);
    }
    
    tbody tr:last-child td {
      border-bottom: none;
    }
    
    .chart-container {
      background: white;
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    
    .date-controls {
      margin-top: 1rem;
      display: flex;
      gap: 12px;
      align-items: center;
    }
    
    input[type="date"] {
      padding: 8px 12px;
      border: 2px solid #e1e5e9;
      border-radius: 6px;
      font-size: 14px;
      transition: all 0.3s ease;
    }
    
    input[type="date"]:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    #apply {
      padding: 8px 16px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    #apply:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    /* Animaciones para los números */
    td:nth-child(3), td:nth-child(4) {
      font-weight: 600;
    }
    
    /* Colores para porcentajes positivos/negativos */
    .positive {
      color: #10b981;
    }
    
    .negative {
      color: #ef4444;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>CryptoInvestment</h1>

  <div class="input-section">
    <input id="addSym" placeholder="Agregar símbolo (p.ej. SOL)">
    <button id="btnAdd">Agregar</button>
    <div id="watchlist" style="margin-top:16px"></div>
  </div>

  <div class="row">
    <div class="table-container">
      <table>
        <thead><tr><th>Símbolo</th><th>Precio</th><th>1h</th><th>24h</th><th>Vol 24h</th></tr></thead>
        <tbody id="tbody"></tbody>
      </table>
    </div>
    <div class="chart-container">
      <canvas id="chart" height="220"></canvas>
      <div class="date-controls">
        <input type="date" id="from"> 
        <input type="date" id="to">
        <button id="apply">Aplicar</button>
      </div>
    </div>
  </div>
</div>

<script>
const wl = new Set(['BTC','ETH','SOL']);
const tbody = document.getElementById('tbody');
const wldiv  = document.getElementById('watchlist');
const fromEl = document.getElementById('from');
const toEl   = document.getElementById('to');
let chart, selectedSymbol = null;

// Fechas por defecto: últimos 7 días (YYYY-MM-DD)
(function setDefaultDates(){
  const fmt = d => d.toISOString().slice(0,10);
  const today = new Date();
  const weekAgo = new Date(); weekAgo.setDate(today.getDate()-7);
  if(!fromEl.value) fromEl.value = fmt(weekAgo);
  if(!toEl.value)   toEl.value   = fmt(today);
})();

document.getElementById('btnAdd').onclick=async()=>{
  const s = document.getElementById('addSym').value.trim().toUpperCase();
  if(!s) return;
  wl.add(s);
  document.getElementById('addSym').value='';
  renderWL();
  await refreshQuotes();
};

function renderWL(){
  wldiv.innerHTML = [...wl].map(s=>`<span class="pill">${s}</span>`).join('');
}
renderWL();

// ===== COTIZACIONES =====
async function refreshQuotes(){
  try{
    const res = await fetch(`/api/quotes?symbols=${[...wl].join(',')}`);
    const data = await res.json();

    tbody.innerHTML = '';
    data.forEach(d=>{
      const tr = document.createElement('tr');
      const pct1 = Number(d.pct_1h||0), pct24 = Number(d.pct_24h||0);
      tr.innerHTML = `
        <td><strong>${d.symbol}</strong></td>
        <td><strong>$${Number(d.price).toFixed(2)}</strong></td>
        <td class="${pct1>=0?'positive':'negative'}">${pct1.toFixed(2)}%</td>
        <td class="${pct24>=0?'positive':'negative'}">${pct24.toFixed(2)}%</td>
        <td>${Math.round(d.volume_24h||0).toLocaleString()}</td>`;
      tr.onclick = () => { selectedSymbol = d.symbol; loadHistory(selectedSymbol); };
      tbody.appendChild(tr);
    });

    // Elegir símbolo inicial si no hay uno seleccionado
    if(!selectedSymbol && data.length) selectedSymbol = data[0].symbol;
    if(selectedSymbol) loadHistory(selectedSymbol);
  }catch(e){ console.error(e); }
}
setInterval(refreshQuotes, 15000);
refreshQuotes();

// ===== HISTÓRICO =====
async function loadHistory(symbol){
  const qs = new URLSearchParams({ symbol });
  if(fromEl.value) qs.set('from', fromEl.value);
  if(toEl.value)   qs.set('to',   toEl.value);

  let rows = [];
  try{
    const res = await fetch(`/api/history?${qs.toString()}`);
    rows = await res.json();
  }catch(e){ console.error(e); }

  // Si no hay datos, sembramos un tick y reintentamos una vez
  if(!rows || rows.length===0){
    try{
      await fetch(`/api/quotes?symbols=${symbol}`);
      const res2 = await fetch(`/api/history?${qs.toString()}`);
      rows = await res2.json();
    }catch(e){ console.error(e); }
  }

  drawChart(symbol, rows);
}

function drawChart(symbol, rows){
  const ctx = document.getElementById('chart').getContext('2d');
  if(chart) chart.destroy();

  if(!rows || rows.length===0){
    chart = new Chart(ctx, {
      type:'line',
      data:{ labels:[], datasets:[{ label:`${symbol} (sin datos)`, data:[] }] },
      options:{ responsive:true }
    });
    return;
  }

  // Orden y mapeo
  rows.sort((a,b)=> new Date(a.captured_at) - new Date(b.captured_at));
  const labels = rows.map(r => new Date(r.captured_at).toLocaleString());
  const prices = rows.map(r => Number(r.price_usd));

  chart = new Chart(ctx,{
    type:'line',
    data:{
      labels,
      datasets:[{
        label: symbol,
        data: prices,
        spanGaps: true,
        showLine: true,
        borderWidth: 2,
        pointRadius: 3,
        tension: .25,
        fill: false
      }]
    },
    options:{
      responsive:true,
      animation:false,
      scales:{ y:{ beginAtZero:false } },
      plugins:{ legend:{ display:true } }
    }
  });
}

// Botón Aplicar: usa el símbolo seleccionado (o el primero de la tabla)
document.getElementById('apply').onclick = ()=>{
  if(!selectedSymbol){
    const first = tbody.querySelector('tr td');
    if(first) selectedSymbol = first.textContent.trim();
  }
  if(selectedSymbol) loadHistory(selectedSymbol);
};
</script>


</body>
</html>
