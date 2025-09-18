<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void {
Schema::create('price_ticks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cryptocurrency_id')->constrained('cryptocurrencies')->cascadeOnDelete();
    $table->decimal('price_usd', 20, 8)->default(0);
    $table->decimal('market_cap', 24, 2)->nullable();
    $table->decimal('volume_24h', 24, 2)->nullable();
    $table->decimal('pct_1h', 10, 4)->nullable();
    $table->decimal('pct_24h', 10, 4)->nullable();
    $table->decimal('pct_7d', 10, 4)->nullable();
    $table->dateTime('captured_at')->index();
    $table->timestamps();
});

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_ticks');
    }
};
