<script setup lang="ts">
import FileTopRow from './FileTopRow.vue';
import PhotoImage from './PhotoImage.vue';
import FileBottomRow from './BottomRow/PhotoBottomRow.vue';
import { File } from '@/Classes/File';
import { provide, toRef } from 'vue';
import FileIcon from "@/Pages/Photos/FileIcon.vue";
import {Photo} from "@/Classes/Photo";

const props = defineProps<{
    file: File
}>();

provide('file', toRef(props.file));

</script>

<template>
    <div class="w-full h-72 rounded-xl flex flex-col overflow-hidden">
        <FileTopRow />
        <PhotoImage v-if="props.file instanceof Photo" />
        <FileIcon v-else/>
        <FileBottomRow />
    </div>
</template>
