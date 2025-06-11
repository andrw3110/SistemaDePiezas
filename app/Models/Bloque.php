<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloque extends Model
{
    protected $table = 'bloques';
    protected $primaryKey = 'id_bloque';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_bloque', 'nombre_bloque', 'id_proyecto'];

    /**
     * Un bloque pertenece a un proyecto
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }
}