<script setup lang="ts">

const debugButtonPressed = () => {
    console.log("Debug button pressed.");
    window.axios("/debug-button-pressed")
        .then(() => {
            console.log("done");
        })
        .catch((e) => {
            console.error(e);
        });
}

</script>

<template>
    <button id="debugButton" @click="debugButtonPressed" class="btn btn-primary absolute top-0 left-0 z-10 hidden">
        DEBUG BUTTON
    </button>
</template>
