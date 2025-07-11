<?php

use App\Enums\ClothesColor;
use App\Enums\ClothesFabric;
use App\Enums\ClothesType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clothes', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->enum('type', array_column(ClothesType::cases(), 'value'));
            $table->enum('fabric', array_column(ClothesFabric::cases(), 'value'));
            $table->enum('color', array_column(ClothesColor::cases(), 'value'));
            $table->integer('quantity');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothes');
    }
};
