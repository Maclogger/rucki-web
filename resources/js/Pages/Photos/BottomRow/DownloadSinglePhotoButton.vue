<script setup lang="ts">
import { computed, inject, ref, Ref } from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import { Photo, PhotoStatus } from '@/Classes/Photo';
import { ToastSeverity, useToastsStore } from '@/stores/toastsStore';

const DOWNLOADED_DELAY: number = 2_000; // in ms, time after the button is in COPIED state

const photo = inject<Ref<Photo>>("photo")!;
const toastStore = useToastsStore();
const isDownloaded = ref<boolean>(false);


const downloadBlob = (blob: Blob | null) => {
    if (!blob) {
        toastStore.displayToast({
            message: "Obrázok sa nepodarilo stiahnuť.",
            severity: ToastSeverity.ERROR,
        });
        return;
    }
    const blobUrl = URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.download = photo.value.originalName;
    link.href = blobUrl;

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    toastStore.displayToast({
        message: "Fotka bola stiahnutá.",
        severity: ToastSeverity.SUCCESS
    });
    isDownloaded.value = true;
    setTimeout(() => {
        isDownloaded.value = false;
    }, DOWNLOADED_DELAY);
}

const handleClick = async () => {
    const [canvas, toast] = photo.value.createCanvasWithImage();
    if (toast) {
        toastStore.displayToast(toast);
        return;
    }

    canvas.toBlob(downloadBlob, photo.value.mimeType);
}

const getIcon = computed(() => {
    return "fa-solid " + (isDownloaded.value ? "fa-check" : "fa-download");
});

const getColorClass = computed(() => {
    return isDownloaded.value ? "bg-success" : "bg-primary";
});

</script>

<template>
    <BottomRowButton :icon="getIcon" :onClick="handleClick" :disabled="photo.status == PhotoStatus.LOADING"
        class="hover:bg-my-white hover:text-primary" :class="getColorClass"></BottomRowButton>
</template>
