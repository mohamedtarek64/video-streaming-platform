<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import VideoCard from '@/Components/VideoCard.vue';

defineProps<{
    channel: {
        id: number;
        name: string;
        slug: string;
        description: string | null;
        subscribers_count: number;
        avatar_path: string | null;
        banner_path: string | null;
        videos: any[];
        can_edit: boolean;
    }
}>();

const formatViews = (views: number) => {
    if (views >= 1000000) return (views / 1000000).toFixed(1) + 'M';
    if (views >= 1000) return (views / 1000).toFixed(1) + 'K';
    return views.toString();
};
</script>

<template>
    <Head :title="channel.name" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-black text-gray-900 dark:text-gray-100">
        <!-- Top Nav (simplified) -->
        <nav class="sticky top-0 z-50 flex items-center justify-between px-6 py-4 bg-white/80 dark:bg-black/80 backdrop-blur-md">
            <Link href="/" class="flex items-center gap-2">
                <div class="size-8 bg-indigo-600 rounded flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                </div>
                <span class="font-bold">VStream</span>
            </Link>
            <div class="flex-1 max-w-xl mx-8 hidden sm:block font-bold">{{ channel.name }}</div>
            <div class="flex items-center gap-4">
                <div class="w-8 h-8 rounded-full bg-indigo-500"></div>
            </div>
        </nav>

        <!-- Channel Banner -->
        <div class="max-w-[1700px] mx-auto px-4 md:px-8 mt-4">
            <div class="w-full aspect-[4/1] bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl overflow-hidden shadow-2xl relative shadow-indigo-600/20">
                <img v-if="channel.banner_path" :src="channel.banner_path" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                     <span class="text-white/20 font-black text-6xl md:text-9xl tracking-tighter">{{ channel.name }}</span>
                </div>
            </div>

            <!-- Channel Header -->
            <div class="mt-8 flex flex-col md:flex-row items-center md:items-end gap-6 px-4">
                <div class="size-32 md:size-40 rounded-full border-8 border-gray-50 dark:border-black bg-indigo-600 -mt-16 md:-mt-20 relative z-10 overflow-hidden flex items-center justify-center text-white font-black text-4xl shadow-xl">
                    <img v-if="channel.avatar_path" :src="channel.avatar_path" class="w-full h-full object-cover" />
                    <span v-else>{{ channel.name[0] }}</span>
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-black">{{ channel.name }}</h1>
                    <div class="flex items-center justify-center md:justify-start gap-2 text-gray-500 text-sm mt-1 font-bold">
                        <span>{{ formatViews(channel.subscribers_count) }} subscribers</span>
                        <span>•</span>
                        <span>{{ channel.videos.length }} videos</span>
                    </div>
                    <p class="text-sm mt-2 text-gray-700 dark:text-gray-400 max-w-xl leading-relaxed whitespace-pre-line">{{ channel.description || 'No description provided.' }}</p>
                </div>
                
                <div class="flex gap-3 mb-2">
                    <Link v-if="channel.can_edit" :href="route('channel.edit')" class="bg-indigo-600 text-white px-8 py-2.5 rounded-full font-black hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/30">Customize Channel</Link>
                    <button v-else class="bg-indigo-600 text-white px-8 py-2.5 rounded-full font-black hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/30">Subscribe</button>
                    <button class="p-2.5 bg-gray-100 dark:bg-zinc-800 rounded-full hover:bg-gray-200 dark:hover:bg-zinc-700 transition">
                         <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="mt-12 flex gap-8 border-b border-gray-100 dark:border-zinc-900 overflow-x-auto no-scrollbar scroll-smooth">
                <button class="px-2 py-4 border-b-2 border-indigo-600 text-indigo-600 font-black whitespace-nowrap">Home</button>
                <button class="px-2 py-4 border-b-2 border-transparent hover:border-gray-300 font-bold text-gray-500 whitespace-nowrap">Videos</button>
                <button class="px-2 py-4 border-b-2 border-transparent hover:border-gray-300 font-bold text-gray-500 whitespace-nowrap">Playlists</button>
                <button class="px-2 py-4 border-b-2 border-transparent hover:border-gray-300 font-bold text-gray-500 whitespace-nowrap">About</button>
            </div>

            <!-- Video Grid -->
            <div class="mt-12">
                <div v-if="channel.videos.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <VideoCard 
                        v-for="video in channel.videos" 
                        :key="video.id" 
                        :video="{ ...video, channel: { name: channel.name, avatar_path: channel.avatar_path } }" 
                    />
                </div>
                <div v-else class="py-24 text-center">
                    <div class="size-16 bg-gray-100 dark:bg-zinc-900 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                    </div>
                    <p class="font-bold">This channel hasn't uploaded any videos yet.</p>
                </div>
            </div>
        </div>
    </div>
</template>
