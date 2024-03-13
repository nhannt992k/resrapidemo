<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /*  public function index()
    {
        //
    } */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            "username" => "required|max:50",
            "email" => "required|max:255",
            "password" => "required|max:255"
        ]);
        try {
            if ($credentials) {
                $data = User::create([
                    "username" => $request->username,
                    "email" => $request->email,
                    "password" => bcrypt($request->password),
                    "verified" => $request->verified,
                    "state" => true,
                ]);
                return response()->json(['message' => "Create user success"], Response::HTTP_OK);
            } else {
                return response()->json(['message' => "Fail create user "], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            if (!empty($user)) {
                $data = $user->where("id", $user->id)
                    ->select("username", "email", "address", "verified")->get();
                return response()->json($data, Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Cant take information this user"], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            if (!empty($request)) {
                $fields = $request->only("email", "address");
                $fields = array_filter($fields, fn ($value) => !is_null($value));
                if (isset($fields['email'])) {
                    $fields['verified'] = false;
                }
                if (empty($fields)) {
                    return response()->json(['Message' => 'No fields provided for update'], Response::HTTP_NOT_ACCEPTABLE);
                } else {
                    $data = $user->where("id", $user->id)->update($fields);
                    return response()->json([
                        'message' => 'update user success',
                        "data" => $data
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json(["message" => "Update information this user"], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $data = $user->find($user->id)->delete();
            return response()->json(["message" => "Delete user success"], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Change state user the specified resource from storage.
     */
    public function changeState(Request $request, User $user)
    {
        try {
            if (!empty($request)) {
                $data = $user->find($user->id)->update()->only("state", $request->state);
                return response()->json(["message" => "Your accoun has been lock"], Response::HTTP_OK);
            } else {
                return response()->json(["message" => "We cant lock your account, try again soon"], Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Login the specified resource from storage.
     */
    public function login(Request $request)
    {
        try { 
            $credentails = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
         ]);
            if ($credentails) {
                if (!Auth::attempt($request->only('email', 'password'))) {
                    return response()->json(["message" => "Login failed. please try again"], Response::HTTP_UNAUTHORIZED);
                } else {
                    $user = $request->user();
                    $token = $user->createToken('token-name', ['*'], now()->addWeek())->plainTextToken;
                    return response()->json([
                        'message' => 'Login successfully!',
                        'token' =>  $token
                    ], Response::HTTP_OK);
                }
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
            
        }
    }

    /**
     * Logout the specified resource from storage.
     */
    public function logout(User $user)
    {
        try {
            $user->tokens()->delete();
            return response()->json(["message"=> "You has been logout!"], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(["message"=> "You cant logout!"], Response::HTTP_NOT_ACCEPTABLE);
            
        }
    }
}
