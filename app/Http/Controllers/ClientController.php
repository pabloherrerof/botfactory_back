<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $userId = auth()->user();
        $user = User::find($userId);

        $query = Client::where('user_id', Auth::id());

        foreach ($request->all() as $key => $value) {
            if (isset($value) && $value !== '') {
                switch ($key) {
                    case 'category_id':
                    case 'active':
                        $query->where($key, $value);
                        break;
                    case 'bigger_than': 
                        $date = now()->subYears($value);
                        $query->where('birthday', '<=', $date);
                        break;
                    case 'smaller_than': 
                        $date = now()->subYears($value);
                        $query->where('birthday', '>=', $date);
                        break;
                    case 'search': 
                        $query->where(function ($query) use ($value) {
                            $query->where('name', 'like', "%{$value}%")
                                  ->orWhere('surname', 'like', "%{$value}%")
                                  ->orWhere('email', 'like', "%{$value}%")
                                  ->orWhere('population', 'like', "%{$value}%");
                        });
                        break;
                }
            }
        }
        $clients = $query->orderBy('id', 'desc')->paginate(10);
        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email',
            'population' => 'required|string|max:255',
            'active' => 'required|boolean',
            'category_id' => 'required|numeric|exists:categories,id',
            'birthday' => 'nullable|date',
        ]);
        
        $validatedData['user_id'] = Auth::id();

        try {
            $client = new Client($validatedData);

            if ($request->hasFile('photo')) {
                DB::transaction(function () use ($request, $client) {
                    $path = $request->file('photo')->store('photos', 'public');
                    $client->photo = $path;
                });
            }

            $client->save();
            return response()->json($client, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create client: ' . $e->getMessage()], 500);
        }

    }


    /**
     * Display the specified resource.
     */
    public function show(Client $client,)
    {
        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'population' => 'required|string|max:255',
            'active' => 'required|boolean',
            'category_id' => 'required|numeric|exists:categories,id',
            'birthday' => 'nullable|date',
        ]);
       try {
            $client->fill($validatedData);

            if ($request->hasFile('photo')) {
                DB::transaction(function () use ($request, $client) {
                    if ($client->photo) {
                        Storage::delete('public/' . $client->photo);
                    }

                    $path = $request->file('photo')->store('photos', 'public');
                    $client->photo = $path;
                });
            }

            $client->save();
            return response()->json($client);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update client: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if ($client->photo) {
            Storage::delete('public/' . $client->photo);
        }

        $client->delete();

        return response()->json(['message' => 'Client successfully deleted']);
    }
}
