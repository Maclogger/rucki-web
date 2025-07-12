<script setup lang="ts">

import GitHubRepository from "@/Pages/PublicDomain/GitHub/GitHubRepository.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
</script>

<template>
    <div class="w-full flex flex-col grow content-between gap-8">
        <div class="flex flex-row gap-8 grow">
            <GitHubRepository repository-link="https://github.com/Maclogger/rucki-web" repository-name="rucki-web">
                <template #stack-icons>
                    <font-awesome-icon class="text-3xl" :icon="['fab', 'laravel']" style="color: #ed333b;"/>
                    <img src="/images/icons/php.svg" class="h-[1.875em]" alt="php icon">
                    <font-awesome-icon class="text-3xl" :icon="['fab', 'vuejs']" style="color: #63E6BE;"/>
                    <img src="/images/icons/typescript.svg" class="h-[1.875em]" alt="typescript icon">
                    <img src="/images/icons/pinia.svg" class="h-[1.875em]" alt="piniastore icon">
                    <img src="/images/icons/tailwind.svg" class="h-[1.875em]" alt="tailwind icon">
                </template>
            </GitHubRepository>
            <GitHubRepository repository-link="https://github.com/Maclogger/kam-na-obedik" repository-name="kam-na-obedik">
                <template #stack-icons>
                    <img src="/images/icons/python.svg" class="h-[1.875em]" alt="python icon">

                    <img src="/images/icons/html5.svg" class="h-[1.875em]" alt="html5 icon">
                    <img src="/images/icons/css3.svg" class="h-[1.875em]" alt="css3 icon">

                    <font-awesome-icon class="text-3xl" :icon="['fab', 'github']"/>
                </template>
            </GitHubRepository>
        </div>
        <div class="flex flex-row gap-8 grow">
            <GitHubRepository repository-link="https://github.com/Maclogger/logitech-mx-master-setup"
                              repository-name="logitech-mx-master-setup">
                <template #stack-icons>
                    <img src="/images/icons/linux.svg" class="h-[1.875em]" alt="linux icon">
                    <img src="/images/icons/mxmaster.webp" class="h-[1.875em]" alt="Logitech MX Master 3s">
                </template>
            </GitHubRepository>
            <GitHubRepository repository-link="https://github.com/Maclogger/nvim_config" repository-name="nvim-config">
                <template #stack-icons>
                    <img src="/images/icons/linux.svg" class="h-[1.875em]" alt="linux icon">
                    <img src="/images/icons/neovim.svg" class="h-[1.875em]" alt="neovim icon">
                    <img src="/images/icons/lua.svg" class="h-[1.875em]" alt="lua icon">
                </template>
            </GitHubRepository>
        </div>
    </div>
</template>

<style scoped>

</style>
