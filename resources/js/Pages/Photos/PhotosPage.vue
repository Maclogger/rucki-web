<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';

</script>


<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Fotky</p>
        </template>
        <template #default>
            <div class="w-full bg-red-300">
                <div>
                    <img src="/photos-show/bmw.jpg" />
                </div>
            </div>




        </template>
    </AuthLayout>
</template>
