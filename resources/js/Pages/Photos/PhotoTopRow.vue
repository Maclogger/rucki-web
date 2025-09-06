<script setup lang="ts">
import { PhotoType } from '@/stores/photosStore';
import { toFormattedDate } from '@/utils/dateHelper';
import { inject } from 'vue';

const photo: PhotoType | undefined = inject("photo")!;

const toggleSelected = () => {
    photo.selected = !photo.selected;
};

const getFormattedDate = () => {
    return toFormattedDate(photo.created_at, "dd.MM.yyyy HH:mm:ss");
};
</script>


<template>
    <div @click="toggleSelected"
        class="bg-primary-dark-transparent p-2 flex flex-row place-content-between hover:cursor-pointer">
        <p>{{ getFormattedDate() }}</p>
        <input type="checkbox" :checked="photo.selected" class="checkbox checkbox-secondary" />
    </div>
</template>
