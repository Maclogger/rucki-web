<script setup lang="ts">
import {vysklonuj} from "@/utils/sklonovac";
import {useGithubStore} from "@/stores/githubStore";
import {computed} from "vue";
import {storeToRefs} from "pinia";

const githubStore = useGithubStore();
const {selected_year, data_by_year} = storeToRefs(githubStore);

const contributionsCount = computed(() => {
    return data_by_year.value?.get(selected_year.value)?.total_contributions ?? 0;
});


</script>

<template>
    <p class="text-xl">
        <strong>
            {{ contributionsCount }}
        </strong>
        {{ vysklonuj(contributionsCount, 'príspevok', 'príspevky', 'príspevkov', true) }}
        v roku
        <strong>
            {{ selected_year }}
        </strong>
    </p>
</template>
