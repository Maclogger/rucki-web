<script setup lang="ts">

import {useGitHubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";
import GitHubTile from "@/Pages/PublicDomain/GitHub/GitHubTile.vue";

const gitHubStore = useGitHubStore();

const {gitHubRecords} = storeToRefs(gitHubStore);

const getItemAtRowColumn = (r: number, s: number): string => {
    if (s <= 1) {
        const width = "w-10";
        const className = width + " h-4 m-0 p-0 text-lg";
        switch (r) {
            case 3:
                return '<p class="' + className + '">Pon</p>'
            case 5:
                return '<p class="' + className + '">Str</p>'
            case 7:
                return '<p class="' + className + '">Pia</p>'
            default:
                return '<div class="' + width + '"></div>';
        }
    }

    if (r == 1) {
        return ""; // TODO
    }

    return '<div class="w-4 h-4 bg-green-500 rounded-[2px]"></div>';
}


</script>

<template>
    <div class="h-full ">
        <div class="p-2">
            <div class="flex flex-col gap-1">
                <div v-for="r in 8" :key="`row-${r}`" class="flex gap-1 ">
                    <div v-for="s in 54" :key="`col-${r}-${s}`">
                        <div v-html="getItemAtRowColumn(r, s)"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
