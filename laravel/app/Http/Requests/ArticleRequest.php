<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:50',
            'body' => 'required|max:500',
            'tags' => [
                'json',
                "regex:/^(?!.*\s).+$/u",
                "regex:/^(?!.*\/).*$/u",
                function ($attribute, $value, $fail) {
                    $tags = json_decode($value);
                    if (count($tags) > 5) {
                        return $fail('タグは5個以内で入力してください。');
                    }
                },
            ],
            'images' => 'nullable|array|max:4',
            'images.*' => 'nullable|file|mimes:jpeg,png,jpg,mp4,webm,ogg|max:1048576',
            'isImageDeleted' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '本文',
            'tags' => 'タグ',
            'images' => '画像・動画',
            'images.*' => '画像・動画',
        ];
    }

    public function passedValidation()
    {
        $this->tags = collect(json_decode($this->tags))
            ->slice(0, 5)
            ->map(function ($requestTag) {
                return $requestTag->text;
            });
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'isImageDeleted' => filter_var($this->input('isImageDeleted'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
