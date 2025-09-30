<script setup lang="ts">
import UploadFilesButton from './UploadFilesButton.vue';
import RefreshRow from './RefreshRow.vue';
import ActionsRow from './ActionsRow.vue';


</script>

<template>
    <div class="flex flex-col flex-1 bg-primary-dark-transparent p-6 gap-6 rounded-xl">
        <RefreshRow />
        <ActionsRow />
        <UploadFilesButton />
    </div>
</template>
