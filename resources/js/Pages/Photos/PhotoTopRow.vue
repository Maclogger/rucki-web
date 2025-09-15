<script setup lang="ts">
import { Photo } from '@/Classes/Photo';
import { toFormattedDate } from '@/utils/dateHelper';
import { inject, Ref } from 'vue';

const photo = inject<Ref<Photo>>("photo")!;

const getFormattedDate = () => {
    return toFormattedDate(photo.value.createdAt, "dd.MM.yyyy HH:mm:ss");
};
</script>


<template>
    <div @click="photo.toggleSelection()"
        class="bg-primary-dark-transparent p-2 flex flex-row place-content-between hover:cursor-pointer">
        <p>{{ getFormattedDate() }}</p>
        <input type="checkbox" :checked="photo.selected" class="checkbox checkbox-secondary" />
    </div>
</template>
