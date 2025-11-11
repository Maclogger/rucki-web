<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

interface Props {
    questionIndex?: number | null;
    content: string;
}

defineProps<Props>();
</script>

<template>
    <Head title="QR Code" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 px-4 py-8">
        <div class="max-w-4xl w-full">
            <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12">
                <div class="text-center mb-6">
                    <h1 class="text-3xl md:text-5xl font-bold text-gray-800 mb-4">
                        <span v-if="questionIndex">Question #{{ questionIndex }} 🎯</span>
                        <span v-else>🎯 QR Code Content</span>
                    </h1>
                    <div class="h-1 w-24 bg-gradient-to-r from-blue-500 to-indigo-600 mx-auto rounded-full"></div>
                </div>

                <div class="mt-8 p-6 md:p-10 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl border-2 border-indigo-200">
                    <p class="text-xl md:text-3xl lg:text-4xl text-gray-800 text-center leading-relaxed whitespace-pre-wrap break-words">
                        {{ content }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Emoji support */
p {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
}
</style>

