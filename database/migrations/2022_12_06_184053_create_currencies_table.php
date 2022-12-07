<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string("valuteID", 255); // - идентификатор валюты, который возвращает метод (пример: R01010)
            $table->string("numCode", 255); // -  числовой код валюты (пример: 036)
            $table->string("сharCode", 255); //- буквенный код валюты (пример: AUD)
            $table->string("name", 255); // - имя валюты (пример: Австралийский доллар)
            $table->string("value", 255); // - значение курса (пример: 43,9538)
            $table->timestamp("date"); // - дата публикации курса (может быть в UNIX-формате или ISO 8601)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
