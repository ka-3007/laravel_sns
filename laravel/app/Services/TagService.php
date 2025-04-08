<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    /**
     * すべてのタグを取得して、特定のフォーマットに変換する
     *
     * @return array
     */
    public function getAllTags(): array
    {
        return Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        })->toArray();
    }
}
