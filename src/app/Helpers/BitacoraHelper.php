<?php

namespace App\Helpers;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class BitacoraHelper
{
    public static function registrar($modulo, $accion, $descripcion)
    {
        Bitacora::create([
            'usuario_id' => Auth::id(),
            'modulo' => $modulo,
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}