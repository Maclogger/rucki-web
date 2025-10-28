<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import StickyHeader from "@/Pages/NewFiles/StickyHeader.vue";
import FileGallery from "@/Pages/NewFiles/FileGallery.vue";
import {onMounted} from "vue";
import {useFilesStore} from "@/stores/filesStore";

const fileStore = useFilesStore();

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
            <StickyHeader/>
            <FileGallery/>
        </template>
    </AuthLayout>
</template>
