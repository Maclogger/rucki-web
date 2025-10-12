<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {computed} from "vue";
import {useToastsStore} from "@/stores/toastsStore";

const props = defineProps<{
    idOfToastInStore: number;
}>();

const toastsStore = useToastsStore();


const closeToast = () => {
    toastsStore.closeToast(props.idOfToastInStore);
}

const toastInfo = computed(() => {
    return toastsStore.getToastInfo(props.idOfToastInStore);
});


</script>

<template>
    <div :class="['alert', toastInfo.alertClass]">
        <font-awesome-icon
            class="text-2xl"
            :class="toastInfo.textColor"
            :icon="toastInfo.icon"
        />
        <span :class="toastInfo.textColor">{{ toastInfo.message }}</span>
        <button class="btn btn-neutral h-6 m-0 px-2" @click="closeToast">
            <font-awesome-icon :icon="['fas', 'xmark']"/>
        </button>
    </div>
</template>

<style scoped></style>
