<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloque extends Model
{
    use HasFactory;

    protected $table = 'bloques';
    protected $primaryKey = 'id_bloque'; // Correcto: id_bloque
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_bloque',
        'nombre_bloque', // Correcto: nombre_bloque
        'id_proyecto',   // Correcto: id_proyecto
    ];

    /**
     * Un bloque pertenece a un proyecto.
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    /**
     * Un bloque tiene muchas piezas.
     */
    public function piezas()
    {
        return $this->hasMany(Pieza::class, 'id_bloque', 'id_bloque');
    }
}