<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('channel.store'));
};
</script>

<template>
    <Head title="Create Your Channel" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-black text-gray-900 dark:text-gray-100 flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-2xl border border-gray-100 dark:border-zinc-800">
            <div class="text-center mb-8">
                <div class="size-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-6 shadow-xl shadow-indigo-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h1 class="text-2xl font-black mb-2">Build Your Identity</h1>
                <p class="text-gray-500 text-sm">Create a channel to start uploading videos and building your community.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2 tracking-widest pl-4">Channel Name</label>
                    <input 
                        v-model="form.name"
                        type="text"
                        placeholder="e.g. My Awesome Channel"
                        class="w-full bg-gray-50 dark:bg-zinc-800 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-600 transition-all font-bold"
                        required
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-xs mt-1 ml-4">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2 tracking-widest pl-4">Description</label>
                    <textarea 
                        v-model="form.description"
                        placeholder="What is your channel about?"
                        rows="4"
                        class="w-full bg-gray-50 dark:bg-zinc-800 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-600 transition-all resize-none"
                    ></textarea>
                    <div v-if="form.errors.description" class="text-red-500 text-xs mt-1 ml-4">{{ form.errors.description }}</div>
                </div>

                <div class="pt-4">
                    <button 
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/30 disabled:opacity-50"
                    >
                         {{ form.processing ? 'Creating...' : 'Launch Channel' }}
                    </button>
                    
                    <Link href="/" class="block text-center mt-6 text-sm text-gray-500 hover:text-indigo-600 font-bold">Later</Link>
                </div>
            </form>
        </div>
    </div>
</template>
