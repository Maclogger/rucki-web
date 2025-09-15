<script setup lang="ts">
import { usePhotosStore } from '@/stores/photosStore';
import { storeToRefs } from 'pinia';
import { computed, ref } from 'vue';

const photoStore = usePhotosStore();

const { getSelectedPhotos: selectedPhotos, areAllPhotosSelected } = storeToRefs(photoStore);

let isHovered = ref<boolean>(false);
let hoverTimeout: number | null = null;
let showOverlay = ref<boolean>(false);

const handleMouseOver = () => {
    isHovered.value = true;
    if (hoverTimeout) {
        clearTimeout(hoverTimeout);
    }
    hoverTimeout = setTimeout(() => {
        showOverlay.value = true;
    }, 30); // 200ms delay
};

const handleMouseLeave = () => {
    isHovered.value = false;
    if (hoverTimeout) {
        clearTimeout(hoverTimeout);
    }
    showOverlay.value = false;
};

const getTextOverlay = computed(() => {
    return areAllPhotosSelected.value ? "Odznačiť všetky" : "Vybrať všetky";
});


</script>

<template>
    <button class="btn h-24 aspect-square rounded-xl bg-secondary hover:bg-my-black" @mouseover="handleMouseOver"
        @mouseleave="handleMouseLeave()" @click="photoStore.toggleSelectAll()">
        <p v-if="showOverlay">
            {{ getTextOverlay }}
        </p>
        <p v-else class="p-0 m-0 text-3xl font-normal">{{ selectedPhotos.length }}</p>
    </button>
</template>
