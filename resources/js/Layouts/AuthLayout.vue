<script setup lang="ts">
import { User, useUserStore } from '@/stores/userStore';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

defineProps<{}>()

const userStore = useUserStore();

const user: User | null = userStore.getUser;

const onLogout = () => {
    if (!user) return;
    userStore.logout();
}

const formatName = () => {
    if (!user) return "Hacker";
    return user.username.charAt(0).toUpperCase() + user.username.slice(1);
}

</script>

<template>
    <div v-if="user">
        <div class="p-4 container mx-auto">
            <div
                class="rounded-xl shadow bg-primary-dark-transparent p-4 mb-4 flex flex-row justify-between items-center">
                <div v-if="$slots.headline">
                    <slot name="headline" />
                </div>
                <div v-else>
                    <h1 class="text-2xl p-0 m-0 h-full">
                        <span class="p-0 font-normal">Vitaj </span>
                        <span class="p-0 font-bold">{{ formatName() }}</span>
                        <span class="p-0 font-normal"> 😜!</span>
                    </h1>
                </div>
                <button @click="onLogout()" class="btn btn-secondary text-lg">
                    Odhlásiť sa <font-awesome-icon icon="fa-solid fa-right-from-bracket" />
                </button>
            </div>
            <slot />
        </div>
    </div>
    <div v-else>
        <h1>Nie si prihlásený!</h1>
    </div>
</template>

<style scoped></style>
