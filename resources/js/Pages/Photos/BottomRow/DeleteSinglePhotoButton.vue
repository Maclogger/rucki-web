<script setup lang="ts">
import { inject } from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import { PhotoType, usePhotosStore } from '@/stores/photosStore';

const photo: PhotoType = inject("photo")!;
const photoStore = usePhotosStore();


const handleClick = () => {
    console.log("Deleted");
    photoStore.deleteSinglePhoto(photo);
}

</script>

<template>
    <BottomRowButton icon="fa-solid fa-trash-can" :onClick="handleClick"
        class="bg-red-400 hover:bg-my-white hover:text-red-400" />
</template>
