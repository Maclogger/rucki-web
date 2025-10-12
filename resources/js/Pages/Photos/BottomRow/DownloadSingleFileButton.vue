<script setup lang="ts">
import {computed, inject, ref, Ref} from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import {File} from '@/Classes/File';
import {Photo, PhotoStatus} from '@/Classes/Photo';

const file = inject<Ref<File>>("file")!;
const isDownloaded = ref<boolean>(false);


const handleClick = async () => {
    file.value.download();
}

const getIcon = computed(() => {
    return "fa-solid " + (isDownloaded.value ? "fa-check" : "fa-download");
});

const getColorClass = computed(() => {
    return isDownloaded.value ? "bg-success" : "bg-primary";
});

</script>

<template>
    <BottomRowButton v-if="file instanceof Photo" :icon="getIcon" :onClick="handleClick" :disabled="file.status == PhotoStatus.LOADING"
                     class="hover:bg-my-white hover:text-primary" :class="getColorClass"/>
    <BottomRowButton v-else :icon="getIcon" :onClick="handleClick" class="hover:bg-my-white hover:text-primary"
                     :class="getColorClass"/>
</template>
