<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   

        $userId = auth()->user();
        $user = User::find($userId);

        $clients = $user->clients()->paginate(10);

        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'population' => 'required',
            'active' => 'required|boolean',
            'category' => ['required', Rule::in(CategoryType::cases())],
            'photo' => 'nullable',
        ]);

        $client = new Client($validatedData);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $client->photo = $path;
        }
    

        $client->save();
        

    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client,)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required',
            'surname' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:clients,email,' . $client->id, 
            'population' => 'sometimes|required',
            'active' => 'sometimes|required|boolean',
            'category' => ['sometimes', 'required', Rule::in(CategoryType::cases())],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    

        $client->fill($validatedData);
    
  
        if ($request->hasFile('photo')) {
            if ($client->photo) {
                Storage::delete('public/' . $client->photo);
            }
        
            $path = $request->file('photo')->store('photos', 'public');
            $client->photo = $path;
        }
    
        $client->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $client = Client::findOrFail($request->id);

        if(!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

    if ($client->photo) {
        Storage::delete('public/' . $client->photo);
    }


    $client->delete();
    }
}
