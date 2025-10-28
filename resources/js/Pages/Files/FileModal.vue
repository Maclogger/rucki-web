<script setup lang="ts">
import {ref, useTemplateRef} from "vue";
import type {File} from "@/Classes/File";
import {Photo} from "@/Classes/Photo";
import FileIcon from "@/Pages/Files/FileIcon.vue";
import FileActionButtons from "@/Pages/Files/FileActionButtons.vue";

const modalRef = useTemplateRef<HTMLDialogElement>('modalRef')
const selectedFile = ref<File | null>(null)

const open = (file: File) => {
    selectedFile.value = file;
    modalRef.value?.showModal();
}

const close = () => {
    modalRef.value?.close();
    selectedFile.value = null;
}


defineExpose({
    open,
    close,
});

</script>

<template>
    <dialog ref="modalRef" class="modal">
        <div class="modal-box w-11/12 max-w-7xl h-[90vh]">
            <div v-if="selectedFile" class="flex flex-col h-full">
                <h3 class="text-lg font-bold mb-4">{{ selectedFile.originalName }}</h3>

                <div class="flex-1 overflow-auto flex items-center justify-center">
                    <img v-if="selectedFile instanceof Photo"
                         class="max-w-full max-h-full object-contain"
                         :src="selectedFile.getFilePath()"
                         :alt="selectedFile.originalName">
                    <FileIcon v-else :file="selectedFile" class="w-64 h-64"/>
                </div>
                <FileActionButtons :file="selectedFile" :onClose="close"/>
            </div>
        </div>
    </dialog>
</template>
