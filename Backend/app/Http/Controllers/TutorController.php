<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TutorController extends Controller
{
    public function grados($id)
    {
        return Tutor::findOrFail($id)->grados;
    }

    public function getTutoresDisponibles()
    {
        // 1. Obtenemos los IDs de los tutores que YA tienen un grado asignado
        $idsOcupados = DB::table('grado')
            ->whereNotNull('ID_Tutor')
            ->pluck('ID_Tutor')
            ->toArray();

        // 2. Buscamos usuarios tipo 'tutor' que NO estén en esa lista
        $tutoresLibres = User::where('tipo', 'tutor')
            ->whereNotIn('id', $idsOcupados)
            ->select('id', 'nombre', 'apellidos', 'email') // Seleccionamos campos necesarios
            ->orderBy('nombre')
            ->get();

        // Formateamos para que sea fácil de leer en el buscador (Nombre + Apellidos)
        $tutoresLibres->transform(function ($tutor) {
            $tutor->nombre_completo = $tutor->nombre . ' ' . ($tutor->apellidos ?? '');
            return $tutor;
        });

        return response()->json($tutoresLibres);
    }
}
