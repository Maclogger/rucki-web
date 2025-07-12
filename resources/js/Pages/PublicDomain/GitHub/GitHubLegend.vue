<script setup lang="ts">

import GitHubLegendFakeTile from "@/Pages/PublicDomain/GitHub/GitHubLegendFakeTile.vue";
</script>

<template>
    <div class="flex flex-col justify-between ms-2 py-2 align-middle items-center">
        <GitHubLegendFakeTile/> <!-- First Row is Month Legend -->
        <GitHubLegendFakeTile  text="Viac"/>
        <GitHubLegendFakeTile  background-color-class="bg-primary-light-ultra"/>
        <GitHubLegendFakeTile  background-color-class="bg-primary-light"/>
        <GitHubLegendFakeTile  background-color-class="bg-primary"/>
        <GitHubLegendFakeTile  background-color-class="bg-primary-dark"/>
        <GitHubLegendFakeTile  background-color-class="bg-primary-dark-transparent"/>
        <GitHubLegendFakeTile  text="Menej"/>
    </div>
</template>

<style scoped>

</style>
