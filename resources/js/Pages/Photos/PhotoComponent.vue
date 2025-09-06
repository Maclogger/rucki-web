<script setup lang="ts">
import PhotoTopRow from './PhotoTopRow.vue';
import Photo from './Photo.vue';
import PhotoBottomRow from './BottomRow/PhotoBottomRow.vue';
import { PhotoType } from '@/stores/photosStore';
import { provide } from 'vue';

const props = defineProps<{
    photo: PhotoType
}>();

provide('photo', props.photo);

</script>

<template>
    <div class="w-full h-72 rounded-xl flex flex-col overflow-hidden">
        <PhotoTopRow />
        <Photo />
        <PhotoBottomRow />
    </div>
</template>
