<script setup lang="ts">
import BottomRowButton from './BottomRowButton.vue';
import { useFilesStore } from '@/stores/filesStore';
import { File } from '@/Classes/File';

const filesStore = useFilesStore();

const props = defineProps<{
    file: File,
    afterClick?: () => void,
}>();

const handleClick = () => {
    filesStore.deleteSingleFile(props.file);
    if (props.afterClick) {
        props.afterClick();
    }
}

</script>

<template>
    <BottomRowButton icon="fa-solid fa-trash-can" :onClick="handleClick"
                     class="bg-red-400 hover:bg-my-white hover:text-red-400" >Zmazať</BottomRowButton>
</template>
