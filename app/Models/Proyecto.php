<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    // Nombre de la tabla asociada
    protected $table = 'proyectos';

    // Clave primaria personalizada
    protected $primaryKey = 'id_proyecto';

    // Indicamos que no es autoincremental y es string
    public $incrementing = false;
    protected $keyType = 'string';

    // Campos que pueden asignarse masivamente
    protected $fillable = ['id_proyecto', 'nombre'];

    // Relación con la tabla bloques
   public function bloques()
    {
    return $this->hasMany(Bloque::class, 'id_proyecto', 'id_proyecto');
    }

}
