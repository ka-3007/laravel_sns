<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Services\TagService;

class ArticleController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->authorizeResource(Article::class, 'article');
        $this->tagService = $tagService;
    }

    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at')->load(['user', 'likes', 'tags']);

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        //タグテーブルから全てのタグ情報を取得
        $allTagNames = $this->tagService->getAllTags();

        return view('articles.create', compact('allTagNames'));
    }

    public function store(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();

        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        //タグテーブルから全てのタグ情報を取得
        $allTagNames = $this->tagService->getAllTags();

        return view('articles.edit', compact('article', 'tagNames', 'allTagNames'));
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all())->save();

        $article->tags()->detach();
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function like(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }
}
