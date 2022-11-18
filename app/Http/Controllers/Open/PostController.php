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
    public function index(Request $request) {

        // validate inputs
        $inputs = $request->validate([
            'tag' => ['nullable', 'string'],
        ]);

        $tag = $inputs['tag'] ?? '';

         return inertia('open.index', [
            'suggestTags' => $this->getTagOptions(),
            'tag' => $tag
        ]);
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
            'search' => ['nullable', 'string'],
            'tag' => ['nullable', 'string'],
        ]);

        $nextCursor = $inputs['cursor'] ?? '';
        $search = $inputs['search'] ?? '';
        $tag = $inputs['tag'] ?? '';
        
        $q = Post::where('published', true)
                    ->where(function ($query) use ($search) {
                        $query->where('title', 'like', '%'.$search.'%')
                                ->orWhere('content', 'like', '%'.$search.'%');
                    })
                    ->orderBy('published_at', 'desc');
        
        if ($tag) {
            $q = $q->withAnyTags([$tag]);
        }

        $posts = $q->cursorPaginate(10, ['id', 'title', 'slug', 'content', 'published_at'], 'cursor', $nextCursor);
        
        $posts->map(function($post) {
            $post->tags;  // load tags
            $post->content = Str::limit($post->content, 200);  // trim content as desription
        });
        
        return response()->json($posts);
    }

    /**
     * show a detail post
     *
     * @param Post $post
     * @return void
     */
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
