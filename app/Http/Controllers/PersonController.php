<?php

namespace App\Http\Controllers;

use App\Person;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    use ApiResponser;

    public function followPerson(Request $request, $personId) {
        $userId = auth()->user()->id;
        $validator = Validator::make([
            'personId' => $personId
        ],
        [
            'personId' => 'required|exists:people,id'
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        try {
            $person = Person::where('id', $userId)->update(['followed_person_id' => $personId]);

            return $this->set_response($person, 200, 'success', ['Followed Another Person']);
        } catch (\Exception $e) {
            return $e;
        }

    }
}
