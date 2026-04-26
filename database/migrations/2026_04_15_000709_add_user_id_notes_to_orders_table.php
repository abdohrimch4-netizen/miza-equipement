<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('id');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('adresse');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('statut');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumnIfExists(['user_id', 'notes', 'payment_status', 'payment_method']);
        });
    }
};
