<script setup lang="ts">
import {Head, Link, useForm} from '@inertiajs/vue3';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();



const form = useForm({

});

const submit = () => {
    form.post("/fetch-github-contributions");
};


</script>

<template>
    <form @submit.prevent="submit">
        <button>Spusti</button>
    </form>
</template>
