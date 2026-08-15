<script setup lang="ts">

import {Replayer} from "@rrweb/all";
import {onMounted, ref} from "vue";
import {Paginated} from "@/types";
import {WrSession} from "@/Pages/Recordings/recordings.types";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import {InfiniteScroll} from "@inertiajs/vue3";

const replayer = ref<Replayer | null>(null);

const props = defineProps<{
    sessions: Paginated<WrSession>
}>();

onMounted(() => {
    // const events = props.sessions.data;
    // replayer.value = new Replayer({})
});




</script>

<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Nahrávky</p>
        </template>
        <template #default>
            <InfiniteScroll data="sessions" class="flex flex-wrap gap-4">
                <div v-for="s in sessions.data" :key="s.id_session" class="card bg-base-200 p-4">
                    {{ s.id_session }} — {{ s.events_count }} udalostí
                </div>

                <template #loading>
                    <span class="loading loading-spinner" />
                </template>
            </InfiniteScroll>
        </template>
    </AuthLayout>
</template>

<style scoped>

</style>
