<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nom_client'); // سمية الكليان
            $table->string('telephone'); // نمرة التيليفون
            $table->text('adresse'); // العنوان
            $table->string('ville')->default('Safi'); // المدينة (درنا آسفي هي الافتراضية)
            $table->decimal('total', 10, 2); // الثمن المجموع
            $table->string('statut')->default('En attente'); // حالة الطلبية (واش باقة كنتسناو، ولا تصيفطات...)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};