<?php

namespace App\Http\Controllers;

use App\Page;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    use ApiResponser;

    public function createPage(Request $request) {
        $validator = Validator::make($request->all(), [
            'page_name'      => 'required',
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        // Create Page
        $page = new Page();
        $page->page_name = $request->page_name;
        $page->created_by = auth()->user()->id;
        $page->save();

        return $this->set_response($page, 200, 'success', ['Person Created Successfully']);
    }
}
