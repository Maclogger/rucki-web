<script setup lang="ts">

import {useWebRecorderStore} from "@/stores/webRecorderStore";
import {useEventListener, useTimeoutPoll} from "@vueuse/core";
import {onMounted, onUnmounted} from "vue";
import {record} from "@rrweb/all";

const FLUSH_INTERVAL_MS = 5_000;

const webRecorderStore = useWebRecorderStore();

// useTimeoutPoll waits for the upload to finish before scheduling the next one, so two
// batches can never overlap and get stored out of order. It stops itself on unmount.
const {pause} = useTimeoutPoll(webRecorderStore.flush, FLUSH_INTERVAL_MS);

// record() returns a stop function - without it a remount would leave several recorders
// running at once, each numbering nodes from zero, which breaks the replay
let stopRecording: (() => void) | undefined;

onMounted(() => {
    stopRecording = record({
        emit(event) {
            webRecorderStore.pushEvent(event);
        }
    });
})

// Hiding the tab is usually the last chance to send whatever is left in the queue
useEventListener(document, 'visibilitychange', () => {
    if (document.visibilityState === 'hidden') {
        webRecorderStore.flush();
    }
});

onUnmounted(() => {
    stopRecording?.();
    pause();
    webRecorderStore.flush();
})

</script>

<template>

</template>
