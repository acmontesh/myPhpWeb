<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Services\ThemeManager;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function index(ThemeManager $themes): View
    {
        $publications = Publication::active()->ordered()->get();

        return view($themes->view('publications.index'), compact('publications'));
    }
}
