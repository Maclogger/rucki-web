<script setup lang="ts">
import { PhotoStoreState, usePhotosStore } from '@/stores/photosStore';
import PhotoComponent from './PhotoComponent.vue';
import { storeToRefs } from 'pinia';

const photoStore = usePhotosStore();

const { photos, state: photoStoreState } = storeToRefs(photoStore);

</script>

<template>

    <div class="pr-4">
        <div v-if="photoStoreState == PhotoStoreState.LOADING"
            class="bg-primary-dark-transparent rounded-xl h-full flex justify-center items-center">
            <span class="loading loading-spinner text-primary w-12"></span>
        </div>
        <div v-else class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-3 gap-4">
            <div v-for="(photo) in photos">
                <PhotoComponent :photo="photo" />
            </div>
        </div>
    </div>

</template>
