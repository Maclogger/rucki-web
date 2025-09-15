<script setup lang="ts">
import { inject, Ref } from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import { Photo } from '@/Classes/Photo';
import { ToastSeverity, useToastsStore } from '@/stores/toastsStore';

const photo = inject<Ref<Photo>>("photo")!;
const toastStore = useToastsStore();

const downloadBlob = (blob: Blob | null) => {
    if (!blob) {
        toastStore.displayToast({
            message: "Image could not be downloaded.",
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

}

const handleClick = async () => {
    console.log("Downloading...");
    const [canvas, toast] = photo.value.createCanvasWithImage();
    if (toast) {
        toastStore.displayToast(toast);
        return;
    }

    canvas.toBlob(downloadBlob, photo.value.mimeType);
}


</script>

<template>
    <BottomRowButton icon="fa-solid fa-download" :onClick="handleClick"
        class="bg-primary hover:bg-my-white hover:text-primary" />
</template>
