<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',60);  
            $table->timestamps();          
        });

        DB::table('categorias')->insert(array(
            'nombre'=>'Accion',
        ));
        DB::table('categorias')->insert(array(
            'nombre'=>'Romance',
        ));
        DB::table('categorias')->insert(array(
            'nombre'=>'Terror',
        ));
        DB::table('categorias')->insert(array(
            'nombre'=>'Comedia',
        ));
        
                      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}
