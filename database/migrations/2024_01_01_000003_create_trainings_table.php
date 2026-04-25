<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['event', 'ecourse']);
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->longText('description');
            $table->text('objectives')->nullable();
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_free')->default(false);
            $table->unsignedSmallInteger('skp_value')->default(0);
            $table->string('pelataran_link')->nullable();
            $table->timestamp('schedule')->nullable();
            $table->string('duration')->nullable();
            $table->unsignedInteger('max_participants')->nullable();
            $table->boolean('is_published')->default(false);
            // Speaker/trainer
            $table->string('trainer_name')->nullable();
            $table->string('trainer_title')->nullable();
            $table->string('trainer_avatar')->nullable();
            $table->text('trainer_bio')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
