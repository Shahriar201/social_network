<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name'      => 'required',
            'last_name'      => 'required',
            'email'     => 'required|email|unique:persons,email',
            'password'  => 'required|string|min:8',
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }
    }
}
