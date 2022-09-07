<?php

namespace App\Http\Controllers;

use App\Page;
use App\Post;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponser;

    public function personPostPublish(Request $request) {
        $validator = Validator::make($request->all(), [
            'person_post_content'      => 'required',
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        try {
            $personPost = new Post();
            $personPost->person_post_content = $request->person_post_content;
            $personPost->created_by = auth()->user()->id;
            $personPost->save();

            return $this->set_response($personPost, 200, 'success', ['Person Post Created Successfully!']);
        } catch (\Exception $e) {
            return $this->set_response(null, 422, 'failed', ['Fail to Create Person Post']);
        }
    }

    public function personPostPublishInAPage(Request $request, $pageId) {
        $validator = Validator::make([
            'pageId' => $pageId,
            'page_post_content' => $request->page_post_content
        ],
        [
            'pageId' => 'required|exists:pages,id',
            'page_post_content' => 'required'
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        // Get current user id
        $userId = auth()->user()->id;

        // Get pages for logged in user
        $pages = Page::where('created_by', $userId)->get()->toArray();

        try {
            // Check user have pages or not
            if(!$pages) {
                return $this->set_response(null, 422, 'failed', ['This user does not have any pages']);
            }

            // Create page post content if user have page
            $pageContent = new Post();
            $pageContent->page_id = $pageId;
            $pageContent->page_post_content = $request->page_post_content;
            $pageContent->page_post_publish = 1;
            $pageContent->created_by = auth()->user()->id;
            $pageContent->save();

            return $this->set_response($pageContent, 200, 'success', ['Person Post Created Successfully!']);
        } catch (\Exception $e) {
            return $this->set_response(null, 422, 'failed', ['Something went wrong']);
        }
    }
}
