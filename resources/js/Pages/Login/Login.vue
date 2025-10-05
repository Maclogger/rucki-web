<script setup lang="ts">

import { useForm } from '@inertiajs/vue3'

const form = useForm({
    username: null,
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

</script>

<template>
    <div class="flex flex-col justify-center align-middle items-center h-[100vh]">
        <div class="card card-md xl:w-1/4 lg:w-1/3 md:w-2/3 bg-primary-dark-transparent">
            <div class="card-body w-full">
                <form @submit.prevent="submit">
                    <h1 class="card-title text-[2em] mb-2">Prihlásenie</h1>
                    <label for="username">Meno</label>
                    <input v-model="form.username" id="username" class="input w-full mb-2" />

                    <label for="password">Heslo</label>
                    <input v-model="form.password" id="password" type="password" class="input w-full" />

                    <button class="btn btn-primary mt-4 w-full" type="submit">Prihlásiť sa</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
