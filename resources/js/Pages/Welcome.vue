<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import VideoCard from '@/Components/VideoCard.vue';
import NavLink from '@/Components/NavLink.vue';
import { ref, watch } from 'vue';

interface Video {
    id: number;
    title: string;
    slug: string;
    thumbnail_path: string;
    views: number;
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
    videos: Pagination;
    filters?: {
        search?: string;
        category?: string;
    };
}>();

const searchTerm = ref(props.filters?.search || '');
const currentCategory = ref(props.filters?.category || 'All');

const search = () => {
    router.get(route('home'), { 
        search: searchTerm.value,
        category: currentCategory.value 
    }, { preserveState: true, replace: true });
};

const filterCategory = (cat: string) => {
    currentCategory.value = cat;
    router.get(route('home'), { 
        search: searchTerm.value,
        category: cat 
    }, { preserveState: true });
};

watch(searchTerm, (val: string) => {
    if (val === '') search();
});

const categories = [
    'All', 'Music', 'Gaming', 'Tech', 'Education', 'Lifestyle'
];
</script>

<template>
    <Head title="Home" />
    
    <div class="min-h-screen bg-gray-50 text-gray-900 dark:bg-black dark:text-gray-100 font-sans selection:bg-indigo-500 selection:text-white">
        <!-- Sidebar and Top Nav integration -->
        <nav class="sticky top-0 z-50 flex items-center justify-between px-6 py-4 bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-4 lg:gap-8">
                <button class="p-2 -ml-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <Link href="/" class="flex items-center gap-2 group">
                    <div class="size-10 bg-indigo-600 rounded-lg flex items-center justify-center transition-transform group-hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">VStream</span>
                </Link>
            </div>
            
            <div class="hidden md:flex flex-1 max-w-2xl px-8">
                <div class="relative w-full">
                    <input 
                        v-model="searchTerm"
                        @keyup.enter="search"
                        type="text" 
                        placeholder="Search for videos, users, and more"
                        class="w-full px-5 py-2.5 bg-gray-100 dark:bg-zinc-800 border-none rounded-full focus:ring-2 focus:ring-indigo-500 transition-all dark:placeholder-gray-500"
                    />
                    <button @click="search" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </div>
            </div>
            
            <div class="flex items-center gap-3 lg:gap-5">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('videos.create')"
                    class="p-2.5 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 relative group"
                    title="Upload Video"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect><line x1="9" y1="9" x2="9" y2="15"></line><line x1="6" y1="12" x2="12" y2="12"></line></svg>
                    <span class="absolute top-full right-0 mt-2 hidden group-hover:block bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap">Upload video</span>
                </Link>
                
                <template v-if="!$page.props.auth.user">
                    <Link :href="route('login')" class="hidden sm:block font-semibold px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-lg">Log in</Link>
                    <Link :href="route('register')" class="bg-indigo-600 text-white font-semibold px-5 py-2.5 rounded-full hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-500/20">Sign up</Link>
                </template>
                <Link v-else :href="route('profile.edit')" class="w-10 h-10 rounded-full bg-indigo-500 border-2 border-transparent hover:border-indigo-500 transition-all flex items-center justify-center text-white font-bold cursor-pointer">
                    {{ $page.props.auth.user.name[0] }}
                </Link>
            </div>
        </nav>
        
        <div class="flex">
            <!-- Sidbar Menu (Left) -->
            <aside class="hidden lg:block w-64 h-[calc(100vh-73px)] sticky top-[73px] p-4 bg-white dark:bg-zinc-950 border-r border-gray-100 dark:border-zinc-900 overflow-y-auto">
                <div class="flex flex-col gap-1">
                    <Link href="/" class="flex items-center gap-4 px-4 py-3 bg-gray-100 dark:bg-zinc-800 rounded-xl font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Home</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 dark:hover:bg-zinc-900 rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                        <span>Trending</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 dark:hover:bg-zinc-900 rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Subscriptions</span>
                    </Link>
                </div>
                
                <hr class="my-6 border-gray-100 dark:border-zinc-900" />
                
                <div class="px-4 mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-zinc-500">You</h3>
                </div>
                <div class="flex flex-col gap-1">
                    <Link href="#" class="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 dark:hover:bg-zinc-900 rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <span>Watch History</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 dark:hover:bg-zinc-900 rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        <span>Liked Videos</span>
                    </Link>
                </div>
            </aside>
            
            <main class="flex-1 min-h-screen p-4 md:p-8">
                <!-- Categories Bar -->
                <div class="flex gap-2 overflow-x-auto pb-6 scrollbar-hide no-scrollbar">
                    <button 
                        v-for="cat in categories" 
                        :key="cat"
                        @click="filterCategory(cat)"
                        class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-semibold transition-all"
                        :class="cat === currentCategory ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700'"
                    >
                        {{ cat }}
                    </button>
                </div>

                <!-- Video Grid -->
                <div v-if="videos.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-10">
                    <VideoCard 
                        v-for="video in videos.data" 
                        :key="video.id" 
                        :video="video" 
                    />
                </div>
                
                <div v-else class="flex flex-col items-center justify-center py-24 text-center">
                    <div class="size-24 bg-gray-100 dark:bg-zinc-900 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">No videos yet</h2>
                    <p class="text-gray-500 max-w-sm">Be the first to upload a video and start sharing your content with the world.</p>
                    <Link v-if="$page.props.auth.user" :href="route('videos.create')" class="mt-8 bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition-all">Upload Now</Link>
                    <Link v-else :href="route('register')" class="mt-8 bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition-all">Get Started</Link>
                </div>

                <!-- Pagination Links -->
                <div v-if="videos.links.length > 3" class="mt-16 flex justify-center gap-2">
                    <Link 
                        v-for="link in videos.links" 
                        :key="link.label"
                        :href="link.url!"
                        v-html="link.label"
                        class="px-4 py-2 rounded-lg text-sm"
                        :class="link.active ? 'bg-indigo-600 text-white font-bold' : 'hover:bg-gray-100 dark:hover:bg-zinc-900 text-gray-500 dark:text-gray-400'"
                    />
                </div>
            </main>
        </div>
    </div>
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
