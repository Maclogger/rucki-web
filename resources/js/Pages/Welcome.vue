<script setup lang="ts">
import {Head, Link, useForm} from '@inertiajs/vue3';
import GitHubSection from "@/Pages/PublicDomain/GitHub/GitHubSection.vue";

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();


const form = useForm({});

const submit = () => {
    form.post("/fetch-github-contributions");
};

</script>

<template>
    <form class="bg-gray" @submit.prevent="submit">
        <button class="bg-github-green w-auto text-white m-2 p-4 rounded">
            Fetch GitHub Data
        </button>
    </form>


    <GitHubSection/>

</template>
