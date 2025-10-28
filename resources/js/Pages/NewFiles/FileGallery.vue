<script setup lang="ts">


import {useFilesStore} from "@/stores/filesStore";
import {storeToRefs} from "pinia";
import GridLayout from "@/Layouts/GridLayout.vue";
import {Photo} from "@/Classes/Photo";
import FileIcon from "@/Pages/NewFiles/FileIcon.vue";

const filesStore = useFilesStore();

const { getVisibleFiles: visibleFiles } = storeToRefs(filesStore);
</script>

<template>
    <div class="bg-primary-dark-transparent p-4 rounded-xl">
        <GridLayout>
            <button v-for="file in visibleFiles" :key="file.id"
                class="btn flex flex-col w-full h-full aspect-square rounded-xl p-0 m-0 overflow-hidden">
                <!-- TOP ROW Selection -->
                <img v-if="file instanceof Photo" class="w-full h-full object-cover" :src="file.getFilePath()" alt="Photo">
                <FileIcon v-else :file="file" class="w-full h-full"/>
            </button>
        </GridLayout>
    </div>
</template>
