<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Wcms posts list
     *
     * @return void
     */
    public function index() {
        return inertia('wcms.post.index', [
            'test' => 'Hello 20221105'
        ]);
    }

    /**
     * Show post create form
     *
     * @return void
     */
    public function create() {
        return inertia('wcms.post.create');
    }

    /**
     * Store created post
     *
     * @return void
     */
    public function store(Request $request) {
        dd($request->all());
    }
}
