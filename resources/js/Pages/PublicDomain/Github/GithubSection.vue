<script setup lang="ts">

import GithubGraphComp from "@/Pages/PublicDomain/Github/GithubGraphComp.vue";
import GitHubTopRepositories from "@/Pages/PublicDomain/Github/GitHubTopRepositories.vue";
import GitHubTitle from "@/Pages/PublicDomain/Github/GitHubTitle.vue";


</script>

<template>

    <div class="container h-screen flex flex-col gap-8 mx-auto justify-center">
        <div
            class="flex flex-wrap md:gap-12 md:flex-nowrap md:content-between p-4 justify-center md:justify-start">
            <GitHubTitle class="mb-8 md:my-0"/>
            <GithubGraphComp class="overflow-hidden"/>
        </div>

        <p class="text-2xl pl-4">Moje ⭐ repozitáre</p>
        <div class="flex md:gap-16 md:flex-nowrap flex-wrap">
            <GitHubTopRepositories/>
        </div>
    </div>


    <!--    <div class="h-screen">-->
    <!--        <div-->
    <!--            class="h-full flex flex-row py-32 xl:gap-20 lg:gap-10 max-w-[1792px] md:gap-8 xl:px-32 lg:px-24 md:px-12 mx-auto">-->
    <!--            <div class="md:w-1/3-->
    <!--                        flex flex-col justify-between overflow-hidden">-->
    <!--            </div>-->
    <!--            <div class="md:w-2/3 flex flex-col gap-12">-->
    <!--                <GithubGraphComp/>-->
    <!--                <div class="border rounded-2xl p-4">-->
    <!--                    <p class="text-xl">Moje <strong>TOP</strong> repozitáre, na ktoré sa oplatí pozrieť.</p>-->
    <!--                    <GitHubTopRepositories class="mt-5"/>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
</template>

<style scoped></style>
