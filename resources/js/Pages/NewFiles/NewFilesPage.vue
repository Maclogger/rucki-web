<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import StickyHeader from "@/Pages/NewFiles/StickyHeader.vue";
import FileGallery from "@/Pages/NewFiles/FileGallery.vue";
import {onBeforeUnmount, onMounted, watch} from "vue";
import {useFilesStore} from "@/stores/filesStore";
import {storeToRefs} from "pinia";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {usePublicStore} from "@/stores/publicStore";

const filesStore = useFilesStore();
const publicStore = usePublicStore();

const {getVisibleFiles: visibleFiles} = storeToRefs(filesStore);

onMounted(() => {
    filesStore.fetchInitialData();
});


// After public store is loaded, get polling settings and start polling
watch(() => publicStore.isLoaded, (loaded) => {
    if (loaded) {
        const pollingPageSize = publicStore.getConstant("galleryPollingPageSize");
        filesStore.setPollingPageSize(pollingPageSize);
        const pollingIntervalSeconds = publicStore.getConstant("galleryPollingIntervalSeconds");
        filesStore.startPolling(pollingIntervalSeconds);
    }
}, { immediate: true }) // immediate is here because the public store might already be loaded


onBeforeUnmount(() => {
    filesStore.stopPolling();
});

</script>

<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Súbory</p>
        </template>
        <template #default>
            <StickyHeader class="mb-4"/>

            <FileGallery v-if="visibleFiles.length > 0" />
            <div v-else
                 class="bg-primary-dark-transparent rounded-xl h-96 flex flex-col items-center justify-center gap-2">
                <font-awesome-icon icon="fa-solid fa-ban" class="text-3xl text-red-500" />
                <p class="p-0 m-0">Nenašli sa žiadne záznamy.</p>
            </div>
        </template>
    </AuthLayout>
</template>
