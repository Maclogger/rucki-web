<script setup lang="ts">
import { inject, Ref } from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import { usePhotosStore } from '@/stores/photosStore';
import { Photo } from '@/Classes/Photo';

const photo: Ref<Photo> = inject<Ref<Photo>>("photo")!;
const photoStore = usePhotosStore();


const handleClick = () => {
    console.log("Deleted");
    photoStore.deleteSinglePhoto(photo.value);
}

</script>

<template>
    <BottomRowButton icon="fa-solid fa-trash-can" :onClick="handleClick"
        class="bg-red-400 hover:bg-my-white hover:text-red-400" />
</template>
