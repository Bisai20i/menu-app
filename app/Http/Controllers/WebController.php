<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\SubscriptionPlan;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $testimonials = Testimonial::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $faqs = Faq::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $math = $this->generateMathChallenge();

        return view('web.index', array_merge(
            compact('plans', 'testimonials', 'faqs'),
            $math
        ));
    }
    public function storeContact(Request $request)
    {
        // Honey pot check
        if ($request->filled('honey_pot')) {
            return response()->json([
                'success' => false,
                'message' => 'Spam detected.',
            ], 422);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'message'     => 'required|string',
            'math_answer' => 'required|numeric',
        ]);

        if ($request->math_answer != session('contact_math_answer')) {
            return response()->json([
                'success' => false,
                'message' => 'The math answer is incorrect.',
            ], 422);
        }

        Contact::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'message' => $validated['message'],
        ]);

        // Clear session after success
        session()->forget('contact_math_answer');

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully!',
        ]);
    }

    public function articles(Request $request)
    {
        $query = Article::where('is_active', true);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest()->paginate(9);

        if ($request->ajax()) {
            return view('web.articles.article-list', compact('articles'))->render();
        }

        $math = $this->generateMathChallenge();

        return view('web.articles.index', array_merge(
            compact('articles'),
            $math
        ));
    }

    /**
     * Display the specified article.
     */
    public function showArticle($slug)
    {
        $article = Article::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Similar articles: same keywords or random
        $similarArticles = Article::where('is_active', true)
            ->where('id', '!=', $article->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $math = $this->generateMathChallenge();

        return view('web.articles.show', array_merge(
            compact('article', 'similarArticles'),
            $math
        ));
    }

    private function generateMathChallenge()
    {
        $val1 = rand(1, 10);
        $val2 = rand(1, 10);
        session(['contact_math_answer' => $val1 + $val2]);
        
        return ['val1' => $val1, 'val2' => $val2];
    }
}
