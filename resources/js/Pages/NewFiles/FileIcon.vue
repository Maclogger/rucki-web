<script setup lang="ts">

import {computed} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import type { File } from "@/Classes/File";

const props = defineProps<{
    file: File
}>();

const TRUNCATE_START = 18;

const displayName = computed(() => {
    const name = props.file.originalName || 'unknown';
    if (name.length > 30) {
        return name.slice(0, TRUNCATE_START) + '...';
    }
    return name;
});

const fileExtension = computed(() => {
    const name = props.file.originalName || '';
    const parts = name.split('.');
    if (parts.length > 1) {
        return parts.pop()!.toUpperCase();
    }
    return '';
});

</script>

<template>
    <div class="h-full w-full flex flex-col items-center justify-center p-2 bg-[#3E435D] gap-4">
        <div class="flex flex-col gap-1">
            <font-awesome-icon icon="fa-regular fa-file" class="text-6xl "/>
            <div class="badge badge-primary text-lg p-3">{{ fileExtension }}</div>
        </div>
        <p class="text-center break-words" >{{ displayName }}</p>
    </div>
</template>
