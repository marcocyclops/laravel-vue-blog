<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index() {
        
        return inertia('wcms.post.index', [
            'test' => 'Hello 20221105'
        ]);
    }
}
