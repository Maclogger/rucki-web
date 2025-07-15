<script setup lang="ts">

import GithubLegendFakeTile from "@/Pages/PublicDomain/Github/GithubLegendFakeTile.vue";
</script>

<template>
    <div class="flex flex-col justify-between ms-2 py-2 align-middle items-center">
        <GithubLegendFakeTile/> <!-- First Row is Month Legend -->
        <GithubLegendFakeTile  text="Viac"/>
        <GithubLegendFakeTile  background-color-class="bg-primary-light-ultra"/>
        <GithubLegendFakeTile  background-color-class="bg-primary-light"/>
        <GithubLegendFakeTile  background-color-class="bg-primary"/>
        <GithubLegendFakeTile  background-color-class="bg-primary-dark"/>
        <GithubLegendFakeTile  background-color-class="bg-primary-dark-transparent"/>
        <GithubLegendFakeTile  text="Menej"/>
    </div>
</template>

<style scoped>

</style>
