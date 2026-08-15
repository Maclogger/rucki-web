<script setup lang="ts">

import {WrSession} from "@/Pages/Recordings/recordings.types";
import {toFormattedDate} from "@/utils/dateHelper";
import {watch} from "vue";
import axios from "axios";
import rrwebPlayer from 'rrweb-player';
import 'rrweb-player/dist/style.css';

const props = defineProps<{
    session: WrSession | null;
}>();

watch(
    () => props.session,
    async () => {
        if (!props.session) {
            return;
        }
        const fetchedRrWebEvents = await axios.get(`/web-recordings-fetch-events/${props.session.id_session}`);

        new rrwebPlayer({
            target: document.getElementById('rrweb-root') as HTMLElement,
            props: {
                events: fetchedRrWebEvents.data.events,
            },
        });
    }
)


</script>

<template>
    <dialog v-if="session" class="modal" id="rrweb-modal" open>
        <div class="modal-box w-10/12 max-w-5xl">
            <div class="modal-action m-0 p-0 flex flex-col">
                <div class="card">
                    <div class="card-title">
                        <div class="w-full flex justify-between align-middle">
                            <h2>Prehrávač</h2>
                            <form method="dialog">
                                <button class="btn">Zatvoriť</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body m-0 p-0">
                        <div class="flex">
                            <div class="flex flex-col gap-4 w-1/3">
                                <div class="">
                                    <p class="font-bold">Session</p>
                                    <p class="text-xs">{{ session.id_session }}</p>
                                </div>
                                <div class="">
                                    <p class="font-bold">Návštevník</p>
                                    <p class="text-xs">{{ session.id_visitor }}</p>
                                </div>
                                <div class="">
                                    <p class="font-bold">Počet záznamov</p>
                                    <p class="text-xs">{{ session.events_count }}</p>
                                </div>
                                <div class="">
                                    <p class="font-bold">Dátum vytvorenia</p>
                                    <p class="text-xs">{{ toFormattedDate(session.created_at, "dd.MM.yyyy HH:mm:ss") }}</p>
                                </div>
                            </div>
                            <div class="w-2/3 bg-black grow content-center">
                                <div id="rrweb-root"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </dialog>
</template>

<style scoped>

</style>
