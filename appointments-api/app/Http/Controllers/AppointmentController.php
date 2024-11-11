<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AppointmentController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Appointment::with('client', 'services')->get());
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar cargar las citas.'], 500);
        }
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

        try {
            $appointment = Appointment::create($request->all());

            if ($request->has('services')) {
                $appointment->services()->attach($request->services);
            }

            return response()->json($appointment, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar guardar cita.'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $appointment = Appointment::with('client', 'services')->findOrFail($id);
            return response()->json($appointment);
        } catch (Exception $e) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
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

        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->update($request->all());

            if ($request->has('services')) {
                $appointment->services()->sync($request->services);
            }

            return response()->json($appointment);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar actualizar la cita.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->services()->detach();
            $appointment->delete();

            return response()->json(['mensaje' => 'Cita eliminada con éxito.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al intentar eliminar la cita'], 500);
        }
    }

    public function indexView()
    {
        try {
            $appointments = AppointmentsView::all();
            return response()->json($appointments);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar cargar la vista'
            ], 500);
        }        
    }
}
