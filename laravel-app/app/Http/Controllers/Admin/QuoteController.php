<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuoteRequest;
use App\Models\Quote;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function __construct(private readonly ImageUploadService $images)
    {
    }

    public function index(): View
    {
        $quotes = Quote::query()->ordered()->paginate(15);

        return view('admin.quotes.index', compact('quotes'));
    }

    public function create(): View
    {
        return view('admin.quotes.create', ['quote' => new Quote()]);
    }

    public function store(QuoteRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $quote = new Quote();
        $quote->author_name = $data['author_name'];
        $quote->setTranslations('quote_text', array_filter($data['quote_text']));
        $quote->display_order = $data['display_order'] ?? 0;
        $quote->active = $request->boolean('active');

        if ($request->hasFile('author_image')) {
            $quote->author_image = $this->images->store($request->file('author_image'), 'uploads/quotes');
        }

        $quote->save();

        return redirect()->route('admin.quotes.index')->with('status', 'Quote created.');
    }

    public function edit(Quote $quote): View
    {
        return view('admin.quotes.edit', compact('quote'));
    }

    public function update(QuoteRequest $request, Quote $quote): RedirectResponse
    {
        $data = $request->validated();

        $quote->author_name = $data['author_name'];
        $quote->setTranslations('quote_text', array_filter($data['quote_text']));
        $quote->display_order = $data['display_order'] ?? 0;
        $quote->active = $request->boolean('active');

        if ($request->boolean('remove_image')) {
            $this->images->delete($quote->author_image);
            $quote->author_image = null;
        }

        if ($request->hasFile('author_image')) {
            $this->images->delete($quote->author_image);
            $quote->author_image = $this->images->store($request->file('author_image'), 'uploads/quotes');
        }

        $quote->save();

        return redirect()->route('admin.quotes.index')->with('status', 'Quote updated.');
    }

    public function destroy(Quote $quote): RedirectResponse
    {
        $this->images->delete($quote->author_image);
        $quote->delete();

        return redirect()->route('admin.quotes.index')->with('status', 'Quote deleted.');
    }
}
