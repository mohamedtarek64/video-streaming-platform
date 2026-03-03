<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import VideoCard from '@/Components/VideoCard.vue';
import VideoLayout from '@/Layouts/VideoLayout.vue';
import axios from 'axios';

interface Video {
    id: number;
    title: string;
    slug: string;
    thumbnail_path: string;
    views: number;
    category: string;
    created_at: string;
    channel: {
        id: number;
        name: string;
        avatar_path: string | null;
        slug: string;
    }
}

interface Pagination {
    data: Video[];
    links: Array<{ url: string | null, label: string, active: boolean }>;
}

const props = defineProps<{
    videos?: Pagination;
    title?: string;
    filters?: {
        search?: string;
        category?: string;
        filter?: 'trending' | 'subscriptions';
    };
}>();

const localVideos = ref<Pagination | null>(null);
const currentVideos = computed(() => props.videos || localVideos.value);
const currentCategory = ref(props.filters?.category || 'All');

const fetchVideos = async (params: any = {}) => {
    try {
        const response = await axios.get('/api/videos', { params, headers: { 'Accept': 'application/json' } });
        localVideos.value = response.data.videos;
    } catch (err) {
        console.error('Failed to fetch videos:', err);
    }
};

onMounted(() => {
    if (!props.videos) {
        fetchVideos(props.filters);
    }
});

const filterCategory = (cat: string) => {
    currentCategory.value = cat;
    fetchVideos({ 
        search: props.filters?.search,
        category: cat 
    });
};

const categories = [
    'All', 'Music', 'Gaming', 'Tech', 'Education', 'Lifestyle'
];
</script>

<template>
    <Head title="Home" />
    
    <VideoLayout :filters="filters">
        <!-- Categories Bar -->
        <div class="flex gap-2 overflow-x-auto pb-6 scrollbar-hide no-scrollbar -mt-2">
            <button 
                v-for="cat in categories" 
                :key="cat"
                @click="filterCategory(cat)"
                class="whitespace-nowrap px-4 py-1.5 rounded-xl text-sm font-semibold transition-all"
                :class="cat === currentCategory ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700'"
            >
                {{ cat }}
            </button>
        </div>

        <h1 v-if="title" class="text-2xl font-black mb-8">{{ title }}</h1>

        <!-- Video Grid -->
        <div v-if="currentVideos && currentVideos.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-10">
            <VideoCard 
                v-for="video in currentVideos.data" 
                :key="video.id" 
                :video="video" 
            />
        </div>
        
        <div v-else-if="currentVideos" class="flex flex-col items-center justify-center py-24 text-center">
            <div class="size-24 bg-gray-100 dark:bg-zinc-900 rounded-full flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
            </div>
            <h2 class="text-2xl font-bold mb-2">No videos yet</h2>
            <p class="text-gray-500 max-w-sm">Be the first to upload a video and start sharing your content with the world.</p>
            <Link :href="route('videos.create')" class="mt-8 bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/30">Upload Now</Link>
        </div>

        <div v-else class="flex justify-center py-24">
             <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600"></div>
        </div>

        <!-- Pagination -->
        <div v-if="currentVideos && currentVideos.links.length > 3" class="mt-16 flex justify-center gap-2">
            <button 
                v-for="link in currentVideos.links" 
                :key="link.label"
                @click="fetchVideos()" 
                v-html="link.label"
                class="px-4 py-2 rounded-xl text-sm"
                :class="link.active ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/20' : 'hover:bg-gray-100 dark:hover:bg-zinc-900 text-gray-500 dark:text-gray-400'"
            />
        </div>
    </VideoLayout>
</template>

<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
