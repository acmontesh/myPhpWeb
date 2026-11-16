<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Services\ThemeManager;
use Illuminate\View\View;

class AwardController extends Controller
{
    public function index(ThemeManager $themes): View
    {
        $awards = Award::active()->ordered()->get();

        return view($themes->view('awards.index'), compact('awards'));
    }
}
