<script setup lang="ts">
import { PhotoType } from '@/stores/photosStore';
import { toFormattedDate } from '@/utils/dateHelper';
import { inject, ref } from 'vue';

const myCheckBox = ref<HTMLInputElement | null>(null);

const toggleCheckBox = () => {
    if (myCheckBox && myCheckBox.value) {
        myCheckBox.value.checked = !myCheckBox.value.checked
    }
}

const photo: PhotoType | undefined = inject("photo");

const getFormattedDate = () => {
    if (!photo) return "";
    return toFormattedDate(photo.created_at, "dd.MM.yyyy HH:mm:ss")
}


</script>


<template>
    <div @click="toggleCheckBox"
        class="bg-primary-dark-transparent p-2 flex flex-row place-content-between hover:cursor-pointer">
        <p>{{ getFormattedDate() }}</p>
        <input @click="toggleCheckBox" ref="myCheckBox" type="checkbox" :checked="false"
            class="checkbox checkbox-secondary" />
    </div>
</template>
