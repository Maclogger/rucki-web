<script setup lang="ts">
import type {File} from "@/Classes/File";
import DownloadSinglePhotoButton from "@/Pages/Files/DownloadSinglePhotoButton.vue";
import CopySinglePhotoButton from "@/Pages/Files/CopySinglePhotoButton.vue";
import {Photo} from "@/Classes/Photo";
import DeleteSingleFileButtonButton from "@/Pages/Files/DeleteSingleFileButton.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps<{
    file: File
    onClose: () => void
}>();

</script>

<template>
    <div class="mt-4 flex flex-col gap-2">
        <div class="grid grid-cols-1 gap-2" :class="file instanceof Photo ? 'md:grid-cols-3' : 'md:grid-cols-2'">
            <DownloadSinglePhotoButton :file="file" :afterClick="() => {props.onClose();}" />
            <CopySinglePhotoButton v-if="file instanceof Photo" :photo="file"/>
            <DeleteSingleFileButtonButton :file="file" :afterClick="() => {props.onClose();}"/>
        </div>
        <button @click="() => {props.onClose();}"
                class="btn p-0 border-none bg-primary-dark-transparent hover:bg-my-white hover:text-primary hover">
            <font-awesome-icon icon="fa-solid fa-xmark" />
            Zatvoriť
        </button>
    </div>
</template>
