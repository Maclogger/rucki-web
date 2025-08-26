<script setup lang="ts">
import { User, useUserStore } from '@/stores/userStore';

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
            <!-- Ak si pridal aj iné dáta, môžeš ich zobraziť tu -->
        </div>
        <div v-else>
            <p>Nie si prihlásený.</p>
        </div>

        <button @click="onLogout()" class="btn btn-primary">Logout</button>
    </div>

</template>
