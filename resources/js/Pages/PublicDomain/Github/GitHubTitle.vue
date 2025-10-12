<script setup lang="ts">

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

</script>

<template>
    <div class="flex flex-col gap-4 md:text-left items-center md:items-start text-center">
        <p class="text-3xl sm:text-4xl lg:text-4xl font-semibold">GitHub</p>
        <p class="">Zaujíma Vás, či viem písať čitateľný kód, ktorý sa dá ľahko pochopiť?</p>
        <p class="">Posúďte sami na mojom GitHub profile</p>
        <a class="btn btn-primary mt-4 py-6 w-min" href="https://github.com/Maclogger" target="_blank">
            <font-awesome-icon class="text-2xl" :icon="['fas', 'square-up-right']"/>
            <span class="ms-2 me-1"> https://github.com/Maclogger</span>
        </a>
    </div>
</template>

<style scoped>

</style>
