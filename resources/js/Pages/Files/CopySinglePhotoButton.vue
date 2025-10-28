<script setup lang="ts">
import {computed, ref} from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {ToastSeverity, useToastsStore} from '@/stores/toastsStore';
import {Photo} from '@/Classes/Photo';

const toastStore = useToastsStore();

const props = defineProps<{
    photo: Photo
}>();


const COPIED_DELAY: number = 2_000; // in ms, time after the button is in COPIED state
enum CopyButtonState {
    ENABLED, // when the image is ready to be copied
    WRITING_TO_CLIPBOARD, // when the image is being loaded to clipboard
    COPIED, // after the image is copied succesfully, for COPIED_DELAY ms this state will be used
}

const status = ref<CopyButtonState>(CopyButtonState.ENABLED);

const writeBlobToClipboard = (blob: Blob | null) => {
    if (!blob) {
        toastStore.displayToast({
            message: `Nepodarilo sa previesť canvas na Blob.`,
            severity: ToastSeverity.ERROR,
        });
        return;
    }

    const item = new ClipboardItem({"image/png": blob});

    navigator.clipboard.write([item]).then(() => {
        toastStore.displayToast({
            message: "Obrázok bol skopírovaný do schránky.",
            severity: ToastSeverity.SUCCESS,
        });
        status.value = CopyButtonState.COPIED;
        setTimeout(() => {
            status.value = CopyButtonState.ENABLED;
        }, COPIED_DELAY);
    }).catch(err => {
        toastStore.displayToast({
            message: `Nepodarilo sa skopírovať obrázok do schránky: ${err.message}`,
            severity: ToastSeverity.ERROR,
        });
        status.value = CopyButtonState.ENABLED;
    });
}

const copyImageToClipboard = async () => {
    if (status.value == CopyButtonState.WRITING_TO_CLIPBOARD) return;
    status.value = CopyButtonState.WRITING_TO_CLIPBOARD;

    // Načítame originálnu fotku (nie thumbnail)
    const img = new Image();
    img.crossOrigin = "anonymous";

    img.onload = () => {
        const canvas = document.createElement('canvas');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        const ctx = canvas.getContext('2d')!;
        ctx.drawImage(img, 0, 0);
        canvas.toBlob(writeBlobToClipboard, 'image/png');
    };

    img.onerror = () => {
        toastStore.displayToast({
            message: 'Nepodarilo sa načítať obrázok.',
            severity: ToastSeverity.ERROR,
        });
        status.value = CopyButtonState.ENABLED;
    };

    img.src = props.photo.getFilePath();
}

const getColorClass = computed(() => {
    return status.value == CopyButtonState.COPIED ? "bg-success" : "bg-primary-dark-transparent";
});

</script>

<template>
    <BottomRowButton :onClick="copyImageToClipboard"
                     class="hover:bg-my-white hover:text-primary" :class="getColorClass">
        <span v-if="status == CopyButtonState.WRITING_TO_CLIPBOARD"
              class="loading loading-spinner loading-xs"></span>
        <font-awesome-icon v-else-if="status == CopyButtonState.COPIED" icon="fa-solid fa-check"/>
        <font-awesome-icon v-else icon="fa-solid fa-copy"/>
        Kopírovať
    </BottomRowButton>
</template>
