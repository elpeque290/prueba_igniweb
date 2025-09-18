<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cryptocurrency extends Model
{
    protected $fillable = ['symbol','name','cmc_id'];
}
