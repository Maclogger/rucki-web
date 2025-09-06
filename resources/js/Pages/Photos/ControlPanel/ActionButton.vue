<script setup lang="ts">
import { usePhotosStore } from "@/stores/photosStore";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { computed } from "vue";

const props = defineProps<{
    subTitle?: string | null,
    icon?: string | null,
    onClick?: () => void,
    btnClass?: string | null,
}>();

const getBtnClass = () => {
    return props.btnClass ?? "btn-primary";
}

const photoStore = usePhotosStore();

const isDisabled = computed(() => {
    return photoStore.getSelectedCount() <= 0;
});


</script>


<template>
    <button class="btn aspect-square h-24 flex flex-col items-center justify-center gap-1 rounded-xl"
        :class="getBtnClass()" @click="onClick" :disabled="isDisabled">
        <font-awesome-icon v-if="props.icon" :icon="props.icon" class="text-2xl" />
        <div v-if="props.subTitle">{{ props.subTitle }}</div>
    </button>
</template>
