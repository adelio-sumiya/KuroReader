<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Tabel untuk menyimpan Info Novel
    Schema::create('novels', function (Blueprint $table) {
        $table->id();
        $table->string('source_url')->unique(); // Kunci utama scraping (URL asli)
        $table->string('title');
        $table->string('cover_image')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();
    });

    // Tabel untuk menyimpan Isi Chapter
    Schema::create('chapters', function (Blueprint $table) {
        $table->id();
        $table->foreignId('novel_id')->constrained()->onDelete('cascade');
        $table->string('chapter_url'); // URL asli chapter
        $table->string('title');
        $table->longText('content')->nullable(); // Isi teks novel
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novels_tables');
    }
};
