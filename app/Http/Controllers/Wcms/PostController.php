<?php

namespace App\Http\Controllers\Wcms;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        $posts = Post::select('id', 'title', 'published', 'published_at', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
                
        return inertia('wcms.post.index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show post create form
     *
     * @return void
     */
    public function create() {
        $tags = DB::table('tags')->select('name')->orderBy('name', 'asc')->get()->toArray();
        $tags = array_map(fn ($value) => ['text' => Str::title(json_decode($value->name)->en)], $tags); // wrap for frontend vuejs-tags usage

        return inertia('wcms.post.create', [
            'suggestTags' => $tags
        ]);
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

        $tags = array_map(fn ($tag) => Str::title($tag['text']), $inputs['tags']);
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
            return back()->withErrors(['error'=>'Error when creating a post and saving to database.']);
        }

        return redirect(route('wcms.posts.index'));
    }
}
