<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_topik', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kelas_id')->constrained('kelas');
            $table->foreignUuid('pembuat_id')->constrained('users');
            $table->string('judul');
            $table->text('konten')->nullable();
            $table->timestamps();
        });

        Schema::create('forum_komentar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('topik_id')->constrained('forum_topik');
            $table->foreignUuid('user_id')->constrained('users');
            $table->text('komentar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_komentar');
        Schema::dropIfExists('forum_topik');
    }
};
