<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Page;
use App\Person;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FollowController extends Controller
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
            $person = Person::where('id', $userId)->first();

            // Follow person_id insert
            $follow = new Follow();
            $follow->followed_person_id = $person->id;
            $follow->created_by = auth()->user()->id;
            $follow->save();

            return $this->set_response($follow, 200, 'success', ['Followed Another Person']);
        } catch (\Exception $e) {
            return $e;
        }

    }

    public function followPage(Request $request, $pageId) {
        $pageId = $request->pageId;

        $validator = Validator::make([
            'pageId' => $pageId
        ],
        [
            'pageId' => 'required|exists:pages,id'
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        try {
            $page = Page::where('id', $pageId)->first();

            // Follow page_id insert
            $follow = new Follow();
            $follow->followed_page_id = $page->id;
            $follow->created_by = auth()->user()->id;
            $follow->save();

            return $this->set_response($follow, 200, 'success', ['Page Follow Successfully!']);
        } catch (\Exception $e) {
            return $e;
        }

    }
}
