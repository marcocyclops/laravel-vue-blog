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
        return inertia('wcms.post.index');
    }

    public function list(Request $request) {
        
        // validate requested query string
        $req = $request->validate([
            'page' => ['nullable', 'integer'],
            'limit' => ['nullable', 'integer'],
            'order' => ['nullable', 'string'],
            'sort' => ['nullable', 'string'],
            'filter_title' => ['nullable', 'string'],
            'filter_published' => ['nullable', 'string'],
        ]);

        // get request query string
        $page = $req['page'] ?? 1;

        $limit = $req['limit'] ?? 10;

        $order = $req['limit'] ?? 'created_at';

        $sort = $req['sort'] ?? 'desc';

        if (!isset($req['filter_title'])) {
            $filterTitle = '';
        }
        elseif (is_null($req['filter_title']) || strtolower(trim($req['filter_title'])) == 'null') {
            $filterTitle = '';
        }
        else {
            $filterTitle = strtolower(trim($req['filter_title']));
        }

        if (!isset($req['filter_published'])) {
            $filterPublished = null;
        }
        elseif (is_null($req['filter_published']) || strtolower(trim($req['filter_published'])) == 'null') {
            $filterPublished = null;
        }
        elseif (strtolower(trim($req['filter_published'])) == 'false' || strtolower(trim($req['filter_published'])) == '0') {
            $filterPublished = 0;
        }
        else{
            $filterPublished = 1;
        }
        
        // eloquent query
        $posts = Post::where('title', 'like', '%'.$filterTitle.'%')
                ->where(function ($query) use ($filterPublished) {
                    if (!is_null($filterPublished)) {
                        $query->where('published', $filterPublished);
                    }
                })
                ->orderBy($order, $sort)
                ->paginate(
                    $limit,  // per page
                    ['id', 'title', 'published', 'published_at', 'created_at'],  // columns
                    'page',  // page name
                    $page // page number
                );
                
        // return paginated posts in json format
        return response()->json($posts);
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
