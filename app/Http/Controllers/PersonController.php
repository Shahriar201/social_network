<?php

namespace App\Http\Controllers;

use App\Person;
use App\Post;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    use ApiResponser;

    public function personFeed(Request $request) {
        $userId = auth()->user()->id;

        $feed = Post::with('person', 'page', 'follow')
                ->where('created_by', $userId)
                ->get()->toArray();

        return $this->set_response($feed, 200, 'success', ['Logged in user news feed']);
    }
}
