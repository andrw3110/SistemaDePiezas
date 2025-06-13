<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';
    protected $primaryKey = 'id_proyecto'; // Correcto: id_proyecto
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_proyecto',
        'nombre', // Correcto: nombre (en minúsculas)
    ];

    /**
     * Un proyecto tiene muchos bloques.
     */
    public function bloques()
    {
        return $this->hasMany(Bloque::class, 'id_proyecto', 'id_proyecto');
    }

    /**
     * Un proyecto tiene muchas piezas a través de los bloques.
     */
    public function piezas()
    {
        return $this->hasManyThrough(
            Pieza::class,   // Modelo final (Pieza)
            Bloque::class,  // Modelo intermedio (Bloque)
            'id_proyecto',  // Clave foránea en la tabla `bloques` que referencia a `proyectos`
            'id_bloque',    // Clave foránea en la tabla `piezas` que referencia a `bloques`
            'id_proyecto',  // Clave local en `proyectos`
            'id_bloque'     // Clave local en `bloques`
        );
    }
}