<script setup lang="ts">


import {useWebRecorderStore} from "@/stores/webRecorderStore";
import {onMounted, onUnmounted} from "vue";
import {record} from "@rrweb/all";


onMounted(() => {
    const webRecorderStore = useWebRecorderStore();
    webRecorderStore.init();

    record({
        emit(event) {
            webRecorderStore.pushEvent(event)
        }
    });
})

onUnmounted(async () => {
    await useWebRecorderStore().finishRecording();
})


</script>

<template>

</template>
