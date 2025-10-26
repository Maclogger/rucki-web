<script setup lang="ts">
import { ToastProps, ToastSeverity, useToastsStore } from '@/stores/toastsStore';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const submit = () => {
    form.post('/contact', {
        onSuccess: () => {
            useToastsStore().displayToast({
                message: "Správa bola úspešne odoslaná",
                severity: ToastSeverity.SUCCESS,
            });
            form.reset();
        },
        onError: (errors) => {
            console.error("Errors during message send:", errors);
            const toast: ToastProps = {
                message: "Chyba pri odosielaní správy",
                severity: ToastSeverity.ERROR,
            };
            useToastsStore().displayToast(toast);
        },
    });
}
</script>

<template>
    <div class="mockup-window border border-base-300 shadow-2xl">
        <div class="bg-base-200 px-8 py-10">
            <h3 class="text-2xl font-bold  mb-6">Napíšte mi správu</h3>
            <form @submit.prevent="submit" class="space-y-4">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend text-lg">Vaše meno</legend>
                    <input type="text" v-model="form.name" placeholder="Ján Novák" class="input w-full text-lg" required/>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend text-lg">Váš Email</legend>
                    <input type="email" v-model="form.email" placeholder="jozef.novak@example.com" class="input w-full text-lg" required/>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend text-lg">Predmet</legend>
                    <input type="text" v-model="form.subject" placeholder="O čom chcete hovoriť?" class="input w-full text-lg" required/>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend text-lg">Správa</legend>
                    <textarea v-model="form.message" class="textarea h-24 w-full text-lg" placeholder="Bio" required></textarea>
                </fieldset>

                <button type="submit" :disabled="form.processing" class="btn btn-primary w-full text-lg py-6">
                    {{ form.processing ? 'Odosiela sa...' : 'Odoslať správu' }}
                </button>
            </form>
        </div>
    </div>

</template>
