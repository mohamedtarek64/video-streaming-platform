<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    user: {
        id: number;
        name: string;
    } | null;
    video: {
        id: number;
        title: string;
        slug: string;
        description: string | null;
        views: number;
        created_at: string;
        thumbnail_path: string;
        user_vote: 'up' | 'down' | null;
        is_subscribed: boolean;
        up_votes_count: number;
        down_votes_count: number;
        channel_id: number;
        channel: {
            id: number;
            user_id: number;
            name: string;
            avatar_path: string | null;
            slug: string;
            subscribers_count: number;
        };
        comments: Array<{
            id: number;
            body: string;
            created_at: string;
            user_id: number;
            user: {
                name: string;
            };
        }>;
    };
    suggestions: Array<{
        id: number;
        title: string;
        slug: string;
        thumbnail_path: string;
        views: number;
        created_at: string;
        channel: {
            name: string;
        };
    }>;
}>();

const commentForm = useForm({
    body: '',
});

const submitComment = () => {
    commentForm.post(route('comments.store', props.video.id), {
        onSuccess: () => commentForm.reset(),
        preserveScroll: true,
    });
};

const deleteComment = (id: number) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        router.delete(route('comments.destroy', id), { preserveScroll: true });
    }
};

const toggleSubscribe = () => {
    router.post(route('channels.subscribe', props.video.channel.id), {}, { preserveScroll: true });
};

const vote = (type: 'up' | 'down') => {
    router.post(route('videos.vote', [props.video.id, type]), {}, { preserveScroll: true });
};

