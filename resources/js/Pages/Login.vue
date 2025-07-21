<script setup lang="ts">

import {useForm} from '@inertiajs/vue3'
import ToastList from "@/Components/ToastList.vue";
import {ref} from "vue";
import {ToastSeverity, useToastsStore} from "@/stores/toastsStore";

const form = useForm({
    email: null,
    password: null,
    remember: true,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};

const toastListRef = ref<typeof ToastList | null>(null);

const toastsStore = useToastsStore();

const addToast = () => {
    toastsStore.displayToast({message: form.email, severity: ToastSeverity.ERROR})
}


</script>

<template>
    <div class="flex flex-col justify-center align-middle items-center h-[100vh]">
        <div class="card card-md w-1/3 bg-primary-dark-transparent">
            <div class="card-body w-full">
                <form @submit.prevent="submit">
                    <h1 class="card-title text-[2em] mb-2">Admin Login</h1>
                    <label for="email">Login</label>
                    <input v-model="form.email" id="email" class="input w-full mb-2"/>

                    <label for="password">Password</label>
                    <input v-model="form.password" id="password" type="password" class="input w-full"/>

                    <button class="btn btn-primary mt-4 w-full" type="submit">Login</button>
                </form>
            </div>
        </div>
        <button @click="addToast" class="btn btn-secondary">Pridaj toast</button>
    </div>
</template>

<style scoped>

</style>
