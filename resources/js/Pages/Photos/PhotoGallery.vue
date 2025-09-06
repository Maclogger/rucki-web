<script setup lang="ts">
import { usePhotosStore } from '@/stores/photosStore';
import PhotoComponent from './PhotoComponent.vue';

const photoStore = usePhotosStore();

</script>

<template>

    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-3 gap-4 pr-4">
        <div v-for="(photo) in photoStore.photos">
            <PhotoComponent :photo="photo" />
        </div>
    </div>

</template>
