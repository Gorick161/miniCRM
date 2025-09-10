<?php

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
        Schema::create('deals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pipeline_id')->constrained()->cascadeOnDelete();
    $table->foreignId('stage_id')->constrained()->cascadeOnDelete();
    $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
    $table->string('title');
    $table->integer('value_cents')->default(0);
    $table->string('currency', 3)->default('EUR');
    $table->unsignedTinyInteger('probability')->default(0); // 0..100
    $table->enum('status', ['open','won','lost'])->default('open');
    $table->date('expected_close_date')->nullable();
    $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
