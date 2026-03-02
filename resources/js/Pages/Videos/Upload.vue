<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const videoInput = ref<HTMLInputElement | null>(null);
const videoPreview = ref<string | null>(null);

const props = defineProps<{
    myVideos: Array<{
        id: number;
        title: string;
        views: number;
        status: string;
        created_at: string;
    }>;
}>();

const form = useForm({
    title: '',
    description: '',
    video: null as File | null,
    category: 'General',
});

const handleFileChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.video = file;
        videoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('videos.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            videoPreview.value = null;
        }
    });
};
</script>

<template>
    <Head title="Upload Video" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-black text-gray-900 dark:text-gray-100 p-4 md:p-12">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <Link href="/" class="flex items-center gap-2 group">
                    <div class="size-8 bg-black dark:bg-zinc-800 rounded flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </div>
                </Link>
                <h1 class="text-3xl font-black">Upload Video</h1>
                <div class="w-8"></div>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white dark:bg-zinc-900 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-zinc-800">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-500 uppercase tracking-wider">Video Title</label>
                        <input 
                            v-model="form.title"
                            type="text"
                            placeholder="Give your video a catchy title"
                            class="w-full bg-gray-50 dark:bg-zinc-800 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-600 transition-all"
                            required
                        />
                        <div v-if="form.errors.title" class="text-red-500 text-xs mt-1 ml-4">{{ form.errors.title }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-500 uppercase tracking-wider">Description</label>
                        <textarea 
                            v-model="form.description"
                            rows="5"
                            placeholder="Tell viewers what happens in your video"
                            class="w-full bg-gray-50 dark:bg-zinc-800 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-600 transition-all resize-none"
                        ></textarea>
                         <div v-if="form.errors.description" class="text-red-500 text-xs mt-1 ml-4">{{ form.errors.description }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-500 uppercase tracking-wider">Category</label>
                        <select 
                            v-model="form.category"
                            class="w-full bg-gray-50 dark:bg-zinc-800 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-600 transition-all appearance-none cursor-pointer"
                        >
                            <option>General</option>
                            <option>Music</option>
                            <option>Gaming</option>
                            <option>Tech</option>
                            <option>Education</option>
                        </select>
                         <div v-if="form.errors.category" class="text-red-500 text-xs mt-1 ml-4">{{ form.errors.category }}</div>
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="block text-sm font-bold mb-2 text-gray-500 uppercase tracking-wider">Video File</label>
                    <div 
                        class="flex-1 aspect-video rounded-3xl border-3 border-dashed border-gray-200 dark:border-zinc-800 flex flex-col items-center justify-center p-8 bg-gray-50 dark:bg-zinc-800/50 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all cursor-pointer relative overflow-hidden group"
                        @click="videoInput?.click()"
                    >
                        <template v-if="!videoPreview">
                            <div class="size-16 bg-indigo-600 rounded-full flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-indigo-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </div>
                            <p class="font-bold text-lg">Click to upload video</p>
                            <p class="text-gray-500 text-sm mt-1">MP4, MOV or AVI files up to 50MB</p>
                        </template>
                        <template v-else>
                            <div class="absolute inset-0 bg-green-500/10 flex items-center justify-center">
                                <video :src="videoPreview" class="w-full h-full object-cover grayscale-0 opacity-80"></video>
                                <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                     <span class="font-bold">File Selected</span>
                                     <span class="text-xs opacity-80 mt-1">Click to change</span>
                                </div>
                            </div>
                        </template>
                    </div>
                    <input 
                        ref="videoInput"
                        type="file" 
                        class="hidden" 
                        accept="video/*"
                        @change="handleFileChange"
                    />
                     <div v-if="form.errors.video" class="text-red-500 text-xs mt-2 ml-4">{{ form.errors.video }}</div>

                    <div class="mt-8 flex gap-4">
                        <button 
                            type="button"
                            class="flex-1 py-4 bg-gray-100 dark:bg-zinc-800 rounded-2xl font-bold hover:bg-gray-200 dark:hover:bg-zinc-750 transition-all text-sm uppercase tracking-tighter"
                        >
                            Draft
                        </button>
                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="flex-[2] py-4 bg-indigo-600 text-white rounded-2xl font-black hover:bg-indigo-700 transition-all text-sm uppercase tracking-wider shadow-xl shadow-indigo-600/30 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Uploading...' : 'Publish Video' }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- My Recent Videos / Dashboard -->
            <div class="mt-12">
                <h2 class="text-xl font-bold mb-6">My Recent Uploads</h2>
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Video</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Views</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                            <tr v-for="video in myVideos" :key="video.id" class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-sm">{{ video.title }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase" :class="video.status === 'ready' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'">
                                        {{ video.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ video.views }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ new Date(video.created_at).toLocaleDateString() }}</td>
                            </tr>
                            <tr v-if="myVideos.length === 0">
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">No videos uploaded yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-3 gap-6">
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-3xl border border-indigo-100 dark:border-indigo-800/30">
                    <h4 class="font-bold text-indigo-600 dark:text-indigo-400 mb-1">Visibility</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Published videos are visible to everyone.</p>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-3xl border border-indigo-100 dark:border-indigo-800/30">
                    <h4 class="font-bold text-indigo-600 dark:text-indigo-400 mb-1">Moderation</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Videos must follow community guidelines.</p>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-3xl border border-indigo-100 dark:border-indigo-800/30">
                    <h4 class="font-bold text-indigo-600 dark:text-indigo-400 mb-1">Quality</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">High quality videos get more recommendations.</p>
                </div>
            </div>
        </div>
    </div>
</template>
