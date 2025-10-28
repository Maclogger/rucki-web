<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import StickyHeader from "@/Pages/NewFiles/StickyHeader.vue";
import FileGallery from "@/Pages/NewFiles/FileGallery.vue";
import {onMounted} from "vue";
import {useFilesStore} from "@/stores/filesStore";
import {storeToRefs} from "pinia";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const fileStore = useFilesStore();

const {getVisibleFiles: visibleFiles} = storeToRefs(fileStore);

onMounted(() => {
    fileStore.refresh();
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
