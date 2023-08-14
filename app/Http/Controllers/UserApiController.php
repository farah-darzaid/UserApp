<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email:rfc,spoof',
                Rule::unique('users', 'email')->withoutTrashed(),
            ],
            'name' => [
                'required',
                'string',
                'max:191'
            ],
            'birthdate' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before:-18 years'
            ],
            'phone' => [
                'required',
                'numeric',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                Rule::unique('users', 'phone')->withoutTrashed(),
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->messages()
            ]);
        }

        $user = User::query()->create($request->all());

        if ($user->wasRecentlyCreated) {

            Mail::to($user->email)->send(new WelcomeMail($user));

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email:rfc,spoof',
                Rule::unique('users', 'email')->ignore($user->id)->withoutTrashed(),
            ],
            'name' => [
                'required',
                'string',
                'max:191'
            ],
            'birthdate' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before:-18 years'
            ],
            'phone' => [
                'required',
                'numeric',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                Rule::unique('users', 'phone')->ignore($user->id)->withoutTrashed(),
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->messages()
            ]);
        }

        $user->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted'
        ]);
    }
}
