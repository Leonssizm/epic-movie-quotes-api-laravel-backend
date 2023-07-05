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
			$table->foreignId('sender_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
			$table->unsignedBigInteger('notifiable_id');
			$table->string('notifiable_type');
			$table->boolean('is_like')->default(false);
			$table->boolean('is_comment')->default(false);
			$table->boolean('is_new')->default(true);
			$table->timestamps();
			$table->index(['notifiable_id', 'notifiable_type']);
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
