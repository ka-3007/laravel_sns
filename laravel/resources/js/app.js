import './bootstrap';
import '../css/app.css';
import { createApp } from 'vue';
import ArticleLike from './components/ArticleLike.vue';

const app = createApp({});
app.component('article-like', ArticleLike);
app.mount('#app');
