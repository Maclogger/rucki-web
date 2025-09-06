<script setup lang="ts">
import BottomRowButton from './BottomRowButton.vue';

const handleClick = () => {
    console.log("Copied");
}

</script>

<template>
    <BottomRowButton icon="fa-solid fa-trash-can" :onClick="handleClick"
        class="bg-red-400 hover:bg-my-white hover:text-red-400" />
</template>
