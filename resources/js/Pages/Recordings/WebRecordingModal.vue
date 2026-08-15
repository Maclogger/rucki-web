<script setup lang="ts">

import {WrSession} from "@/Pages/Recordings/recordings.types";
import {toFormattedDate} from "@/utils/dateHelper";
import {ToastSeverity, useToastsStore} from "@/stores/toastsStore";
import {onBeforeUnmount, useTemplateRef, watch} from "vue";
import axios from "axios";
import rrwebPlayer from 'rrweb-player';
import 'rrweb-player/dist/style.css';

// rrweb-player si výšku ovládacieho panela pripočítava k `height` props-e
const CONTROLLER_HEIGHT = 80;

const props = defineProps<{
    session: WrSession | null;
}>();

const emit = defineEmits<{
    close: [];
}>();

const dialog = useTemplateRef<HTMLDialogElement>('dialog');
const stage = useTemplateRef<HTMLDivElement>('stage');

let player: rrwebPlayer | null = null;

watch(() => props.session, async (session) => {
    player?.pause();

    if (!session) {
        dialog.value?.close();
        return;
    }

    dialog.value?.showModal();

    try {
        const {data} = await axios.get(`/web-recordings-fetch-events/${session.id_session}`);
        const target = stage.value!;
        target.innerHTML = '';

        // Prehrávač má fixné inline rozmery v px, takže mu ich musíme dopočítať zo stage-u
        player = new rrwebPlayer({
            target,
            props: {
                events: data.events,
                width: target.clientWidth,
                height: target.clientHeight - CONTROLLER_HEIGHT,
                autoPlay: false,
            },
        });
    } catch {
        useToastsStore().displayToast({
            message: "Nahrávku sa nepodarilo načítať.",
            severity: ToastSeverity.ERROR,
        });
    }
});

onBeforeUnmount(() => player?.pause());

</script>

<template>
    <dialog ref="dialog" id="rrweb-modal" class="modal" @close="emit('close')">
        <div class="modal-box flex h-[92vh] max-h-none w-[95vw] max-w-none flex-col gap-4 p-4">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-bold">Prehrávač</h2>
                <form method="dialog">
                    <button class="btn btn-sm">Zatvoriť</button>
                </form>
            </div>

            <div v-if="session" class="flex flex-wrap gap-x-8 gap-y-2">
                <div>
                    <p class="font-bold">Session</p>
                    <p class="text-xs opacity-70">{{ session.id_session }}</p>
                </div>
                <div>
                    <p class="font-bold">Návštevník</p>
                    <p class="text-xs opacity-70">{{ session.id_visitor }}</p>
                </div>
                <div>
                    <p class="font-bold">Počet záznamov</p>
                    <p class="text-xs opacity-70">{{ session.events_count }}</p>
                </div>
                <div>
                    <p class="font-bold">Dátum vytvorenia</p>
                    <p class="text-xs opacity-70">{{ toFormattedDate(session.created_at, "dd.MM.yyyy HH:mm:ss") }}</p>
                </div>
            </div>

            <div ref="stage" class="flex min-h-0 grow items-center justify-center overflow-hidden rounded-box bg-black"/>
        </div>

        <form method="dialog" class="modal-backdrop">
            <button>Zatvoriť</button>
        </form>
    </dialog>
</template>

<style>

/* .rr-player je float:left s bielym pozadím a tieňom – v modale to nechceme */
#rrweb-modal .rr-player {
    float: none;
    background: transparent;
    box-shadow: none;
}

</style>
