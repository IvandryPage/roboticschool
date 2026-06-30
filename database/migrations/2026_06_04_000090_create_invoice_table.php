<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pendaftaran_id')->nullable()->constrained('pendaftaran')->unique();
            $table->string('no_invoice')->unique();
            $table->decimal('total_tagihan', 14, 2)->default(0);
            $table->timestamp('tanggal_terbit')->nullable();
            $table->timestamp('tanggal_jatuh_tempo')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('payment_reference')->unique()->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
