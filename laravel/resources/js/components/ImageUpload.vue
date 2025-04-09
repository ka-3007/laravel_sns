<template>
    <div class="form-group">
        <label>画像</label>
        <input type="file" ref="fileInput" name="images[]" class="form-control-file" @change="handleImageChange"
            multiple />

        <div v-if="imageUrls.length > 0" id="image-wrapper">
            <div class="mt-3 image-grid">
                <div v-for="(imageUrl, index) in imageUrls" :key="index">
                    <img :src="imageUrl.url || imageUrl" alt="現在の画像" class="img-fluid" />
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
            default: () => []
        }
    },
    data() {
        return {
            imageUrls: this.existingImageUrls.map(url => ({ url })), // 既存の画像URLをオブジェクト形式に変換
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

                filesToProcess.forEach(file => {
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
    }
};
</script>

<style scoped>
.image-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}
</style>
