<script setup lang="ts">
import type { GitHubRecord } from "@/stores/githubStore";
import { computed } from "vue";

const props = defineProps<{
    day: number,
    week: number,
    gitHubRecord: GitHubRecord | null,
}>();

const getColor = computed(() => {
    const level = props.gitHubRecord?.year_level ?? 0;

    if (level === 0) {
        return 'rgba(235,237,240,0.07)';
    } else if (level > 0 && level <= 0.25) {
        return '#9be9a8';
    } else if (level > 0.25 && level <= 0.5) {
        return '#40c463';
    } else if (level > 0.5 && level <= 0.75) {
        return '#30a14e';
    } else {
        return '#216e39';
    }
});

</script>

<template>
    <div
        class="w-4 h-4 rounded-sm"
        :style="{ backgroundColor: getColor }"
        :title="gitHubRecord ? `${gitHubRecord.contributions_count} contributions on ${gitHubRecord.date.toLocaleDateString()}` : 'No contributions'"
    ></div>
</template>

<style scoped>
.rounded-sm {
    border-radius: 2px;
}
</style>
