<script setup lang="ts">
import { useFilesStore } from '@/stores/filesStore';
import ActionButton from './ActionButton.vue';
import { File } from '@/Classes/File';
import { useToastsStore, ToastSeverity } from '@/stores/toastsStore';
import JSZip from 'jszip';
import { Photo } from '@/Classes/Photo';

const filesStore = useFilesStore();
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

const handleMultipleDownload = async (selectedFiles: File[]) => {
    for (const file of selectedFiles) {
        if (file instanceof Photo) {
            const photo = file as Photo;
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
    }

    toastStore.displayToast({
        message: `${selectedFiles.length} súborov bolo stiahnutých.`,
        severity: ToastSeverity.SUCCESS
    });
}

/**
 * Zabalí všetky vybrané fotky do jedného ZIP archívu a stiahne ho.
 */
const handleZipDownload = async (selectedFiles: File[]) => {
    const zip = new JSZip();

    for (const file of selectedFiles) {
        const photo = file as Photo;
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
    downloadBlob(zipBlob, "súbory.zip");

    toastStore.displayToast({
        message: `ZIP archív s ${selectedFiles.length} súbormi bol stiahnutý.`,
        severity: ToastSeverity.SUCCESS
    });
}

const handleClick = async () => {
    const selectedFiles: File[] = filesStore.getSelectedFiles;

    if (selectedFiles.length === 0) {
        toastStore.displayToast({
            message: "Nie sú vybraté žiadne súbory.",
            severity: ToastSeverity.WARNING,
        });
        return;
    }

    try {
        if (props.type === "multiple") {
            await handleMultipleDownload(selectedFiles);
        } else {
            await handleZipDownload(selectedFiles);
        }
    } catch (error) {
        toastStore.displayToast({
            message: "Pri sťahovaní súborov sa vyskytla neočakávaná chyba.",
            severity: ToastSeverity.ERROR,
        });
    }
}

</script>

<template>
    <ActionButton icon="fa-solid fa-download" :sub-title="getNiceSubTitle()" :onClick="handleClick" />
</template>
