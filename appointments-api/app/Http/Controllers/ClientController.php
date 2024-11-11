<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ClientController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Client::all());
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar cargar la lista de clientes.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string|max:20',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'email.email' => 'El correo electrónico no es válido.',
            'phone.required' => 'El teléfono es obligatorio.'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $client = Client::create($request->all());
            return response()->json($client, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar guardar el cliente.'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $client = Client::findOrFail($id);
            return response()->json($client);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Cliente no encontrado'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:clients,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'email.email' => 'El correo electrónico no es válido.',
            'phone.required' => 'El teléfono es obligatorio.'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $client = Client::findOrFail($id);
            $client->update($request->all());
            return response()->json($client);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar actualizar el cliente.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response()->json(['message' => 'Cliente eliminado exitosamente.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al intentar eliminar el cliente.'], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Client::query();
    
            if ($request->has('name') && !empty($request->input('name'))) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            }
    
            if ($request->has('phone') && !empty($request->input('phone'))) {
                $query->orWhere('phone', 'like', '%' . $request->input('phone') . '%');
            }
    
            $clients = $query->select('id', 'email', 'name', 'phone')->get();
    
            if ($clients->isEmpty()) {
                return response()->json([], 200);
            }
            
            return response()->json($clients);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al realizar la búsqueda de clientes.'
            ], 500);
        }
    }
}
