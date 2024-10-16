<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
            'services' => 'array|exists:services,id',  
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
        $appointment = Appointment::with('client', 'services')->findOrFail($id);
        return response()->json($appointment);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'sometimes|required|exists:clients,id', 
            'note' => 'sometimes|nullable|string|max:255',   
            'date' => 'sometimes|required|date',                    
            'time' => 'sometimes|required|date_format:H:i',        
            'services' => 'sometimes|array|exists:services,id', 
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
        
        return response()->json(null, 204);
    }

    public function indexView()
    {
        $appointments = AppointmentsView::all();

        return response()->json($appointments);
    }
}
