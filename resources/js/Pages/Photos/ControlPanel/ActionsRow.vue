<script setup lang="ts">
import { usePhotosStore } from '@/stores/photosStore';
import { computed } from 'vue';
import ActionButton from "./ActionButton.vue";
import DownloadMultiplePhotosButton from './DownloadMultiplePhotosButton.vue';


const photoStore = usePhotosStore();

const selectedCount = computed(() => {
    return photoStore.getSelectedCount;
});

</script>

<template>
    <div class="flex flex-row gap-6">
        <div class="bg-secondary rounded-xl text-3xl aspect-square h-24 flex items-center justify-center">
            <p class="m-0 p-0">{{ selectedCount }}</p>
        </div>
        <ActionButton icon="fa-solid fa-download" sub-title="ZIP" />
        <ActionButton icon="fa-solid fa-download" sub-title="Multiple" />
        <DownloadMultiplePhotosButton />
    </div>
</template>
