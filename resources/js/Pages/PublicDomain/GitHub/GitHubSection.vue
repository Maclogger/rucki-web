<script setup lang="ts">

import GitHubGraph from "@/Pages/PublicDomain/GitHub/GitHubGraph.vue";
import {useGitHubStore} from "@/stores/githubStore";
import {toNicelyFormattedDateAndTime} from "@/utils/dateHelper";
import {storeToRefs} from "pinia";

const gitHubStore = useGitHubStore();

const { lastUpdate } = storeToRefs(gitHubStore); // <-- ZMENA TU

</script>

<template>
    <p>Last update: {{ toNicelyFormattedDateAndTime(lastUpdate) }}</p>
    <GitHubGraph/>
</template>

<style scoped>

</style>
