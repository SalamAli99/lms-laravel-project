<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamp('used_at');

            $table->unique(['coupon_id','user_id']); // 🔥 prevents duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_user');
    }
};
