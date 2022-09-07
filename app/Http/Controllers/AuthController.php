<?php

namespace App\Http\Controllers;

use App\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name'      => 'required',
            'last_name'      => 'required',
            'email'     => 'required|email|unique:people,email',
            'password'  => 'required|string|min:8',
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        // create person
        $person = Person::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' =>  bcrypt($request->password),
        ]);

        // return ['person' => $person];

        return $this->set_response($person, 200, 'success', ['Person Created Successfully']);
    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        $credentials =  $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->set_response(null, 400, 'failed', ['Credentials does not match']);
        }

        if(Auth::attempt($credentials)) {
            $user = $request->user();
            $tokenRes = $user->createToken('Create Token');
            $token = $tokenRes->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            $tokenData = [
                'access_token'  => $tokenRes->accessToken,

                'user' => [
                    'token_type'    => 'Bearer',
                    'expires_at'    => Carbon::parse($token->expires_at)->toDateTimeString(),
                    'first_name'    => $user->first_name,
                    'last_name'     => $user->last_name,
                    'email'         => $user->email,
                ]
            ];
            // return ['responseData' => $tokenData];
            return $this->set_response($tokenData, 200, 'success', ['Logged in!']);
        }


    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();

        return $this->set_response(null, 200, 'success', ['Successfully logout']);
    }
}
