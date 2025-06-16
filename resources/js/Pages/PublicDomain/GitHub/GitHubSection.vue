<script setup lang="ts">

import GitHubGraph from "@/Pages/PublicDomain/GitHub/GitHubGraph.vue";
import {useGitHubStore} from "@/stores/githubStore";
import {toNicelyFormattedDateAndTime} from "@/utils/dateHelper";
import {storeToRefs} from "pinia";

const gitHubStore = useGitHubStore();

const { last_update } = storeToRefs(gitHubStore);

</script>

<template>
    <div class="bg-black h-screen">
        <p>Last update: {{ toNicelyFormattedDateAndTime(lastUpdate) }}</p>
        <GitHubGraph/>
    </div>
</template>


<style scoped>

</style>
