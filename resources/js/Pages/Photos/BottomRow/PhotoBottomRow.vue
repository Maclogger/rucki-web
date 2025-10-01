<script setup lang="ts">
import DownloadSingleFileButton from "./DownloadSingleFileButton.vue";
import CopySinglePhotoButton from "./CopySinglePhotoButton.vue";
import DeleteSinglePhotoButton from "./DeleteSinglePhotoButton.vue";
</script>


<template>
    <div class="flex flex-row">
        <DownloadSingleFileButton />
        <CopySinglePhotoButton />
        <DeleteSinglePhotoButton />
    </div>
</template>
