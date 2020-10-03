<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public function Peliculas()
    {
        return $this->hasMany(Pelicula::class);
    }
}
