<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pelicula;
use Faker\Generator as Faker;

$factory->define(Pelicula::class, function (Faker $faker) {
    return [
        'nombre' =>$faker->username,
        'descripcion' =>$faker->text,
        'categoria_id' =>rand(1,4),
        'year' =>rand(2000,2020)
    ];
});
