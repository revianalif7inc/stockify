<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nama atribut (contoh: Ukuran, Warna, Berat)
            $table->enum('type', ['text', 'select', 'number']); // Tipe input
            $table->json('options')->nullable(); // Pilihan untuk type 'select' (contoh: ["S", "M", "L", "XL"])
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['category_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
