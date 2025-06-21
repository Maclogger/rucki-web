<script setup lang="ts">

import GitHubGraph from "@/Pages/PublicDomain/GitHub/GitHubGraph.vue";
import {useGitHubStore} from "@/stores/githubStore";
import {toNicelyFormattedDateAndTime} from "@/utils/dateHelper";
import {storeToRefs} from "pinia";

const gitHubStore = useGitHubStore();

const { last_update } = storeToRefs(gitHubStore);

</script>

<template>
    <div class="bg-my-black h-screen">




    </div>
</template>


<!--
<p>Last update: {{ toNicelyFormattedDateAndTime(last_update) }}</p>
<GitHubGraph/>
-->
<style scoped>

</style>
