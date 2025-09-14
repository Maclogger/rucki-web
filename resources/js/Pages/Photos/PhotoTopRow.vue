<script setup lang="ts">
import { Photo } from '@/Classes/Photo';
import { toFormattedDate } from '@/utils/dateHelper';
import { inject } from 'vue';

const photo: Photo = inject("photo")!;

const getFormattedDate = () => {
    return toFormattedDate(photo.created_at, "dd.MM.yyyy HH:mm:ss");
};
</script>


<template>
    <div @click="photo.toggleSelection()"
        class="bg-primary-dark-transparent p-2 flex flex-row place-content-between hover:cursor-pointer">
        <p>{{ getFormattedDate() }}</p>
        <input type="checkbox" :checked="photo.selected" class="checkbox checkbox-secondary" />
    </div>
</template>
