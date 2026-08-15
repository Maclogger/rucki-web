<script setup lang="ts">

import {Paginated} from "@/types";
import {WrSession} from "@/Pages/Recordings/recordings.types";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import {InfiniteScroll} from "@inertiajs/vue3";
import WrSessionRow from "./WrSessionRow.vue";
import {computed, ref} from "vue";
import WebRecordingModal from "@/Pages/Recordings/WebRecordingModal.vue";

defineProps<{
    sessions: Paginated<WrSession>
}>();

const selectedSession = ref<WrSession | null>(null);

const closeModal = () => {
    selectedSession.value = null;
}

const openModal = (session: WrSession) => {
    selectedSession.value = session;
}


</script>

<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Nahrávky</p>
        </template>
        <template #default>
            <WebRecordingModal :session="selectedSession" @close="closeModal"/>
            <InfiniteScroll data="sessions">
                <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
                    <table class="table w-full">
                        <thead>
                        <tr>
                            <th>Session</th>
                            <th>Návštevník</th>
                            <th>Počet udalostí</th>
                            <th>Dátum vytvorenia</th>
                            <th>Záznam</th>
                        </tr>
                        </thead>
                        <tbody>
                        <WrSessionRow v-for="s in sessions.data" :session="s" :key="s.id_session" :onClick="() => {openModal(s)}"/>
                        </tbody>
                    </table>
                </div>

                <template #loading>
                    <span class="loading loading-spinner"/>
                </template>
            </InfiniteScroll>
        </template>
    </AuthLayout>
</template>

<style scoped>

</style>
