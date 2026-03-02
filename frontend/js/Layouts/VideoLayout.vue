<script setup lang="ts">
import { Link, Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    filters?: {
        search?: string;
        category?: string;
        filter?: string;
    }
}>();

const searchTerm = ref(props.filters?.search || '');
const showSidebar = ref(true);

const search = () => {
    router.get(route('home'), { search: searchTerm.value }, { preserveState: true, replace: true });
};

watch(searchTerm, (val) => {
    if (val === '') search();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-900 dark:bg-black dark:text-gray-100 font-sans selection:bg-indigo-500 selection:text-white">
        <!-- Top Nav -->
        <nav class="sticky top-0 z-50 flex items-center justify-between px-6 py-4 bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-800 backdrop-blur-md bg-white/80 dark:bg-zinc-900/80">
            <div class="flex items-center gap-4 lg:gap-8">
                <button @click="showSidebar = !showSidebar" class="p-2 -ml-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <Link href="/" class="flex items-center gap-2 group">
                    <div class="size-10 bg-indigo-600 rounded-lg flex items-center justify-center transition-transform group-hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
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
                        placeholder="Search videos..."
                        class="w-full px-5 py-2.5 bg-gray-100 dark:bg-zinc-800 border-none rounded-full focus:ring-2 focus:ring-indigo-500 transition-all"
                    />
                    <button @click="search" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('videos.create')"
                    class="p-2.5 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 group relative"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect><line x1="9" y1="9" x2="9" y2="15"></line><line x1="6" y1="12" x2="12" y2="12"></line></svg>
                </Link>
                
                <template v-if="!$page.props.auth.user">
                    <Link :href="route('login')" class="font-semibold px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800">Log in</Link>
                    <Link :href="route('register')" class="bg-indigo-600 text-white font-semibold px-5 py-2.5 rounded-full hover:bg-indigo-700 shadow-lg shadow-indigo-600/20">Sign up</Link>
                </template>
                <Link v-else :href="route('profile.edit')" class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold border-2 border-transparent hover:border-indigo-500 transition-all">
                    {{ $page.props.auth.user.name[0] }}
                </Link>
            </div>
        </nav>

        <div class="flex">
            <!-- Sidebar -->
            <aside 
                v-show="showSidebar"
                class="fixed lg:sticky top-[73px] z-40 w-64 h-[calc(100vh-73px)] p-4 bg-white dark:bg-zinc-950 border-r border-gray-100 dark:border-zinc-900 transition-all"
            >
                <div class="space-y-1">
                    <Link :href="route('home')" class="flex items-center gap-5 px-3 py-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold" :class="{ 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/30': route().current('home') }">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        Home
                    </Link>
                    <Link href="#" class="flex items-center gap-5 px-3 py-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"></path><path d="m16 18-4 4-4-4"></path><path d="m16 6-4-4-4 4"></path></svg>
                        Trending
                    </Link>
                    <Link href="#" class="flex items-center gap-5 px-3 py-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                        Subscriptions
                    </Link>
                </div>
                
                <hr class="my-6 border-gray-100 dark:border-zinc-900" />
                
                <div class="px-4 mb-4"><h3 class="text-xs font-black uppercase text-gray-400">You</h3></div>
                <div class="space-y-1">
                    <Link href="#" class="flex items-center gap-5 px-3 py-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        History
                    </Link>
                    <Link href="#" class="flex items-center gap-5 px-3 py-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        Liked Videos
                    </Link>
                </div>

                 <hr class="my-6 border-gray-100 dark:border-zinc-900" />
                
                <div v-if="$page.props.auth.user" class="px-4 mb-4"><h3 class="text-xs font-black uppercase text-gray-400">Settings</h3></div>
                <div v-if="$page.props.auth.user" class="space-y-1">
                     <Link :href="route('channel.edit')" v-if="$page.props.auth.channel" class="flex items-center gap-5 px-3 py-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold" :class="{ 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/30': route().current('channel.edit') }">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        Channel Settings
                    </Link>
                    <Link :href="route('profile.edit')" class="flex items-center gap-5 px-3 py-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold" :class="{ 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/30': route().current('profile.edit') }">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Account Settings
                    </Link>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-8 overflow-hidden">
                <slot />
            </main>
        </div>
    </div>
</template>
