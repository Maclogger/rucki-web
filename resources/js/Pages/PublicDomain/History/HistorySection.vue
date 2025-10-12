<script setup lang="ts">

import Timeline from "@/Pages/PublicDomain/History/Timeline.vue";
</script>

<template>
    <div class="bg-gradient-to-br from-primary-dark to-primary-dark-transparent min-h-[100vh] md:min-h-auto skew-section pb-32">
        <div class="container mx-auto p-12 flex flex-col gap-12 pt-18">
            <p class="sm:text-4xl text-3xl text-start">História</p>
            <Timeline/>
        </div>
    </div>
</template>

<style scoped>
.skew-section {
    clip-path: polygon(0 0, 100% 9rem, 100% 100%, 0 calc(100% - 9rem));
}
</style>
