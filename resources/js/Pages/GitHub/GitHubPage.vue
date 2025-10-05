<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {router} from "@inertiajs/vue3";
import {ToastSeverity, useToastsStore} from "@/stores/toastsStore";
import GithubGraphComp from "@/Pages/PublicDomain/Github/GithubGraphComp.vue";


const toastStore = useToastsStore();

const updateGitHubRecords = () => {
    router.post("/fetch-github-contributions", {}, {
        onFinish: () => {
            toastStore.displayToast({
                message: "GitHub údaje boli úspešne aktualizované.",
                severity: ToastSeverity.SUCCESS,
            });
        },
    });
}


</script>

<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">GitHub</p>
        </template>
        <template #default>
            <div class="bg-primary-dark-transparent rounded-xl w-full p-4 h-full">
                <button class="btn btn-primary w-full text-xl p-8" @click="updateGitHubRecords">
                    <font-awesome-icon icon="fa-solid fa-rotate-right" class="text-lg"/>
                    Aktualizovať údaje
                </button>
            </div>
            <div class="mt-6 flex flex-col">
                <div class="flex flex-col">
                    <GithubGraphComp/>
                </div>
            </div>
        </template>
    </AuthLayout>
</template>

