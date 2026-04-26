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
        Schema::table('products', function (Blueprint $table) {
            // كنزيدو الساروت (category_id) مورا الـ id وتكون خاوية في الأول (nullable)
            $table->unsignedBigInteger('category_id')->nullable()->after('id');

            // كنربطو هاد الساروت مع جدول الأصناف (categories)
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null'); // إلا تمسحات الكاتيݣوري، المنتج كيبقى والخانة كتولي NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // كنحيدو الربط (Foreign Key) هو الأول
            $table->dropForeign(['category_id']);
            // عاد كنمسحو الخانة
            $table->dropColumn('category_id');
        });
    }
};