<script setup lang="ts">
import { FetchStatus, usePhotosStore } from '@/stores/photosStore';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import PhotoComponent from './PhotoComponent.vue';
import { storeToRefs } from 'pinia';

const photoStore = usePhotosStore();

const { photos, status: photoStoreFetchStatus } = storeToRefs(photoStore);

</script>

<template>

    <div class="pr-4">
        <div v-if="photoStoreFetchStatus == FetchStatus.LOADING"
            class="bg-primary-dark-transparent rounded-xl h-full flex justify-center items-center">
            <span class="loading loading-spinner text-primary w-12"></span>
        </div>
        <div v-else>
            <div v-if="photos.length > 0" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-3 gap-4">
                <div v-for="(photo) in photos" :key="photo.file_name">
                    <PhotoComponent :photo="photo" />
                </div>
            </div>
            <div v-else
                class="bg-primary-dark-transparent rounded-xl h-96 flex flex-col items-center justify-center gap-2">
                <font-awesome-icon icon="fa-solid fa-ban" class="text-3xl text-red-500" />
                <p class="p-0 m-0">Neboli nájdené žiadne fotky</p>
            </div>
        </div>

    </div>

</template>
