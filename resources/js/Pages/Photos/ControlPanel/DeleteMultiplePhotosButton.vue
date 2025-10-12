<script setup lang="ts">
import { useFilesStore } from '@/stores/filesStore';
import ActionButton from './ActionButton.vue';

const filesStore = useFilesStore();


const handleClick = () => {
    filesStore.deleteSelectedFiles();
}

</script>


<template>
    <ActionButton icon="fa-solid fa-trash-can" btn-class="btn-error" :onClick="handleClick" />
</template>
