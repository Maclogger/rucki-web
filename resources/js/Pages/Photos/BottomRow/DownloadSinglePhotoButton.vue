<script setup lang="ts">
import BottomRowButton from './BottomRowButton.vue';
import { BottomRowColor } from './BottomRowButton.vue';

const handleClick = () => {
    console.log("Downloading...");
}


</script>

<template>
    <BottomRowButton icon="fa-solid fa-download" :onClick="handleClick" :color="BottomRowColor.PRIMARY" />
</template>
