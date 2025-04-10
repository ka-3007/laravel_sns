<template>
    <div class="form-group">
        <!-- 画像選択 -->
        <label for="fileInput" class="font-weight-bold">画像を選択</label>
        <input type="file" ref="fileInput" name="images[]" id="fileInput" class="form-control-file"
            @change="handleImageChange" multiple />

        <!-- 画像プレビュー -->
        <div v-if="imageUrls.length > 0" id="image-wrapper">
            <div class="mt-3 image-grid">
                <div v-for="(imageUrl, index) in imageUrls" :key="index" class="image-preview">
                    <img :src="imageUrl.url || imageUrl" alt="現在の画像" class="img-fluid rounded" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        existingImageUrls: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            imageUrls: this.existingImageUrls.map((url) => ({ url })), // 既存の画像URLをオブジェクト形式に変換
        };
    },
    methods: {
        handleImageChange(event) {
            const files = Array.from(event.target.files);

            if (files.length > 0) {
                // 新しい選択があった場合、既存の画像をすべてクリア
                this.imageUrls = [];

                // 最大4つまでに制限
                const filesToProcess = files.slice(0, 4);

                filesToProcess.forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrls.push({
                            url: e.target.result,
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
        },
    },
};
</script>

<style scoped>
/* グリッドレイアウトのスタイル */
.image-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.image-preview {
    position: relative;
}

.image-preview img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
}

/* 画像選択ボタンのスタイル */
#fileInput {
    padding: 10px;
    border-radius: 8px;
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    cursor: pointer;
    transition: background-color 0.3s;
}

#fileInput:hover {
    background-color: #e6e6e6;
}

#image-wrapper {
    margin-top: 10px;
}
</style>
