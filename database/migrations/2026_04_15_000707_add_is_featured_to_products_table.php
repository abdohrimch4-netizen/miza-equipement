<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('stock');
            }
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
        });
    }
    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumnIfExists(['is_featured', 'slug']);
        });
    }
};
