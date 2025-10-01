<script setup lang="ts">
import {useFilesStore} from '@/stores/filesStore';
import ActionButton from './ActionButton.vue';
import {File} from '@/Classes/File';
import {useToastsStore, ToastSeverity} from '@/stores/toastsStore';

const filesStore = useFilesStore();
const toastStore = useToastsStore();

const props = defineProps<{
    type: "multiple" | "zip"
}>();

const getNiceSubTitle = (): string => {
    return props.type === "multiple" ? "Zvlášť" : "Ako ZIP";
}

const handleMultipleDownload = async (selectedFiles: File[]) => {
    await File.downloadMultiple(selectedFiles);

    toastStore.displayToast({
        message: `${selectedFiles.length} súborov bolo stiahnutých.`,
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
            File.downloadZip(selectedFiles);
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
    <ActionButton icon="fa-solid fa-download" :sub-title="getNiceSubTitle()" :onClick="handleClick"/>
</template>
