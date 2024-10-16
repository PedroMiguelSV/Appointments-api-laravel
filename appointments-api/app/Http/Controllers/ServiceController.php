<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ServiceController extends Controller
{

    public function index()
    {
        return response()->json(Service::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'duration' => 'required|integer',
            'precio' => 'required|numeric|min:0',  
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $service = Service::create($request->all());
        return response()->json($service, 201);
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'duration' => 'sometimes|required|integer',
            'precio' => 'sometimes|required|numeric|min:0',  
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $service = Service::findOrFail($id);
        $service->update($request->all());
        return response()->json($service);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json(null, 204);
    }

}
