<script setup lang="ts">
import { PhotoType, SinglePhotoStatus, usePhotosStore } from '@/stores/photosStore';
import { inject, onMounted, Ref, ref } from 'vue';

const photo: PhotoType = inject("photo")!;

const imageRef: Ref<HTMLImageElement | null> = ref<HTMLImageElement | null>(null);

onMounted(() => {
    const imageElement = document.getElementById(photo.file_name) as HTMLImageElement;
    photo.status = SinglePhotoStatus.LOADING;
    imageElement.onload = () => {
        photo.status = SinglePhotoStatus.LOADED;
        photo.imgElement = imageRef;
    };
});


const getPhotoFilePath = () => {
    return `/photos-show/${photo.file_name}`;
}


</script>


<template>
    <div class="bg-orange-400 w-full h-full">
        <img ref="imageRef" :id="photo.file_name" :src="getPhotoFilePath()"
            class="w-full h-full object-center object-cover" />
    </div>
</template>
