<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import DownloadSinglePhotoButton from "./DownloadSinglePhotoButton.vue";
import CopySinglePhotoButton from "./CopySinglePhotoButton.vue";
</script>


<template>
    <div class="flex flex-row">
        <DownloadSinglePhotoButton />
        <CopySinglePhotoButton />
        <button class="btn bg-red-400 hover:bg-my-white hover:text-red-400 w-1/3 rounded-none border-none">
            <font-awesome-icon icon="fa-solid fa-trash-can" />
        </button>
    </div>
</template>
