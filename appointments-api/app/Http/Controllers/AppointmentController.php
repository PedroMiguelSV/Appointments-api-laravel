<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentController extends Controller
{

    public function index()
    {
        return response()->json(Appointment::with('client', 'services')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id', 
            'note' => 'nullable|string|max:255',                  
            'date' => 'required|date',                    
            'time' => 'required|date_format:H:i',         
            'services' => 'required|array|exists:services,id',  
        ], [
            'client_id.required' => 'El cliente es obligatorio.',
            'client_id.exists' => 'El cliente seleccionado no es válido.',
            'note.string' => 'La nota debe ser un texto válido.',
            'note.max' => 'La nota no puede exceder los 255 caracteres.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe tener un formato válido.',
            'time.required' => 'La hora es obligatoria.',
            'time.date_format' => 'La hora debe tener el formato HH:mm.',
            'services.required' => 'El servicio es obligatorio.',
            'services.array' => 'Los servicios deben ser un arreglo.',
            'services.exists' => 'Algunos servicios no son válidos.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $appointment = Appointment::create($request->all());

        if ($request->has('services')) {
            $appointment->services()->attach($request->services);
        }

        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        try {
            $appointment = Appointment::with('client', 'services')->findOrFail($id);
            return response()->json($appointment);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'sometimes|required|exists:clients,id', 
            'note' => 'sometimes|nullable|string|max:255',   
            'date' => 'sometimes|required|date',                    
            'time' => 'sometimes|required|date_format:H:i',        
            'services' => 'sometimes|required|array|exists:services,id', 
        ], [
            'client_id.required' => 'El cliente es obligatorio.',
            'client_id.exists' => 'El cliente seleccionado no es válido.',
            'note.string' => 'La nota debe ser un texto válido.',
            'note.max' => 'La nota no puede exceder los 255 caracteres.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe tener un formato válido.',
            'time.required' => 'La hora es obligatoria.',
            'time.date_format' => 'La hora debe tener el formato HH:mm.',
            'services.required' => 'El servicio es obligatorio.',
            'services.array' => 'Los servicios deben ser un arreglo.',
            'services.exists' => 'Algunos servicios no son válidos.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->all());

        if ($request->has('services')) {
            $appointment->services()->sync($request->services);
        }

        return response()->json($appointment);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->services()->detach();
        $appointment->delete();

        return response()->json(['mensaje' => 'Cita eliminada con éxito.'], 204);
    }

    public function indexView()
    {
        $appointments = AppointmentsView::all();

        return response()->json($appointments);
    }
}
