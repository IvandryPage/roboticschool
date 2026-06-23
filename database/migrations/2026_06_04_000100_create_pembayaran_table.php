<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->constrained('invoice')->unique();
            $table->decimal('nominal', 14, 2)->default(0);
            $table->string('metode_pembayaran')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_reference')->unique()->nullable();
            $table->string('status')->nullable();
            $table->string('bukti_file')->nullable();
            $table->foreignUuid('diverifikasi_oleh')->nullable()->constrained('users');
            $table->text('catatan_penolakan')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('callback_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
