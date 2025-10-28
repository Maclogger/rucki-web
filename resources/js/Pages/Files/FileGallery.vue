<script setup lang="ts">

import {useFilesStore} from "@/stores/filesStore";
import {storeToRefs} from "pinia";
import GridLayout from "@/Layouts/GridLayout.vue";
import {Photo} from "@/Classes/Photo";
import FileIcon from "@/Pages/Files/FileIcon.vue";
import {useTemplateRef} from "vue";
import {useInfiniteScroll} from "@vueuse/core";
import type {File} from "@/Classes/File";
import FileModal from "@/Pages/Files/FileModal.vue";

const filesStore = useFilesStore();

const {getVisibleFiles: visibleFiles} = storeToRefs(filesStore);

const el = useTemplateRef<HTMLElement>('el')
const modalRef = useTemplateRef<InstanceType<typeof FileModal>>('modalRef')

useInfiniteScroll(
    el,
    async () => {
        console.log("Loading more...");
        await filesStore.loadMoreFiles();
    },
    {
        distance: 5,
        canLoadMore: (): boolean => {
            return filesStore.hasMore;
        }
    }
);

const openModal = (file: File) => {
    modalRef.value?.open(file);
};

</script>

<template>
    <div ref="el" class="rounded-xl h-[75vh] overflow-y-scroll">
        <GridLayout>
            <button v-for="file in visibleFiles" :key="file.id"
                    @click="openModal(file)"
                    class="btn flex flex-col w-full h-full aspect-square rounded-xl p-0 m-0 overflow-hidden">
                <!-- Use thumbnail for gallery view -->
                <img v-if="file instanceof Photo" class="w-full h-full object-cover" :src="file.getThumbnailPath()" :alt="file.originalName"
                     loading="lazy">
                <FileIcon v-else :file="file" class="w-full h-full"/>
            </button>
        </GridLayout>
        <FileModal ref="modalRef"/>
    </div>
</template>
