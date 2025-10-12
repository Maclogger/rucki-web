<script setup lang="ts">
import {User, useUserStore} from '@/stores/userStore';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {computed, ComputedRef, ref} from 'vue';
import {router} from '@inertiajs/vue3';
import {EmojiHelper} from "@/Classes/EmojiHelper";

defineProps<{}>()

const userStore = useUserStore();

const user: ComputedRef<User | null> = computed(() => {
    return userStore.getUser;
});

const onLogout = () => {
    if (!user) return;
    userStore.logout();
}

const formatName = () => {
    if (!user.value) return "Hacker";
    return user.value.username.charAt(0).toUpperCase() + user.value.username.slice(1);
}

const emoji = ref(EmojiHelper.getRandomEmoji());

</script>

<template>
    <div v-if="user">
        <div class="p-4 container mx-auto">
            <div
                class="rounded-xl shadow bg-primary-dark-transparent p-4 mb-4 flex flex-row justify-between items-center">
                <div class="flex flex-row gap-3">
                    <div class="h-full">
                        <button class="btn btn-primary" @click="router.get('/home');">
                            <font-awesome-icon icon="fa-solid fa-house text-2xl"/>
                        </button>
                    </div>
                    <div class="flex items-center" v-if="$slots.headline">
                        <slot name="headline"/>
                    </div>
                    <div v-else>
                        <h1 class="text-2xl p-0 m-0 h-full flex flex-row flex-nowrap items-center whitespace-nowrap">
                            <span class="p-0 font-normal">Vitaj&nbsp;</span>
                            <span class="p-0 font-bold">{{ formatName() }}</span>
                            <span class="p-0 ml-1 font-normal mr-1">{{ emoji }}!</span>
                        </h1>
                    </div>
                </div>
                <button @click="onLogout()" class="btn btn-secondary">
                    <span class="hidden sm:inline">Odhlásiť sa</span>
                    <font-awesome-icon icon="fa-solid fa-right-from-bracket"/>
                </button>
            </div>
            <slot/>
        </div>
    </div>
    <div v-else>
        <h1>Nie si prihlásený!</h1>
    </div>
</template>

<style scoped></style>
