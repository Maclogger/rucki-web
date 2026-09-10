<script setup lang="ts">
import { TresCanvas } from "@tresjs/core";
import { useGLTF } from "@tresjs/cientos";
import { ACESFilmicToneMapping, SRGBColorSpace, Vector3 } from "three";

// The model is a CC-BY asset: "Laptop" by Aullwen. The credit belongs in the footer.
const { execute } = useGLTF("/models/macbook/scene.gltf");
const { scene: laptop } = await execute();

// Turned a little off head-on: enough for the chassis to read as a solid object,
// little enough that the screen stays square to the reader.
laptop.rotation.y = -0.16;

const cameraPosition = new Vector3(0, 9, 33);
const cameraTarget = new Vector3(0, 4.5, 0);
const laptopPosition = new Vector3(0, -5, 0);

// Lit with three lights rather than an environment map, so the hero pulls no
// remote HDR and renders identically offline.
const keyLight = new Vector3(16, 24, 20);
const fillLight = new Vector3(-20, 8, 14);
const rimLight = new Vector3(-4, 14, -22);
</script>

<template>
    <div class="h-[min(78vh,42rem)] w-full">
        <TresCanvas
            clear-color="#00000000"
            :alpha="true"
            :tone-mapping="ACESFilmicToneMapping"
            :output-color-space="SRGBColorSpace"
        >
            <TresPerspectiveCamera :position="cameraPosition" :look-at="cameraTarget" :fov="32" />

            <TresAmbientLight :intensity="0.35" />
            <TresDirectionalLight :position="keyLight" :intensity="2.2" />
            <TresDirectionalLight :position="fillLight" :intensity="0.7" />
            <TresDirectionalLight :position="rimLight" :intensity="1.1" />

            <primitive :object="laptop" :position="laptopPosition" />
        </TresCanvas>
    </div>
</template>
