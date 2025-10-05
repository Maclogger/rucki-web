<script setup lang="ts">
import SquareButton from "@/Pages/Home/SquareButton.vue";
import {onMounted} from 'vue';
import {useGithubStore} from '@/stores/githubStore';
import {router} from '@inertiajs/vue3';
import AuthLayout from "@/Layouts/AuthLayout.vue";

const gitHubStore = useGithubStore();

onMounted(() => {
    gitHubStore.refresh();
});

const handleFotkyClick = () => {
    router.get("/files");
}

const handleBufferClick = () => {
    router.get("/buffer-secured");
}

const handleGitHubClick = () => {
    router.get("/github-secured");
}


</script>

<template>
    <AuthLayout>
        <template #default>
            <div class="grid lg:grid-cols-6 md:grid-cols-4 grid-cols-2 place-items-center gap-y-4 gap-x-4">
                <SquareButton icon="fa-solid fa-image" :wip="false" :onClick="handleFotkyClick" label="Súbory"/>
                <SquareButton :wip="false" :onClick="handleGitHubClick" label="GitHub" icon="fa-brands fa-github"/>
                <SquareButton icon="fa-solid fa-file-arrow-up" :wip="false" label="Buffer" :onClick="handleBufferClick"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
            </div>
        </template>
    </AuthLayout>
</template>