const formatViews = (views: number) => {
    if (views >= 1000000) return (views / 1000000).toFixed(1) + 'M';
    if (views >= 1000) return (views / 1000).toFixed(1) + 'K';
    return views.toString();
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="video.title" />
    
    <div class="min-h-screen bg-white dark:bg-black text-gray-900 dark:text-gray-100 pb-12">
        <!-- Top Nav (simplified) -->
        <nav class="sticky top-0 z-50 flex items-center justify-between px-6 py-4 bg-white/80 dark:bg-black/80 backdrop-blur-md">
            <Link href="/" class="flex items-center gap-2">
                <div class="size-8 bg-indigo-600 rounded flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                </div>
                <span class="font-bold">VStream</span>
            </Link>
            
            <div class="flex-1 max-w-xl mx-8 hidden sm:block">
                 <input 
                    type="text" 
                    placeholder="Search"
                    class="w-full px-4 py-2 bg-gray-100 dark:bg-zinc-900 border-none rounded-full focus:ring-1 focus:ring-indigo-500"
                />
            </div>
            
            <div class="flex items-center gap-4">
                 <button class="p-2 hover:bg-gray-100 dark:hover:bg-zinc-900 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                </button>
                <div class="w-8 h-8 rounded-full bg-indigo-500"></div>
            </div>
        </nav>

        <div class="max-w-[1700px] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 md:px-8 mt-4">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Video Player Aspect Ratio 16:9 -->
                <div class="aspect-video bg-black rounded-2xl overflow-hidden ring-1 ring-gray-200 dark:ring-zinc-800 shadow-2xl relative group">
                    <!-- Dummy Video Placeholder -->
                    <div class="w-full h-full flex flex-col items-center justify-center text-center p-8">
                        <img :src="video.thumbnail_path" class="absolute inset-0 w-full h-full object-cover blur-sm opacity-50" />
                        <div class="relative z-10 flex flex-col items-center">
                            <button class="size-20 bg-indigo-600/90 text-white rounded-full flex items-center justify-center pl-2 hover:bg-indigo-600 hover:scale-110 transition-all shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            </button>
                            <p class="mt-6 font-bold text-2xl tracking-tight text-white drop-shadow-lg">Click to Play Video</p>
                            <p class="text-white/60 mt-2 text-sm italic">Simulated playback mode</p>
                        </div>
                    </div>
                </div>

                <!-- Video Info -->
                <div class="mt-6">
                    <h1 class="text-2xl font-bold leading-tight">{{ video.title }}</h1>
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mt-6 pb-6 border-b border-gray-100 dark:border-zinc-900">
                        <div class="flex items-center gap-4">
                            <Link :href="route('channel.show', video.channel.slug)" class="size-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg overflow-hidden shrink-0">
                                <img v-if="video.channel.avatar_path" :src="video.channel.avatar_path" class="w-full h-full object-cover" />
                                <span v-else>{{ video.channel.name[0] }}</span>
                            </Link>
                            <div class="flex flex-col">
                                <Link :href="route('channel.show', video.channel.slug)" class="font-bold hover:text-indigo-600 transition-colors">{{ video.channel.name }}</Link>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatViews(video.channel.subscribers_count) }} subscribers</span>
                            </div>
                            <button 
                                @click="toggleSubscribe"
                                class="ml-4 px-6 py-2 rounded-full font-bold transition-all"
                                :class="video.is_subscribed ? 'bg-gray-100 dark:bg-zinc-800 text-gray-900 dark:text-white' : 'bg-indigo-600 text-white hover:bg-indigo-700'"
                            >
                                {{ video.is_subscribed ? 'Subscribed' : 'Subscribe' }}
                            </button>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <div class="flex bg-gray-100 dark:bg-zinc-900 rounded-full">
                                <button 
                                    @click="vote('up')"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-200 dark:hover:bg-zinc-800 rounded-l-full border-r border-gray-200 dark:border-zinc-750 transition-colors"
                                    :class="{ 'text-indigo-600': video.user_vote === 'up' }"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" :fill="video.user_vote === 'up' ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                    <span class="font-medium">{{ formatViews(video.up_votes_count) }}</span>
                                </button>
                                <button 
                                    @click="vote('down')"
                                    class="px-4 py-2 hover:bg-gray-200 dark:hover:bg-zinc-800 rounded-r-full transition-colors"
                                    :class="{ 'text-indigo-600': video.user_vote === 'down' }"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" :fill="video.user_vote === 'down' ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zM17 2h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3"></path></svg>
                                </button>
                            </div>
                            <button class="bg-gray-100 dark:bg-zinc-900 px-4 py-2 rounded-full flex items-center gap-2 font-medium hover:bg-gray-200 dark:hover:bg-zinc-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                                Share
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 bg-gray-50 dark:bg-zinc-900 rounded-xl p-4">
                        <div class="flex items-center gap-2 font-bold mb-2">
                            <span>{{ video.views.toLocaleString() }} views</span>
                            <span class="mx-1">•</span>
                            <span>{{ formatDate(video.created_at) }}</span>
                            <span v-if="video.category" class="mx-1 text-indigo-600">#{{ video.category }}</span>
                        </div>
                        <p class="whitespace-pre-line text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                            {{ video.description || 'No description provided.' }}
                        </p>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="mt-8">
                    <h3 class="text-xl font-bold mb-6">{{ video.comments.length }} Comments</h3>
                    
                    <!-- New Comment Input -->
                    <div v-if="user" class="flex gap-4 mb-8">
                        <div class="size-10 rounded-full bg-indigo-600 flex items-center justify-center text-white shrink-0 font-bold">
                            {{ user.name[0] }}
                        </div>
                        <div class="flex-1">
                            <textarea 
                                v-model="commentForm.body"
                                placeholder="Add a comment..." 
                                class="w-full bg-transparent border-b border-gray-200 dark:border-zinc-800 focus:border-indigo-600 outline-none py-2 resize-none transition-all no-scrollbar"
                                rows="1"
                            ></textarea>
                            <div class="flex justify-end gap-3 mt-2">
                                <button @click="commentForm.reset()" class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-full text-sm font-bold">Cancel</button>
                                <button 
                                    @click="submitComment"
                                    :disabled="!commentForm.body || commentForm.processing"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-full text-sm font-bold hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Comment
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-else class="mb-8 p-4 bg-gray-50 dark:bg-zinc-800 rounded-xl text-center">
                        <Link :href="route('login')" class="text-indigo-600 font-bold hover:underline">Log in</Link> to post a comment
                    </div>

                    <!-- Comment List -->
                    <div class="space-y-6">
                        <div v-for="comment in video.comments" :key="comment.id" class="flex gap-4 group">
                            <div class="size-10 rounded-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center shrink-0 font-bold">
                                {{ comment.user.name[0] }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-sm">{{ comment.user.name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(comment.created_at) }}</span>
                                </div>
                                <p class="text-sm dark:text-gray-300 leading-relaxed">{{ comment.body }}</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <button class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 hover:text-indigo-600 font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                        Like
                                    </button>
                                    <button 
                                        v-if="user && (user.id === comment.user_id || user.id === video.channel.user_id)"
                                        @click="deleteComment(comment.id)"
                                        class="flex items-center gap-1.5 text-xs text-red-500 opacity-0 group-hover:opacity-100 transition-opacity font-bold"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggestions Sidebar -->
            <div class="space-y-4">
                <h3 class="font-bold mb-4">You may also like</h3>
                <Link v-for="suggestion in suggestions" :key="suggestion.id" :href="route('videos.show', suggestion.slug)" class="flex gap-3 group">
                    <div class="w-40 aspect-video rounded-lg bg-gray-100 dark:bg-zinc-800 overflow-hidden shrink-0">
                        <img :src="suggestion.thumbnail_path" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                    </div>
                    <div class="flex flex-col gap-1 overflow-hidden">
                        <h4 class="text-sm font-bold line-clamp-2 leading-tight group-hover:text-indigo-600 transition-colors">{{ suggestion.title }}</h4>
                        <div class="text-xs text-gray-500">
                            <div>{{ suggestion.channel.name }}</div>
                            <div>{{ formatViews(suggestion.views) }} views • {{ formatDate(suggestion.created_at) }}</div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</template>
