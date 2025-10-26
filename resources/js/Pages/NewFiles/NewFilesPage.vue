<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import {storeToRefs} from "pinia";
import {useFilesStore} from "@/stores/filesStore";

const filesStore = useFilesStore();

const { getSelectedFiles: selectedFiles, areAllFilesSelected } = storeToRefs(filesStore);



</script>

<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Súbory</p>
        </template>
        <template #default>
        </template>
    </AuthLayout>
</template>
