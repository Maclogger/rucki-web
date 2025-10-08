<script setup lang="ts">

import {usePublicStore} from "@/stores/publicStore";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const publicStore = usePublicStore();

const redirect = (url: string) => {
    window.location.replace(url);
}

</script>

<template>
    <div class="flex flex-col">
        <p class="md:text-start text-3xl sm:text-4xl lg:text-4xl font-semibold">
            {{ publicStore.getFullName() }}
        </p>
        <p class=""> Pozrite si moju tvorbu priamo na GitHube </p>
        <button class="btn btn-primary mt-4 py-6 w-min" @click="redirect('https://github.com/Maclogger')">
            <font-awesome-icon class="text-2xl" :icon="['fas', 'square-up-right']"/>
            <span class="ms-2 me-1"> https://github.com/Maclogger</span>
        </button>
    </div>
</template>

<style scoped>

</style>
