<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import BufferComponent from "@/Pages/Buffer/BufferUpload/BufferComponent.vue";
</script>

<template>
    <AuthLayout >
        <template #headline>
            <p class="text-2xl">Buffer</p>
        </template>
        <template #default>
            <BufferComponent/>
        </template>
    </AuthLayout>
</template>
