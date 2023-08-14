<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required'
//                'email:rfc,spoof',
//                Rule::unique('users', 'email')->withoutTrashed(),
            ],
//            'name' => [
//                'required',
//                'string',
//                'max:191'
//            ],
//            'birthday' => [
//                'required',
//                'date',
//                'date_format:Y-m-d',
//                'before:-18 years'
//            ],
//            'phone' => [
//                'required',
//                'string',
//                'between:8,11',
//                Rule::unique('users', 'phone')->withoutTrashed(),
//            ]
        ]);

        dd($request->validated());


        dd('store user data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
