<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PriceTick extends Model
{
    protected $fillable = [
        'cryptocurrency_id','price_usd','market_cap','volume_24h',
        'pct_1h','pct_24h','pct_7d','captured_at'
    ];
    public $timestamps = true;
    protected $casts = [
        'captured_at'=>'datetime','price_usd'=>'float','market_cap'=>'float',
        'volume_24h'=>'float','pct_1h'=>'float','pct_24h'=>'float','pct_7d'=>'float',
    ];
}
