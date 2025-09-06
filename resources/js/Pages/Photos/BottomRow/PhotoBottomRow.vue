<script setup lang="ts">
import DownloadSinglePhotoButton from "./DownloadSinglePhotoButton.vue";
import CopySinglePhotoButton from "./CopySinglePhotoButton.vue";
import DeleteSinglePhotoButton from "./DeleteSinglePhotoButton.vue";
</script>


<template>
    <div class="flex flex-row">
        <DownloadSinglePhotoButton />
        <CopySinglePhotoButton />
        <DeleteSinglePhotoButton />
    </div>
</template>
