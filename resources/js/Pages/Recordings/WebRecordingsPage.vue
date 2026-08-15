<script setup lang="ts">

import {Paginated} from "@/types";
import {WrSession} from "@/Pages/Recordings/recordings.types";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import {InfiniteScroll} from "@inertiajs/vue3";
import WrSessionRow from "./WrSessionRow.vue";
import {computed, ref} from "vue";
import WebRecordingModal from "@/Pages/Recordings/WebRecordingModal.vue";
import {ToastSeverity, useToastsStore} from "@/stores/toastsStore";
import axios from "axios";

const props = defineProps<{
    sessions: Paginated<WrSession>
}>();

const selectedSession = ref<WrSession | null>(null);

// Rows arrive as an Inertia prop, so a deleted one is hidden locally instead of
// reloading the page - a reload would throw away everything InfiniteScroll appended
const deletedSessionIds = ref(new Set<string>());

const visibleSessions = computed(
    () => props.sessions.data.filter(s => !deletedSessionIds.value.has(s.id_session))
);

const closeModal = () => {
    selectedSession.value = null;
}

const openModal = (session: WrSession) => {
    selectedSession.value = session;
}

const deleteSession = async (session: WrSession) => {
    try {
        await axios.delete(`/web-recordings-delete-session/${session.id_session}`);

        if (selectedSession.value?.id_session === session.id_session) {
            closeModal();
        }
        deletedSessionIds.value.add(session.id_session);

        useToastsStore().displayToast({
            message: "Nahrávka bola zmazaná.",
            severity: ToastSeverity.SUCCESS,
        });
    } catch {
        useToastsStore().displayToast({
            message: "Nahrávku sa nepodarilo zmazať!",
            severity: ToastSeverity.ERROR,
        });
    }
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
                            <th>Zmazať</th>
                        </tr>
                        </thead>
                        <tbody>
                        <WrSessionRow v-for="s in visibleSessions" :session="s" :key="s.id_session"
                                      :onClick="() => {openModal(s)}" :onDelete="() => {deleteSession(s)}"/>
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
