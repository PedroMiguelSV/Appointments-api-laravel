<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ServiceController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Service::all());
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar cargar la lista de servicios.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0.01',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'duration.required' => 'El campo duración es obligatorio.',
            'duration.integer' => 'La duración debe ser un número entero.',
            'duration.min' => 'La duración mínima es 1 minuto.',
            'precio.required' => 'El campo precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio debe ser mayor que 0.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $service = Service::create($request->all());
            return response()->json($service, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar guardar el servicio.'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $service = Service::findOrFail($id);
            return response()->json($service);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'El servicio no fue encontrado'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'duration' => 'sometimes|required|integer|min:1',
            'precio' => 'sometimes|required|numeric|min:0.01',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'duration.required' => 'El campo duración es obligatorio.',
            'duration.integer' => 'La duración debe ser un número entero.',
            'duration.min' => 'La duración mínima es 1 minuto.',
            'precio.required' => 'El campo precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio debe ser mayor que 0.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $service = Service::findOrFail($id);
            $service->update($request->all());
            return response()->json($service);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar actualizar el servicio.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();
            return response()->json(['mensaje' => 'Servicio eliminado con éxito'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al intentar eliminar el servicio.'], 500);
        }
    }
}
