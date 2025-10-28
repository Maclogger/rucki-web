<script setup lang="ts">

import {onMounted} from "vue";
import {useGithubStore} from "@/stores/githubStore";
import {usePublicStore} from "@/stores/publicStore";
import ToastLayout from "@/Layouts/ToastLayout.vue";


const gitHubStore = useGithubStore();
const publicStore = usePublicStore();

onMounted( () => {
    publicStore.refresh();
    gitHubStore.refresh();
});

</script>

<template>
    <ToastLayout>
        <slot/>
    </ToastLayout>
</template>
