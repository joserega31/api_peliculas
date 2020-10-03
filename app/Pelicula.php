<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    public function categoria()
    {
        return $this->hasOne(Categoria::class);
    }
}
