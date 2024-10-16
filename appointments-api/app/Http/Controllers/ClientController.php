<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ClientController extends Controller
{
    
    public function index()
    {
        return response()->json(Client::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json($client);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:clients,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $client = Client::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(null, 204);
    }


    public function search(Request $request)
{
    $query = Client::query();

    if ($request->has('name') && !empty($request->input('name'))) {
        $query->where('name', 'like', $request->input('name') . '%');
    }

    if ($request->has('phone') && !empty($request->input('phone'))) {
        $query->orWhere('phone', 'like', '%' . $request->input('phone') . '%');
    }

    $clients = $query->select('id', 'name', 'phone')->get();

    if ($clients->isEmpty()) {
        return response()->json([], 200);  
    }
    
    return response()->json($clients);
}

}
