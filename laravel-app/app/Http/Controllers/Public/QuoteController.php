<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Services\ThemeManager;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function index(ThemeManager $themes): View
    {
        $quotes = Quote::active()->ordered()->get();

        return view($themes->view('quotes.index'), compact('quotes'));
    }
}
