<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ResearchProject;
use App\Services\ThemeManager;
use Illuminate\View\View;

class ResearchController extends Controller
{
    public function index(ThemeManager $themes): View
    {
        $projects = ResearchProject::active()->ordered()->get();

        return view($themes->view('research.index'), compact('projects'));
    }
}
