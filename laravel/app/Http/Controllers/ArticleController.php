<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Tag;
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

    public function store(ArticleRequest $request, Article $article)
    {
        // 初期化：画像URLはnull
        $imageUrl = null;
        // 画像のアップロード処理
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $firebaseStorage = app('firebase.storage');
            $bucketName = env("FIREBASE_STORAGE_BUCKET");
            $bucket = $firebaseStorage->getBucket($bucketName);

            $filePath = 'articles/' . uniqid() . '.' . $file->getClientOriginalExtension();

            // ファイルをFirebase Storageにアップロード
            $bucket->upload(
                file_get_contents($file->getRealPath()),
                ['name' => $filePath]
            );

            // 公開URLを組み立て
            $filePathEncoded = urlencode($filePath);
            $imageUrl = "https://firebasestorage.googleapis.com/v0/b/{$bucketName}/o/{$filePathEncoded}?alt=media";
        }

        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        // 画像URLを設定（アップロードされた画像があれば、そうでなければnull）
        $article->image_url =  $imageUrl;
        // 記事を保存
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
