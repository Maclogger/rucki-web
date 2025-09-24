<script setup lang="ts">
import { computed, inject, ref, Ref } from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import { File } from '@/Classes/File';
import { ToastSeverity, useToastsStore } from '@/stores/toastsStore';
import { Photo, PhotoStatus } from '@/Classes/Photo';

const DOWNLOADED_DELAY: number = 2_000; // in ms, time after the button is in COPIED state

const file = inject<Ref<File>>("file")!;
const toastStore = useToastsStore();
const isDownloaded = ref<boolean>(false);


const downloadBlob = (blob: Blob | null) => {
    if (!blob) {
        toastStore.displayToast({
            message: "Súbor sa nepodarilo stiahnuť.",
            severity: ToastSeverity.ERROR,
        });
        return;
    }
    const blobUrl = URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.download = file.value.originalName;
    link.href = blobUrl;

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    toastStore.displayToast({
        message: "Súbor bol stiahnutý.",
        severity: ToastSeverity.SUCCESS
    });
    isDownloaded.value = true;
    setTimeout(() => {
        isDownloaded.value = false;
    }, DOWNLOADED_DELAY);
}

const handleClick = async () => {
    if (file.value instanceof Photo) {
        const [canvas, toast] = file.value.createCanvasWithImage();
        if (toast) {
            toastStore.displayToast(toast);
            return;
        }

        canvas.toBlob(downloadBlob, file.value.mimeType);
    }
}

const getIcon = computed(() => {
    return "fa-solid " + (isDownloaded.value ? "fa-check" : "fa-download");
});

const getColorClass = computed(() => {
    return isDownloaded.value ? "bg-success" : "bg-primary";
});

</script>

<template>
    <div v-if="file instanceof Photo">
        <BottomRowButton :icon="getIcon" :onClick="handleClick" :disabled="file.status == PhotoStatus.LOADING"
            class="hover:bg-my-white hover:text-primary" :class="getColorClass" />
    </div>
    <div v-else>
        <BottomRowButton :icon="getIcon" :onClick="handleClick" class="hover:bg-my-white hover:text-primary"
            :class="getColorClass" />
    </div>
</template>
