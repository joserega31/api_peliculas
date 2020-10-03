<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeliculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peliculas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('categoria_id')->unsigned();     
            $table->string('nombre',100);
            $table->string('descripcion',500);         
            $table->integer('year');
            $table->timestamps();        

            $table->foreign('categoria_id')->references('id')->on('categorias')
            ->onDelete('cascade')
            ->onUpdate('cascade');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peliculas');
    }
}
