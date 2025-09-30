<script setup lang="ts">
import { FetchStatus, useFilesStore } from '@/stores/filesStore';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { storeToRefs } from 'pinia';
import FilesGallery from "@/Pages/Photos/FilesGallery.vue";

const filesStore = useFilesStore();

const { files, status: filesStoreFetchStatus } = storeToRefs(filesStore);

const debugButtonPressed = () => {
    console.log("debugButtonPressed");
}

</script>

<template>
    <button class="btn btn-primary absolute top-0 left-0" @click="debugButtonPressed()">DEBUG</button>
    <div v-bind="$attrs" class="pr-4 w-full">
        <div v-if="filesStoreFetchStatus == FetchStatus.LOADING"
            class="bg-primary-dark-transparent rounded-xl h-full flex justify-center items-center">
            <span class="loading loading-spinner text-primary w-12"></span>
        </div>
        <div v-else>
            <FilesGallery v-if="files.length > 0" />
            <div v-else
                class="bg-primary-dark-transparent rounded-xl h-96 flex flex-col items-center justify-center gap-2">
                <font-awesome-icon icon="fa-solid fa-ban" class="text-3xl text-red-500" />
                <p class="p-0 m-0">Neboli nájdené žiadne súbory</p>
            </div>
        </div>
    </div>
</template>
