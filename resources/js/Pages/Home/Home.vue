<script setup lang="ts">
import GitHubFetchDataButton from "@/Pages/GitHubFetchDataButton.vue";
import SquareButton from "@/Pages/Home/SquareButton.vue";
import GithubGraphComp from "@/Pages/PublicDomain/Github/GithubGraphComp.vue";
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


</script>

<template>
    <AuthLayout>
        <template #default>
            <div class="grid grid-cols-6 place-items-center gap-y-4 gap-x-4">
                <SquareButton icon="fa-solid fa-image" :wip="false" :onClick="handleFotkyClick" label="Súbory"/>
                <GitHubFetchDataButton/>
                <SquareButton icon="fa-solid fa-file-arrow-up" :wip="false" label="Buffer" :onClick="handleBufferClick"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
                <SquareButton :wip="true" label="WIP"/>
            </div>

            <div class="mt-6 flex flex-col">
                <div class="flex flex-col">
                    <GithubGraphComp/>
                </div>
            </div>
        </template>
    </AuthLayout>
</template>
