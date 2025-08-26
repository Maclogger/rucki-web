<script setup lang="ts">
import { User, useUserStore } from '@/stores/userStore';
import GitHubFetchDataButton from "@/Pages/GitHubFetchDataButton.vue";

defineProps<{}>()


const userStore = useUserStore();

const user: User | null = userStore.getUser;

const onLogout = () => {
    if (!user) return;
    userStore.logout();
}

</script>

<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Toto je HOME</h1>

        <div v-if="user" class="mb-4">
            <p>Vitaj, <span class="font-semibold">{{ user.username }}</span>!</p>
            <p>Tvoje ID je: {{ user.id }}</p>
            <GitHubFetchDataButton />
            <button @click="onLogout()" class="btn btn-primary">Logout</button>
        </div>
        <div v-else>
            <p>Nie si prihlásený.</p>
        </div>
    </div>

</template>
