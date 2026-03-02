<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    channel: {
        id: number;
        name: string;
        slug: string;
        description: string | null;
        avatar_path: string | null;
        banner_path: string | null;
    }
}>();

const avatarInput = ref<HTMLInputElement | null>(null);
const bannerInput = ref<HTMLInputElement | null>(null);
const avatarPreview = ref<string | null>(props.channel.avatar_path);
const bannerPreview = ref<string | null>(props.channel.banner_path);

const form = useForm({
    name: props.channel.name,
    description: props.channel.description || '',
    avatar: null as File | null,
    banner: null as File | null,
});

const handleAvatarChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.avatar = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
};

const handleBannerChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.banner = file;
        bannerPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('channel.update'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Channel Settings" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-black text-gray-900 dark:text-gray-100 p-4 md:p-12 font-sans">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <Link href="/" class="flex items-center gap-2 group">
                    <div class="size-8 bg-black dark:bg-zinc-800 rounded flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </div>
                </Link>
                <h1 class="text-3xl font-black">Customize Channel</h1>
                <div class="w-8"></div>
            </div>

            <form @submit.prevent="submit" class="space-y-12">
                <!-- Branding Section -->
                <div class="bg-white dark:bg-zinc-900 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-zinc-800">
                    <h2 class="text-xl font-bold mb-8 flex items-center gap-2">
                        <span class="size-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white text-sm">1</span>
                        Branding
                    </h2>

                    <div class="space-y-10">
                        <!-- Banner -->
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-500 mb-4 tracking-widest">Channel Banner</label>
                            <div 
                                class="w-full aspect-[4/1] rounded-2xl border-2 border-dashed border-gray-200 dark:border-zinc-800 overflow-hidden relative group cursor-pointer"
                                @click="bannerInput?.click()"
                            >
                                <img v-if="bannerPreview" :src="bannerPreview" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full bg-gray-50 dark:bg-zinc-800/50 flex flex-col items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    <span class="mt-2 text-sm font-bold">Upload Banner (2048 x 1152)</span>
                                </div>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white font-black uppercase tracking-tighter">Change Banner</span>
                                </div>
                            </div>
                            <input ref="bannerInput" type="file" @change="handleBannerChange" class="hidden" accept="image/*" />
                             <div v-if="form.errors.banner" class="text-red-500 text-xs mt-2 ml-4">{{ form.errors.banner }}</div>
                        </div>

                        <!-- Avatar -->
                        <div class="flex flex-col md:flex-row gap-8 items-center">
                            <div 
                                class="size-32 rounded-full border-4 border-white dark:border-zinc-800 overflow-hidden relative group cursor-pointer shrink-0 shadow-lg"
                                @click="avatarInput?.click()"
                            >
                                <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-3xl font-black italic">
                                    {{ channel.name[0] }}
                                </div>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                                </div>
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <h3 class="font-black text-lg">Profile Picture</h3>
                                <p class="text-gray-500 text-sm mt-1">Your avatar will appear next to your videos and comments.</p>
                                <button type="button" @click="avatarInput?.click()" class="mt-4 text-indigo-600 font-bold hover:underline text-sm uppercase tracking-wider">Change Photo</button>
                            </div>
                            <input ref="avatarInput" type="file" @change="handleAvatarChange" class="hidden" accept="image/*" />
                             <div v-if="form.errors.avatar" class="text-red-500 text-xs mt-2 ml-4">{{ form.errors.avatar }}</div>
                        </div>
                    </div>
                </div>

                <!-- Basic Info Section -->
                <div class="bg-white dark:bg-zinc-900 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-zinc-800">
                    <h2 class="text-xl font-bold mb-8 flex items-center gap-2">
                        <span class="size-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white text-sm">2</span>
                        Basic Info
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-500 mb-2 tracking-widest pl-4">Channel Name</label>
                            <input 
                                v-model="form.name"
                                type="text"
                                class="w-full bg-gray-50 dark:bg-zinc-800 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-600 transition-all font-bold"
                            />
                             <div v-if="form.errors.name" class="text-red-500 text-xs mt-1 ml-4">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase text-gray-500 mb-2 tracking-widest pl-4">Description</label>
                            <textarea 
                                v-model="form.description"
                                rows="5"
                                class="w-full bg-gray-50 dark:bg-zinc-800 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-600 transition-all resize-none"
                                placeholder="Tell viewers about your channel..."
                            ></textarea>
                             <div v-if="form.errors.description" class="text-red-500 text-xs mt-1 ml-4">{{ form.errors.description }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="flex justify-end gap-4">
                    <Link :href="route('channel.show', channel.slug)" class="px-8 py-4 bg-gray-200 dark:bg-zinc-800 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-300 transition-all">Cancel</Link>
                    <button 
                        type="submit"
                        :disabled="form.processing"
                        class="px-12 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/30 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
