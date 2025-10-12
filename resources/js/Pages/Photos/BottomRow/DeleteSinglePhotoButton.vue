<script setup lang="ts">
import { inject, Ref } from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import { useFilesStore } from '@/stores/filesStore';
import { File } from '@/Classes/File';

const file: Ref<File> = inject<Ref<File>>("file")!;
const filesStore = useFilesStore();


const handleClick = () => {
    filesStore.deleteSingleFile(file.value);
}

</script>

<template>
    <BottomRowButton icon="fa-solid fa-trash-can" :onClick="handleClick"
        class="bg-red-400 hover:bg-my-white hover:text-red-400" />
</template>
