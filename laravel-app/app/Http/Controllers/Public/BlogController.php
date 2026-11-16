<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\ThemeManager;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(ThemeManager $themes): View
    {
        $posts = BlogPost::published()->latest('published_at')->paginate(9);

        return view($themes->view('blog.index'), compact('posts'));
    }

    public function show(ThemeManager $themes, BlogPost $post): View
    {
        abort_unless($post->isPublished(), 404);

        return view($themes->view('blog.show'), compact('post'));
    }
}
