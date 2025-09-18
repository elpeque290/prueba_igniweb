<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Cryptocurrency;
use App\Models\PriceTick;

class QuoteController extends Controller
{
    // GET /api/quotes?symbols=BTC,ETH,SOL
    public function quotes(Request $r) {
        $symbols = strtoupper($r->query('symbols','BTC,ETH'));
        try {
            $resp = Http::withHeaders(['X-CMC_PRO_API_KEY'=>env('CMC_API_KEY')])
                ->timeout(10)
                ->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest', [
                    'symbol'=>$symbols,'convert'=>'USD'
                ]);
            if ($resp->failed()) throw new \Exception($resp->body());
            $data = $resp->json()['data'] ?? [];
        } catch (\Throwable $e) {
            \Log::error('CMC fail', ['msg'=>$e->getMessage()]);
            $data = [];
            foreach (explode(',', $symbols) as $s) {
                $data[$s] = ['name'=>$s,'quote'=>['USD'=>[
                    'price'=>rand(20000,70000)+rand(0,99)/100,
                    'market_cap'=>0,'volume_24h'=>0,
                    'percent_change_1h'=>0,'percent_change_24h'=>0,'percent_change_7d'=>0
                ]]];
            }
        }

        $out = [];
        foreach ($data as $sym=>$d) {
            $q = $d['quote']['USD'] ?? [];
            $crypto = Cryptocurrency::updateOrCreate(
                ['symbol'=>$sym],
                ['name'=>$d['name'] ?? $sym, 'cmc_id'=>$d['id'] ?? null]
            );
            PriceTick::create([
                'cryptocurrency_id'=>$crypto->id,
                'price_usd'=>$q['price'] ?? 0,
                'market_cap'=>$q['market_cap'] ?? 0,
                'volume_24h'=>$q['volume_24h'] ?? 0,
                'pct_1h'=>$q['percent_change_1h'] ?? 0,
                'pct_24h'=>$q['percent_change_24h'] ?? 0,
                'pct_7d'=>$q['percent_change_7d'] ?? 0,
                'captured_at'=>now()
            ]);
            $out[] = [
                'symbol'=>$sym,
                'name'=>$d['name'] ?? $sym,
                'price'=>$q['price'] ?? 0,
                'pct_1h'=>$q['percent_change_1h'] ?? 0,
                'pct_24h'=>$q['percent_change_24h'] ?? 0,
                'pct_7d'=>$q['percent_change_7d'] ?? 0,
                'volume_24h'=>$q['volume_24h'] ?? 0,
                'market_cap'=>$q['market_cap'] ?? 0,
                'time'=>now()->toISOString()
            ];
        }
        return response()->json($out);
    }

    // GET /api/history?symbol=BTC&from=YYYY-MM-DD&to=YYYY-MM-DD
public function history(Request $r)
{
    $symbol = strtoupper((string) $r->query('symbol'));
    if (!$symbol) {
        return response()->json([]);
    }

    $from = $r->query('from'); // yyyy-mm-dd o dd/mm/yyyy
    $to   = $r->query('to');   // yyyy-mm-dd o dd/mm/yyyy

    // Normalizar fechas (acepta dd/mm/yyyy y yyyy-mm-dd)
    $start = null; $end = null;
    if ($from) {
        $start = \Carbon\Carbon::hasFormat($from, 'd/m/Y')
            ? \Carbon\Carbon::createFromFormat('d/m/Y', $from)->startOfDay()->utc()
            : \Carbon\Carbon::parse($from)->startOfDay()->utc();
    }
    if ($to) {
        $end = \Carbon\Carbon::hasFormat($to, 'd/m/Y')
            ? \Carbon\Carbon::createFromFormat('d/m/Y', $to)->endOfDay()->utc()
            : \Carbon\Carbon::parse($to)->endOfDay()->utc();
    }

    $q = \App\Models\PriceTick::query()
        ->select('price_ticks.captured_at', 'price_ticks.price_usd')
        ->join('cryptocurrencies as c', 'c.id', '=', 'price_ticks.cryptocurrency_id')
        ->where('c.symbol', $symbol)
        ->orderBy('price_ticks.captured_at', 'asc');

    if ($start && $end)  $q->whereBetween('price_ticks.captured_at', [$start, $end]);
    elseif ($start)      $q->where('price_ticks.captured_at', '>=', $start);
    elseif ($end)        $q->where('price_ticks.captured_at', '<=', $end);

    $rows = $q->get();

    // (opcional) forzar ISO para el frontend
    $rows = $rows->map(function ($r) {
        $r->captured_at = \Carbon\Carbon::parse($r->captured_at)->toISOString();
        return $r;
    });

    return response()->json($rows);
}



}
