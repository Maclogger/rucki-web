<script setup lang="ts">
import { User, useUserStore } from '@/stores/userStore';
import GitHubFetchDataButton from "@/Pages/GitHubFetchDataButton.vue";
import SquareButton from "@/Pages/Home/SquareButton.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import GithubGraphComp from "@/Pages/PublicDomain/Github/GithubGraphComp.vue";
import { onMounted } from 'vue';
import { useGithubStore } from '@/stores/githubStore';

defineProps<{}>()


const userStore = useUserStore();
const gitHubStore = useGithubStore();

const user: User | null = userStore.getUser;

const onLogout = () => {
    if (!user) return;
    userStore.logout();
}

const formatName = () => {
    if (!user) return "Hacker";
    return user.username.charAt(0).toUpperCase() + user.username.slice(1);
}

onMounted(() => {
    gitHubStore.refresh();
});

</script>

<template>
    <div class="p-4 w-1/2 mx-auto">
        <div class="rounded shadow bg-primary-dark-transparent p-4 mb-4 flex flex-row justify-between items-center">
            <h1 class="text-2xl p-0 m-0 h-full">
                <span class="p-0 font-normal">Vitaj </span>
                <span class="p-0 font-bold">{{ formatName() }}</span>
                <span class="p-0 font-normal"> 😜!</span>
            </h1>
            <button @click="onLogout()" class="btn btn-secondary text-lg">
                Odhlásiť sa <font-awesome-icon icon="fa-solid fa-right-from-bracket" />
            </button>
        </div>

        <div class="grid grid-cols-6 place-items-center gap-y-4 gap-x-4">
            <SquareButton icon="fa-solid fa-image" :wip="false">Fotky</SquareButton>
            <GitHubFetchDataButton />
            <SquareButton :wip="true">WIP</SquareButton>
            <SquareButton :wip="true">WIP</SquareButton>
            <SquareButton :wip="true">WIP</SquareButton>
            <SquareButton :wip="true">WIP</SquareButton>
            <SquareButton :wip="true">WIP</SquareButton>
            <SquareButton :wip="true">WIP</SquareButton>
            <SquareButton :wip="true">WIP</SquareButton>
            <SquareButton :wip="true">WIP</SquareButton>
            <SquareButton :wip="true">WIP</SquareButton>
            <SquareButton :wip="true">WIP</SquareButton>
        </div>

        <div class="mt-6 flex flex-col">
            <div class="flex flex-col">
                <GithubGraphComp />
            </div>
        </div>

        <div v-if="user" class="mb-4">
        </div>
        <div v-else>
            <p>Nie si prihlásený.</p>
        </div>
    </div>

</template>
