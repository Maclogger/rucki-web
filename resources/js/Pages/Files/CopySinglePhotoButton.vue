<script setup lang="ts">
import {computed, ref} from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {ToastSeverity, useToastsStore} from '@/stores/toastsStore';
import {Photo} from '@/Classes/Photo';

const toastStore = useToastsStore();

const props = defineProps<{
    photo: Photo
    afterClick?: () => void,
}>();


enum CopyButtonState {
    ENABLED,
    LOADING,
}

const status = ref<CopyButtonState>(CopyButtonState.ENABLED);

const writeBlobToClipboard = (blob: Blob | null) => {
    if (!blob) {
        toastStore.displayToast({
            message: `Nepodarilo sa previesť canvas na Blob.`,
            severity: ToastSeverity.ERROR,
        });
        status.value = CopyButtonState.ENABLED;
        return;
    }

    const item = new ClipboardItem({"image/png": blob});

    navigator.clipboard.write([item]).then(() => {
        toastStore.displayToast({
            message: "Obrázok bol skopírovaný do schránky.",
            severity: ToastSeverity.SUCCESS,
        });
        status.value = CopyButtonState.ENABLED;
        props.afterClick?.();
    }).catch(err => {
        toastStore.displayToast({
            message: `Nepodarilo sa skopírovať obrázok do schránky: ${err.message}`,
            severity: ToastSeverity.ERROR,
        });
        status.value = CopyButtonState.ENABLED;
    });
}

const copyImageToClipboard = async () => {
    if (status.value == CopyButtonState.LOADING) return;
    status.value = CopyButtonState.LOADING;

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
    return "bg-primary-dark-transparent";
});

</script>

<template>
    <BottomRowButton :onClick="copyImageToClipboard"
                     class="hover:bg-my-white hover:text-primary" :class="getColorClass">
        <span v-if="status == CopyButtonState.LOADING"
              class="loading loading-spinner loading-xs"></span>
        <font-awesome-icon v-else icon="fa-solid fa-copy"/>
        Kopírovať
    </BottomRowButton>
</template>
