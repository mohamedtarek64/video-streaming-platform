<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    video: {
        id: number;
        title: string;
        slug: string;
        thumbnail_path: string;
        views: number;
        created_at: string;
        channel: {
            name: string;
            avatar_path: string | null;
        };
    };
}>();

const formatViews = (views: number) => {
    if (views >= 1000000) return (views / 1000000).toFixed(1) + 'M';
    if (views >= 1000) return (views / 1000).toFixed(1) + 'K';
    return views.toString();
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (diff < 60) return 'Just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
    return date.toLocaleDateString();
};
</script>

<template>
    <div class="flex flex-col gap-3 group">
        <Link :href="route('videos.show', video.slug)" class="relative aspect-video overflow-hidden rounded-xl bg-gray-100 dark:bg-zinc-800">
            <img 
                :src="video.thumbnail_path" 
                :alt="video.title"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
        </Link>
        <div class="flex gap-3 px-1">
            <div class="shrink-0">
                <div class="w-10 h-10 rounded-full bg-indigo-500 overflow-hidden">
                    <img v-if="video.channel.avatar_path" :src="video.channel.avatar_path" alt="" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-white font-bold">
                        {{ video.channel.name[0] }}
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-1 overflow-hidden">
                <Link :href="route('videos.show', video.slug)" class="font-semibold text-gray-900 dark:text-white line-clamp-2 leading-tight">
                    {{ video.title }}
                </Link>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <div>{{ video.channel.name }}</div>
                    <div class="flex items-center">
                        {{ formatViews(video.views) }} views 
                        <span class="mx-1">•</span> 
                        {{ formatDate(video.created_at) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
