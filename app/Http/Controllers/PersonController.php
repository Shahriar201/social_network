<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Post;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    use ApiResponser;

    public function personFeed(Request $request) {
        $userId = auth()->user()->id;

        // Get logged In person page & post
        $feed = Post::with('person', 'page')
                ->where('created_by', $userId)
                ->get()->toArray();

        // logged In person follow data
        $follow = Follow::where('created_by', $userId)->get()->toArray();

        // All info pushed into an array
        $newsFeed = [
            'newsFeed' => $feed,
            'follow' => $follow
        ];

        return $this->set_response($newsFeed, 200, 'success', ['Logged in user news feed']);
    }
}
