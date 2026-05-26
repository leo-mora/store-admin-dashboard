<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim($request->buscar ?? '');

        $bitacoras = Bitacora::with('usuario')
            ->when($buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('modulo', 'like', "%{$buscar}%")
                      ->orWhere('accion', 'like', "%{$buscar}%")
                      ->orWhere('descripcion', 'like', "%{$buscar}%")
                      ->orWhereHas('usuario', function ($sub) use ($buscar) {
                          $sub->where('name', 'like', "%{$buscar}%")
                              ->orWhere('email', 'like', "%{$buscar}%");
                      });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('bitacoras.index', compact('bitacoras'));
    }
}
