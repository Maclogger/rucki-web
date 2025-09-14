<script setup lang="ts">
import PhotoTopRow from './PhotoTopRow.vue';
import PhotoImage from './PhotoImage.vue';
import PhotoBottomRow from './BottomRow/PhotoBottomRow.vue';
import { Photo } from '@/Classes/Photo';
import { provide, toRef } from 'vue';

const props = defineProps<{
    photo: Photo
}>();

provide('photo', toRef(props, 'photo'));

</script>

<template>
    <div class="w-full h-72 rounded-xl flex flex-col overflow-hidden">
        <PhotoTopRow />
        <PhotoImage />
        <PhotoBottomRow />
    </div>
</template>
