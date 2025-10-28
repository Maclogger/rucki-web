<script setup lang="ts">


import {useFilesStore} from "@/stores/filesStore";
import {storeToRefs} from "pinia";

const filesStore = useFilesStore();

const { getVisibleFiles: visibleFiles } = storeToRefs(filesStore);
</script>

<template>
    <div class="bg-primary-dark-transparent p-4">
        <div class="fotka w-full bg-red-100 h-40">
        </div>
    </div>
</template>
