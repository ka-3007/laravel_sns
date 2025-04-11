<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FirebaseImageUploader
{
    protected $bucket;
    protected $bucketName;
    protected $imageManager;

    // 最適化パラメータを固定値として定義
    protected const IMAGE_QUALITY = 80;
    protected const MAX_WIDTH = 1200;
    protected const MAX_HEIGHT = null;

    public function __construct()
    {
        // 環境がローカルでない場合のみFirebase設定を行う
        if (app()->environment('production')) {
            $firebaseStorage = app('firebase.storage');
            $this->bucketName = env('FIREBASE_STORAGE_BUCKET');
            $this->bucket = $firebaseStorage->getBucket($this->bucketName);
        }

        // ImageManagerのインスタンスを作成
        $this->imageManager = new ImageManager(new Driver());
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
            // 一時ファイルパスを生成
            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $file->getClientOriginalExtension();

            // ファイル拡張子を取得
            $extension = strtolower($file->getClientOriginalExtension());

            // 画像の場合
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                // 画像を最適化
                $image = $this->imageManager->read($file->getRealPath());

                // 固定値を使ってリサイズ
                if (self::MAX_WIDTH || self::MAX_HEIGHT) {
                    $image->scale(width: self::MAX_WIDTH, height: self::MAX_HEIGHT);
                }

                // 画像フォーマットに応じた保存処理
                if (in_array($extension, ['jpg', 'jpeg'])) {
                    $image->toJpeg(self::IMAGE_QUALITY)->save($tempPath);
                } elseif ($extension === 'png') {
                    $image->toPng()->save($tempPath);
                } elseif ($extension === 'gif') {
                    $image->toGif()->save($tempPath);
                } elseif ($extension === 'webp') {
                    $image->toWebp(self::IMAGE_QUALITY)->save($tempPath);
                } else {
                    $image->save($tempPath);
                }
            }
            // 動画の場合
            elseif (in_array($extension, ['mp4', 'webm', 'ogg'])) {
                // 動画ファイルをそのまま保存
                $file->move(sys_get_temp_dir(), $tempPath);
            }

            // 環境に応じて処理を切り替える
            if (app()->environment('production')) {
                // Firebaseにアップロード
                $filePath = $directory . '/' . uniqid() . '.' . $extension;
                $this->bucket->upload(
                    file_get_contents($tempPath),
                    ['name' => $filePath]
                );

                // FirebaseのURLを取得
                $filePathEncoded = urlencode($filePath);
                $imageUrl = "https://firebasestorage.googleapis.com/v0/b/{$this->bucketName}/o/{$filePathEncoded}?alt=media";
                $imageUrls[] = $imageUrl;
            } else {
                // ローカルに保存
                $filePath = $directory . '/' . uniqid() . '.' . $extension;
                Storage::disk('public')->put($filePath, file_get_contents($tempPath));

                // ローカルURLを取得
                $imageUrl = asset('storage/' . $filePath);
                $imageUrls[] = $imageUrl;
            }

            // 一時ファイルを削除
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }

        return $imageUrls;
    }
}
