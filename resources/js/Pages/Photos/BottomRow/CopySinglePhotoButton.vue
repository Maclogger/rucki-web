<script setup lang="ts">
import BottomRowButton from './BottomRowButton.vue';
import { BottomRowColor } from './BottomRowButton.vue';

const handleClick = () => {
    console.log("Copied");
}

</script>

<template>
    <BottomRowButton icon="fa-solid fa-copy" :onClick="handleClick" :color="BottomRowColor.PRIMARY_DARK" />
</template>
