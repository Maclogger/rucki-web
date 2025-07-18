<script setup lang="ts">

import {reactive} from 'vue'
import {router} from '@inertiajs/vue3'

const form = reactive({
    login: null,
    password: null,
})

const submit = () => {
    router.post('/login', form);
    console.log("sent");
}


</script>

<template>
    <div class="flex flex-col justify-center align-middle items-center h-[100vh]">
        <div class="card card-md w-1/3 bg-primary-dark-transparent">
            <div class="card-body w-full">
                <form @submit.prevent="submit">
                    <h1 class="card-title text-[2em] mb-2">Admin Login</h1>
                    <label for="login">Login</label>
                    <input v-model="form.login" id="login" class="input w-full mb-2"/>

                    <label for="password">Password</label>
                    <input v-model="form.password" id="password" type="password" class="input w-full"/>

                    <button class="btn btn-primary mt-4 w-full" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
