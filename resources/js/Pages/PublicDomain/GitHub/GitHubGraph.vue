<script setup lang="ts">

import {useGitHubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";

const gitHubStore = useGitHubStore();

const { gitHubRecords } = storeToRefs(gitHubStore);

</script>

<template>

    <div class="m-5 bg-red-100 h-24 w-48">
    </div>





</template>

<style scoped>

</style>
