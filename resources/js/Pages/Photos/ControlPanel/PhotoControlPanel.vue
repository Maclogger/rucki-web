<script setup lang="ts">
import { usePhotosStore } from '@/stores/photosStore';
import UploadPhotosButton from './UploadPhotosButton.vue';


const photoStore = usePhotosStore();

const handleRefreshClick = () => {
    photoStore.refresh();
}


</script>

<template>
    <div class="flex flex-col flex-1">
        <button class="btn btn-primary aspect-square" @click="handleRefreshClick">Refresh</button>
        <UploadPhotosButton />
    </div>
</template>
