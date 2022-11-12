<?php

namespace App\Http\Controllers\Wcms;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Models\Post;

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

        // validate inputs
        $inputs = $request->validate([
            'title' => ['required', 'string'],
            'content' => ['nullable', 'string'],
            'tags' => ['required', 'array'],
            'published' => ['nullable', 'boolean']
        ]);

        $tags = array_map(fn ($tag) => $tag['text'], $inputs['tags']);
        $inputs['tags'] = $tags;

        $inputs['slug'] = Str::slug($inputs['title'], '-');

        if ($inputs['published']) {
            $inputs['published_at'] = today();
        }

        try {
            Post::create($inputs);
        }
        catch(QueryException $e) {
            Log::error($e->getMessage());
            return back()->withErrors(['error'=>'Error when creating Post.']);
        }

        return redirect(route('wcms.posts.index'));
    }
}