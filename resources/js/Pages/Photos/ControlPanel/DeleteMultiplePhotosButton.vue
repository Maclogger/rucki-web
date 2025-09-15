<script setup lang="ts">
import { usePhotosStore } from '@/stores/photosStore';
import ActionButton from './ActionButton.vue';

const photoStore = usePhotosStore();


const handleClick = () => {
    photoStore.deleteSelectedPhotos();
}

</script>


<template>
    <ActionButton icon="fa-solid fa-trash-can" btn-class="btn-error" :onClick="handleClick" />
</template>
