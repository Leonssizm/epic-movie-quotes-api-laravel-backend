<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('notifications', function (Blueprint $table) {
			$table->id();
			$table->foreignId('receiver_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
			$table->foreignId('quote_id')->constrained()->cascadeOnDelete();
			$table->foreignId('sender_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
			$table->boolean('is_like')->default(false);
			$table->boolean('is_comment')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('notifications');
	}
};
