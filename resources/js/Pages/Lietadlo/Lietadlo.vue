<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3'
import GameCanvas from "@/Pages/Lietadlo/GameCanvas.vue";

defineProps<{}>()
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Lietadlo"/>

        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Lietadlo
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">

                        <GameCanvas/>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
