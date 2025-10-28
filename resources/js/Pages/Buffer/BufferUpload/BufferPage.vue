<script setup lang="ts">

import BufferComponent from "@/Pages/Buffer/BufferUpload/BufferComponent.vue";


const props = defineProps({
    code: String,
});

</script>

<template>
    <div class="h-[100vh] md:container md:mx-auto ">
        <BufferComponent :code="props.code"/>
    </div>
</template>

