<script setup lang="ts">
import SquareButton from "@/Pages/Home/SquareButton.vue";
import { router } from '@inertiajs/vue3';
import AuthLayout from "@/Layouts/AuthLayout.vue";

interface ButtonConfig {
    label: string;
    icon?: string;
    url?: string;
    wip?: boolean;
}

const buttons: ButtonConfig[] = [
    { label: "Súbory", icon: "fa-solid fa-image", url: "/files" },
    { label: "GitHub", icon: "fa-brands fa-github", url: "/github-secured" },
    { label: "Buffer", icon: "fa-solid fa-file-arrow-up", url: "/buffer-secured" },
    { label: "Buffer kódy", icon: "fa-solid fa-key", url: "/buffer-codes" },
    { label: "Nové Súbory", icon: "fa-solid fa-image", url: "/new_files" },
    { label: "WIP", wip: true },
    { label: "WIP", wip: true },
    { label: "WIP", wip: true },
    { label: "WIP", wip: true },
    { label: "WIP", wip: true },
];

const handleClick = (button: ButtonConfig) => {
    if (button.wip) return;
    if (button.url) router.get(button.url);
};

</script>

<template>
    <AuthLayout>
        <template #default>
            <div class="grid lg:grid-cols-6 md:grid-cols-4 grid-cols-2 place-items-center gap-y-4 gap-x-4">
                <SquareButton
                    v-for="(button, idx) in buttons"
                    :key="idx"
                    :icon="button.icon"
                    :wip="button.wip ?? false"
                    :onClick="() => handleClick(button)"
                    :label="button.label"
                />
            </div>
        </template>
    </AuthLayout>
</template>
