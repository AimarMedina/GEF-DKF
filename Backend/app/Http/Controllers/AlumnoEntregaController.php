<?php

namespace App\Http\Controllers;

use App\Models\AlumnoEntrega;
use Illuminate\Http\Request;

class AlumnoEntregaController extends Controller
{
    public function entregarCuaderno(Request $request, $id)
{
    $urlCuaderno = null;

    if ($request->hasFile('cuaderno')) {
        $archivo = $request->file('cuaderno');
        $nombreOriginal = $archivo->getClientOriginalName();

        $nombreFinal = time() . '_' . $nombreOriginal;

        $path = $archivo->storeAs('cuadernos', $nombreFinal, 'public');

        $urlCuaderno = asset('storage/' . $path);
    }

    return AlumnoEntrega::updateOrCreate(
        [
            'ID_Alumno' => $id,
            'ID_Entrega' => $request->ID_Entrega
        ],
        [
            'URL_Cuaderno' => $urlCuaderno,
            'Fecha_Entrega' => now()
        ]
    );
}

    public function descargarCuaderno($id)
{
    $entrega = AlumnoEntrega::findOrFail($id);

    $path = str_replace(asset('storage/'), '', $entrega->URL_Cuaderno);
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        return response()->json(['error' => 'Archivo no encontrado'], 404);
    }

    return response()->download($fullPath);
}

}
