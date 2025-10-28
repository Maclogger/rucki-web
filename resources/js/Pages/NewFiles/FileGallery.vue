<script setup lang="ts">

import {useFilesStore} from "@/stores/filesStore";
import {storeToRefs} from "pinia";
import GridLayout from "@/Layouts/GridLayout.vue";
import {Photo} from "@/Classes/Photo";
import FileIcon from "@/Pages/NewFiles/FileIcon.vue";
import {onBeforeUnmount, useTemplateRef} from "vue";
import {useInfiniteScroll} from "@vueuse/core";
import {usePublicStore} from "@/stores/publicStore";
import {watch} from "vue";

const filesStore = useFilesStore();
const publicStore = usePublicStore();

const {getVisibleFiles: visibleFiles} = storeToRefs(filesStore);

const el = useTemplateRef<HTMLElement>('el')

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

watch(() => publicStore.isLoaded, (loaded) => {
    if (loaded) {
        const pollingPageSize = publicStore.getConstant("galleryPollingPageSize");
        filesStore.setPollingPageSize(pollingPageSize);
        const pollingIntervalSeconds = publicStore.getConstant("galleryPollingIntervalSeconds");
        filesStore.startPolling(pollingIntervalSeconds);
    }
}, { immediate: true })

onBeforeUnmount(() => {
    // Stop polling when component is unmounted
    filesStore.stopPolling();
});


</script>

<template>
    <div ref="el" class="bg-primary-dark-transparent p-4 rounded-xl h-[75vh] overflow-y-scroll">
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
