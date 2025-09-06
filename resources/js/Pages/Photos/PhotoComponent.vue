<script setup lang="ts">
import PhotoTopRow from './PhotoTopRow.vue';
import Photo from './Photo.vue';
import PhotoBottomRow from './BottomRow/PhotoBottomRow.vue';

</script>

<template>
    <div class="w-full h-48 rounded-xl flex flex-col overflow-hidden">
        <PhotoTopRow />
        <Photo />
        <PhotoBottomRow />
    </div>
</template>
