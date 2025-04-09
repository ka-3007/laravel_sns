import './bootstrap';
import '../css/app.css';
import { createApp } from 'vue';
import ArticleLike from './components/ArticleLike.vue';
import ArticleTagsInput from './components/ArticleTagsInput.vue';
import FollowButton from './components/FollowButton.vue';

const app = createApp({});
app.component('article-like', ArticleLike);
app.component('article-tags-input', ArticleTagsInput);
app.component('follow-button', FollowButton);
app.mount('#app');
