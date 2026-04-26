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
        // كنقلبو واش الخانة ديال الستوك ماكايناش عاد كنزيدوها باش مانطيحوش فـ الإيرور
        if (!Schema::hasColumn('products', 'stock')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('stock')->default(0)->after('price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // هادي باش إلا بغينا نمسحو الخانة من بعد
            $table->dropColumn('stock');
        });
    }
};