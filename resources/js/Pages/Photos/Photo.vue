<script setup lang="ts">
import { PhotoType } from '@/stores/photosStore';
import { inject } from 'vue';

const photo: PhotoType = inject("photo")!;

const getPhotoFilePath = () => {
    return `/photos-show/${photo.file_name}`;
}

</script>


<template>
    <div class="bg-orange-400 w-full h-full">
        <img :src="getPhotoFilePath()" class="w-full h-full object-center object-cover" />
    </div>
</template>
