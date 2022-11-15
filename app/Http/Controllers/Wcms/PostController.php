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
     * show posts list
     *
     * @return void
     */
    public function index() {
        return inertia('wcms.post.index');
    }

    /**
     * get paginated posts and return as json format
     *
     * @param Request $request
     * @return void
     */
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
        $q = Post::where('title', 'like', '%'.$filterTitle.'%')
                ->where(function ($query) use ($filterPublished) {
                    if (!is_null($filterPublished)) {
                        $query->where('published', $filterPublished);
                    }
                });

        // fix searched pagination less than current_page
        $maxPages = ceil($q->count() / $limit);
        $page = $maxPages < $page ? $maxPages : $page;

        // get posts pagination
        $posts = $q->orderBy($order, $sort)
                    ->paginate(
                        $limit,  // per page
                        ['id', 'title', 'slug', 'published', 'published_at', 'created_at'],  // columns
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
        return inertia('wcms.post.create', [
            'suggestTags' => $this->getTagOptions()
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
            Log::error('Error when creating a post and saving to database: ' . $e->getMessage());
            return back()->withErrors(['error'=>'Error when creating a post and saving to database.']);
        }

        return redirect(route('wcms.posts.index'));
    }

    /**
     * show edit ui
     *
     * @param Post $post
     * @return void
     */
    public function edit(Post $post) {
        if ($post) {
            $post->tags;  // load post tags before pass to ui
            return inertia('wcms.post.edit', [
                'post' => $post,
                'suggestTags' => $this->getTagOptions()
            ]);
        }

        abort(404);
    }

    /**
     * update post to database
     *
     * @param Request $request
     * @param Post $post
     * @return void
     */
    public function update(Request $request, Post $post) {

        if (!$post) {
            return back()->withErrors(['error'=>'Post ' . $post->slug . ' does not exist.']);
        }
        
        // validate inputs
        $inputs = $request->validate([
            'title' => ['required', 'string'],
            'content' => ['nullable', 'string'],
            'tags' => ['required', 'array'],
            'published' => ['nullable', 'boolean']
        ]);

        $post->title = $inputs['title'];

        $post->slug = Str::slug($inputs['title'], '-');

        $post->content = $inputs['content'];

        $tags = array_map(fn ($tag) => Str::title($tag['text']), $inputs['tags']);
        $post->syncTags($tags);

        $post->published = $inputs['published'];

        if ($inputs['published'] && !$post->published_at) {
            $post->published_at = today();
        }

        try {
            $post->save();
        }
        catch(QueryException $e) {
            Log::error('Error when creating a post and saving to database: ' . $e->getMessage());
            return back()->withErrors(['error'=>'Error when creating a post and saving to database.']);
        }

        return redirect(route('wcms.posts.index'));
    }

    /**
     * delete post
     *
     * @param Post $post
     * @return void
     */
    public function destroy(Post $post) {
        if ($post) {
            $post->delete();
            return response()->json(['deleted' => 1]);
        }

        return back()->withErrors(['error'=>'Can not delete, post does not exist.']);
    }

    /**
     * delete selected posts
     *
     * @param Request $request
     * @return void
     */
    public function destroySelected(Request $request) {

        // validate inputs
        $inputs = $request->validate([
            'ids' => ['required', 'array'],
        ]);
        
        foreach($inputs['ids'] as $id) {
            $post = Post::find($id);
            if (!$post) {
                return back()->withErrors(['error'=>'Can not delete, post does not exist.']);
            }
            $post->delete();
        }
        return response()->json(['deleted' => 1]);
    }

    /**
     * toggle post published status
     *
     * @param Post $post
     * @return void
     */
    public function toggle(Post $post) {
        if ($post) {
            $post->published = !$post->published;
            if ($post->published && !$post->published_at) {
                $post->published_at = today();
            }
            $post->save();
            return response()->json(['toggled' => 1]);
        }

        return back()->withErrors(['error'=>'Can not toggle status, post does not exist.']);
    }


    public function toggleSelected(Request $request) {

        // validate inputs
        $inputs = $request->validate([
            'ids' => ['required', 'array'],
        ]);
        
        foreach($inputs['ids'] as $id) {
            $post = Post::find($id);
            if (!$post) {
                return back()->withErrors(['error'=>'Can not toggle status, post does not exist.']);
            }

            $post->published = !$post->published;
            if ($post->published && !$post->published_at) {
                $post->published_at = today();
            }
            $post->save();
        }
        return response()->json(['toggled' => 1]);
    }

    /**
     * get existing tags optons for ui autocomplete usage
     *
     * @return void
     */
    public function getTagOptions() {
        $tags = DB::table('tags')->select('name')->orderBy('name', 'asc')->get()->toArray();
        $tags = array_map(fn ($value) => ['text' => Str::title(json_decode($value->name)->en)], $tags); // wrap for frontend vuejs-tags usage
        return $tags;
    }
}
