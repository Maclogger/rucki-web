<script setup lang="ts">
import { inject } from 'vue';
import BottomRowButton from './BottomRowButton.vue';

const handleClick = () => {
    console.log("Downloading...");
}

const photo = inject("photo");



</script>

<template>
    <BottomRowButton icon="fa-solid fa-download" :onClick="handleClick"
        class="bg-primary hover:bg-my-white hover:text-primary" />
</template>
