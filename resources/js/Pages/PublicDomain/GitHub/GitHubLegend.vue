<script setup lang="ts">

import GitHubLegendTile from "@/Pages/PublicDomain/GitHub/GitHubLegendTile.vue";
</script>

<template>
    <div class="flex flex-col justify-between ms-2 py-1 align-middle items-center">
        <p class="text-sm">Viac</p>
        <GitHubLegendTile  background-color-class="bg-primary-light-ultra"/>
        <GitHubLegendTile  background-color-class="bg-primary-light"/>
        <GitHubLegendTile  background-color-class="bg-primary"/>
        <GitHubLegendTile  background-color-class="bg-primary-dark"/>
        <GitHubLegendTile  background-color-class="bg-primary-dark-transparent"/>
        <p class="text-sm">Menej</p>
    </div>
</template>

<style scoped>

</style>
