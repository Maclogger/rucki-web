<script setup lang="ts">
import { useFilesStore } from '@/stores/filesStore';
import { storeToRefs } from 'pinia';
import { computed, ref } from 'vue';

const filesStore = useFilesStore();

const { getSelectedFiles: selectedFiles, areAllFilesSelected } = storeToRefs(filesStore);

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
    return areAllFilesSelected.value ? "Odznačiť všetky" : "Vybrať všetky";
});


</script>

<template>
    <button class="btn h-24 aspect-square rounded-xl bg-secondary hover:bg-my-black" @mouseover="handleMouseOver"
        @mouseleave="handleMouseLeave()" @click="filesStore.toggleSelectAll()">
        <p v-if="showOverlay">
            {{ getTextOverlay }}
        </p>
        <p v-else class="p-0 m-0 text-3xl font-normal">{{ selectedFiles.length }}</p>
    </button>
</template>
