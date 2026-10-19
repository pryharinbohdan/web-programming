<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // categories створюється раніше (менша часова позначка), тому FK має на що посилатися.
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('operation', 40)->index();
            $table->decimal('price', 12, 2)->index();
            $table->string('currency', 10)->default('₴');
            $table->string('short_description');
            $table->text('description');
            $table->string('image');
            $table->string('contact_name');
            $table->string('contact_phone', 32);
            $table->string('contact_email');
            $table->string('status', 40);
            $table->date('published_at')->index();
            $table->json('attributes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
