<script setup lang="ts">

import {useGitHubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";

import GitHubGraph from "@/Pages/PublicDomain/GitHub/GitHubGraph.vue";
import {toNicelyFormattedDateAndTime} from "@/utils/dateHelper";
import GitHubGraphYears from "@/Pages/PublicDomain/GitHub/GitHubGraphYears.vue";
import GitHubLegend from "@/Pages/PublicDomain/GitHub/GitHubLegend.vue";

const gitHubStore = useGitHubStore();
const {last_update} = storeToRefs(gitHubStore);

</script>

<template>
    <p class="text-xl"><strong>307</strong> príspevkov v roku <strong>2025</strong></p>
    <div class="flex flex-row">
        <GitHubGraph/>
        <GitHubLegend/>
    </div>
    <GitHubGraphYears/>
<!--
    <button class="btn btn-neutral">Neutral</button>
    <button class="btn btn-primary">Primary</button>
    <button class="btn btn-secondary">Secondary</button>
    <button class="btn btn-accent">Accent</button>
    <button class="btn btn-info">Info</button>
    <button class="btn btn-success">Success</button>
    <button class="btn btn-warning">Warning</button>
    <button class="btn btn-error">Error</button>
-->
    <p>Aktualizované: <strong>{{ toNicelyFormattedDateAndTime(last_update) }}</strong></p>
    <p class="text-xl mt-5">Moje <strong>TOP</strong> repozitáre, na ktoré sa oplatí pozrieť.</p>


</template>

<style scoped>

</style>
