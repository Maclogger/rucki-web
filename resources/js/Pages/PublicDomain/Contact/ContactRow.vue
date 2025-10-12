<script setup lang="ts">

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps<{
    icon?: string,
    headline?: string,
    value?: string,
    href?: string,
}>()

</script>

<template>
    <div class="flex items-center gap-3 bg-slate-800 rounded-lg p-4 hover:bg-slate-700 transition-colors">
        <font-awesome-icon v-if="props.icon" :icon="props.icon" class="text-2xl w-8"/>
        <div>
            <p v-if="props.headline" class="text-sm text-gray-400">{{ props.headline }}</p>
            <p v-if="props.value" class="text-lg font-semibold">{{ props.value }}</p>
        </div>
    </div>
</template>
