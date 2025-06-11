<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pieza extends Model
{
    use HasFactory;

    protected $table = 'piezas';
    protected $primaryKey = 'id_pieza';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pieza',
        'pieza',
        'peso_teorico',
        'peso_real',
        'estado',
        'id_proyecto',
        'id_bloque',
        'fecha_registro',
        'registrado_por',
    ];

    /**
     * Una pieza pertenece a un proyecto
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    /**
     * Una pieza pertenece a un bloque
     */
    public function bloque()
    {
        return $this->belongsTo(Bloque::class, 'id_bloque', 'id_bloque');
    }
}
