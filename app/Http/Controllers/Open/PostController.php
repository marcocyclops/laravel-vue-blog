<?php

namespace App\Http\Controllers\Open;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * open posts list page without data
     *
     * @return void
     */
    public function index() {
         return inertia('open.index');
    }

    /**
     * get posts list data
     *
     * @param Request $request
     * @return void
     */
    public function list(Request $request) {
        
        // validate inputs
        $inputs = $request->validate([
            'cursor' => ['nullable', 'string'],
        ]);

        $nextCursor = $inputs['cursor'] ?? null;
        
        $q = Post::where('published', true)
                    ->orderBy('published_at', 'desc');

        $posts = $q->cursorPaginate(10, ['id', 'title', 'slug', 'content', 'published_at'], 'cursor', $nextCursor);

        $posts->map(function($post) {
            $post->tags;  // load tags
            $post->content = Str::limit($post->content, 200);  // trim content as desription
        });
        
        return response()->json($posts);
    }

    public function show(Post $post) {
        if ($post) {
            $post->tags;  // load post tags before pass to ui
            return inertia('open.show', [
                'post' => $post
            ]);
        }

        abort(404);
    }
}
