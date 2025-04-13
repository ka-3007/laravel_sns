<template>
    <div class="form-group">
        <!-- ファイル選択 -->
        <label for="fileInput" class="font-weight-bold">画像・動画を選択</label>
        <input type="file" ref="fileInput" name="images[]" id="fileInput" class="form-control-file"
            @change="handleMediaChange" multiple accept="image/*,video/*" />

        <!-- プレビュー -->
        <div v-if="mediaUrls.length > 0" id="media-wrapper">
            <div class="mt-3 media-grid">
                <div v-for="(media, index) in mediaUrls" :key="index" class="media-preview">
                    <!-- 画像プレビュー -->
                    <img v-if="media.type === 'image'" :src="media.url" alt="画像" class="img-fluid rounded" />
                    <!-- 動画プレビュー -->
                    <video v-else-if="media.type === 'video'" :src="media.url" controls
                        class="img-fluid rounded"></video>
                </div>
            </div>
        </div>

        <!-- ファイルがないときだけ空文字を送る -->
        <input type="hidden" name="isImageDeleted" :value=isDeleted />
        <!-- 画像削除ボタン -->
        <div class="text-right mt-3">
            <button type="button" class="btn btn-outline-danger" @click="resetFileInput">
                <i class="fas fa-trash-alt mr-1"></i> 画像・動画を削除
            </button>
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
            mediaUrls: this.existingImageUrls.map((url) => ({
                url,
                type: this.getMediaType(url),
            })),
            isDeleted: false
        };
    },
    methods: {
        handleMediaChange(event) {
            const files = Array.from(event.target.files);

            if (files.length > 0) {
                this.mediaUrls = [];

                const filesToProcess = files.slice(0, 4);

                filesToProcess.forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.mediaUrls.push({
                            url: e.target.result,
                            type: this.getMediaType(file.name),
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
            // ファイルが選択されたら、削除フラグをfalseに設定
            this.isDeleted = false;
        },
        getMediaType(filename) {
            const cleanFilename = filename.split('?')[0]; // クエリパラメータ除去
            const ext = cleanFilename.split('.').pop().toLowerCase();
            const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            const videoTypes = ['mp4', 'webm', 'ogg'];
            if (imageTypes.includes(ext)) return 'image';
            if (videoTypes.includes(ext)) return 'video';
            return 'unknown';
        },
        resetFileInput() {
            // inputを空にする
            this.$refs.fileInput.value = '';
            // プレビューも初期化
            this.mediaUrls = [];
            // 削除されたとみなす
            this.isDeleted = true;
        }
    },
};
</script>

<style scoped>
.media-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.media-preview {
    position: relative;
}

.media-preview img,
.media-preview video {
    width: 100%;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
}

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

#media-wrapper {
    margin-top: 10px;
}
</style>
