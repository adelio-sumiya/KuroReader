<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('user_libraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('novel_api_id'); 
            $table->enum('status', ['want_to_read', 'reading', 'completed']);
            $table->timestamps();
            

            $table->unique(['user_id', 'novel_api_id']);
    });
    }

    public function down(): void
    {
        //
    }
};
