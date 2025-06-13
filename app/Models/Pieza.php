<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pieza extends Model
{
    use HasFactory;

    protected $table = 'piezas';
    protected $primaryKey = 'id_pieza'; // Correcto: id_pieza
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pieza',
        'pieza',
        'peso_teorico',
        'peso_real',
        'estado',
        'id_proyecto',  // <-- ¡ESTO ES LO QUE FALTABA Y HA SIDO AÑADIDO!
        'id_bloque',
        'fecha_registro',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'peso_teorico' => 'decimal:2',
        'peso_real' => 'decimal:2',
    ];

    /**
     * Una pieza pertenece a un bloque.
     */
    public function bloque()
    {
        return $this->belongsTo(Bloque::class, 'id_bloque', 'id_bloque');
    }

    /**
     * Una pieza pertenece a un proyecto a través de su bloque.
     */
    public function proyecto()
    {
        return $this->hasOneThrough(
            Proyecto::class,
            Bloque::class,
            'id_bloque',
            'id_proyecto',
            'id_bloque',
            'id_proyecto'
        );
    }

    /**
     * Una pieza es registrada por un usuario.
     */
    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por', 'id');
    }
}