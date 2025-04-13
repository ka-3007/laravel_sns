<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use App\Services\FirebaseImageUploader;
use Illuminate\Http\Request;
use App\Services\TagService;
use Illuminate\Support\Str;

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

    public function store(ArticleRequest $request, Article $article, FirebaseImageUploader $uploader)
    {
        // アップロードされた画像があればFirebaseにアップロード
        $imagePaths = null;
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $imagePaths = $uploader->upload($files, 'articles');
        }

        // 記事データの保存
        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->image_paths = $imagePaths ? json_encode($imagePaths) : null;
        $article->save();

        // タグを保存
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

    public function update(ArticleRequest $request, Article $article, FirebaseImageUploader $uploader)
    {
        // 既存の画像を保持
        $imagePaths = $article->image_paths ? json_decode($article->image_paths, true) : null;

        // isImageDeleted が true の場合は null にする
        if ($request->isImageDeleted) {
            $imagePaths = null;
        }

        // アップロードされた画像があればFirebaseにアップロード
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $imagePaths = $uploader->upload($files, 'articles');
        }

        // 記事データの保存
        $article->image_paths = $imagePaths ? json_encode($imagePaths) : null;
        $article->fill($request->all())->save();

        // 記事に紐づいている既存のタグをすべて解除する（多対多の関係を一旦リセット）
        $article->tags()->detach();
        // タグを保存
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
