<script setup lang="ts">
import { usePhotosStore } from '@/stores/photosStore';
import ActionButton from './ActionButton.vue';
import { Photo } from '@/Classes/Photo';
import { useToastsStore, ToastSeverity } from '@/stores/toastsStore';
import JSZip from 'jszip';

const photoStore = usePhotosStore();
const toastStore = useToastsStore();

const props = defineProps<{
    type: "multiple" | "zip"
}>();

const getNiceSubTitle = (): string => {
    return props.type === "multiple" ? "Zvlášť" : "Ako ZIP";
}

/**
 * Vytvorí '<a>' element a programovo naň klikne, aby sa spustilo stiahnutie.
 * @param blob Dáta na stiahnutie.
 * @param filename Názov súboru.
 */
const downloadBlob = (blob: Blob, filename: string) => {
    const blobUrl = URL.createObjectURL(blob);
    const link = document.createElement('a');

    link.download = filename;
    link.href = blobUrl;

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Uvoľníme URL z pamäte
    URL.revokeObjectURL(blobUrl);
}

const handleMultipleDownload = async (selectedPhotos: Photo[]) => {
    for (const photo of selectedPhotos) {
        const [canvas, toast] = photo.createCanvasWithImage();
        if (toast) {
            toastStore.displayToast(toast);
            continue;
        }

        const blob = await new Promise<Blob | null>(resolve => {
            canvas!.toBlob(resolve, photo.mimeType);
        });

        if (blob) {
            downloadBlob(blob, photo.originalName);
        } else {
            toastStore.displayToast({
                message: `Nepodarilo sa vytvoriť Blob pre fotku: ${photo.originalName}`,
                severity: ToastSeverity.ERROR,
            });
        }
    }

    toastStore.displayToast({
        message: `${selectedPhotos.length} fotiek bolo stiahnutých.`,
        severity: ToastSeverity.SUCCESS
    });
}

/**
 * Zabalí všetky vybrané fotky do jedného ZIP archívu a stiahne ho.
 */
const handleZipDownload = async (selectedPhotos: Photo[]) => {
    const zip = new JSZip();

    for (const photo of selectedPhotos) {
        const [canvas, toast] = photo.createCanvasWithImage();
        if (toast) {
            toastStore.displayToast(toast);
            continue;
        }

        const blob = await new Promise<Blob | null>(resolve => {
            canvas!.toBlob(resolve, photo.mimeType);
        });

        if (blob) {
            // Pridáme súbor do zipu
            zip.file(photo.originalName, blob);
        }
    }

    const zipBlob = await zip.generateAsync({ type: "blob" });
    downloadBlob(zipBlob, "fotky.zip");

    toastStore.displayToast({
        message: `ZIP archív s ${selectedPhotos.length} fotkami bol stiahnutý.`,
        severity: ToastSeverity.SUCCESS
    });
}

const handleClick = async () => {
    const selectedPhotos: Photo[] = photoStore.getSelectedPhotos;

    if (selectedPhotos.length === 0) {
        toastStore.displayToast({
            message: "Nie sú vybraté žiadne fotky.",
            severity: ToastSeverity.WARNING,
        });
        return;
    }

    try {
        if (props.type === "multiple") {
            await handleMultipleDownload(selectedPhotos);
        } else {
            await handleZipDownload(selectedPhotos);
        }
    } catch (error) {
        toastStore.displayToast({
            message: "Vyskytla sa neočakávaná chyba pri sťahovaní.",
            severity: ToastSeverity.ERROR,
        });
    }
}

</script>

<template>
    <ActionButton icon="fa-solid fa-download" :sub-title="getNiceSubTitle()" :onClick="handleClick" />
</template>
