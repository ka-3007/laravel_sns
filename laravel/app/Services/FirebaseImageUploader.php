<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FirebaseImageUploader
{
    protected $bucket;
    protected $bucketName;

    public function __construct()
    {
        $firebaseStorage = app('firebase.storage');
        $this->bucketName = env('FIREBASE_STORAGE_BUCKET');
        $this->bucket = $firebaseStorage->getBucket($this->bucketName);
    }

    /**
     * 単一または複数ファイルをアップロードして、公開URLを返す
     *
     * @param array<UploadedFile>|UploadedFile $files
     * @param string $directory
     * @return array<string> アップロードされた画像のURLリスト
     */
    public function upload($files, string $directory = 'articles'): array
    {
        $files = is_array($files) ? $files : [$files];
        $imageUrls = [];

        foreach ($files as $file) {
            $filePath = $directory . '/' . uniqid() . '.' . $file->getClientOriginalExtension();

            $this->bucket->upload(
                file_get_contents($file->getRealPath()),
                ['name' => $filePath]
            );

            $filePathEncoded = urlencode($filePath);
            $imageUrl = "https://firebasestorage.googleapis.com/v0/b/{$this->bucketName}/o/{$filePathEncoded}?alt=media";
            $imageUrls[] = $imageUrl;
        }

        return $imageUrls;
    }
}
