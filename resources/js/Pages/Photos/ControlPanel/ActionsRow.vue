<script setup lang="ts">
import DeleteMultiplePhotosButton from './DeleteMultiplePhotosButton.vue';
import DownloadMultiplePhotosButton from './DownloadMultiplePhotosButton.vue';
import SelectionButton from './SelectionButton.vue';


</script>

<template>
    <div class="flex flex-row gap-6">
        <SelectionButton />
        <DownloadMultiplePhotosButton type="multiple" />
        <DownloadMultiplePhotosButton type="zip" />
        <DeleteMultiplePhotosButton />
    </div>
</template>
