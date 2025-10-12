<script setup lang="ts">

import GithubGraphComp from "@/Pages/PublicDomain/Github/GithubGraphComp.vue";
import GitHubTopRepositories from "@/Pages/PublicDomain/Github/GitHubTopRepositories.vue";
import GitHubTitle from "@/Pages/PublicDomain/Github/GitHubTitle.vue";

</script>

<template>
    <div class="container flex flex-col gap-8 mx-auto justify-center">
        <div
            class="flex flex-wrap md:gap-12 md:flex-nowrap md:content-between px-4 justify-center md:justify-start">
            <GitHubTitle class="mb-8 md:my-0"/>
            <GithubGraphComp class="overflow-hidden"/>
        </div>

        <p class="text-2xl pl-4">Moje ⭐ repozitáre</p>
        <div class="flex md:gap-16 md:flex-nowrap flex-wrap">
            <GitHubTopRepositories/>
        </div>
    </div>
</template>

<style scoped></style>
