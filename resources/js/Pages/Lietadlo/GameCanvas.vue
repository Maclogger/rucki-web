<template>
    <div>
        <canvas ref="canvasRef" width="800" height="600" style="border: 1px solid black;"></canvas>
        <div v-if="player" class="info">
            Aktuálny uhol lietadla (v stupňoch): {{ Math.round(player.angle) }}°
        </div>
        <div v-if="!allImagesLoaded" class="loading-indicator">
            Načítavam obrázky ({{ loadedImagesCount }} / {{ totalImages }})...
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';

// Pomocná funkcia pre modulo
function mod(n: number, m: number): number {
    return ((n % m) + m) % m;
}

const canvasRef = ref<HTMLCanvasElement | null>(null);
let ctx: CanvasRenderingContext2D | null = null;

const camera = ref({ x: 0, y: 0 });

const player = ref({
    x: 400,
    y: 300,
    // Tieto rozmery sa už nepoužívajú na kreslenie, ale môžu sa hodiť neskôr (napr. pre kolízie)
    width: 50,
    height: 100,
    angle: 0,
    constantSpeed: 2.5,
});

const keysPressed = ref<{ [key: string]: boolean }>({});

const playerImages: HTMLImageElement[] = [];
const backgroundImg = new Image();

const totalImages = 361;
const loadedImagesCount = ref(0);
const allImagesLoaded = computed(() => loadedImagesCount.value === totalImages);


onMounted(() => {
    const canvas = canvasRef.value;
    if (!canvas) return;
    ctx = canvas.getContext('2d');

    player.value.x = canvas.width / 2;
    player.value.y = canvas.height / 2;

    const onImageLoad = () => {
        loadedImagesCount.value++;
        if (allImagesLoaded.value) {
            camera.value.x = (backgroundImg.width / 2) - (canvas.width / 2);
            camera.value.y = (backgroundImg.height / 2) - (canvas.height / 2);
            gameLoop();
        }
    };

    backgroundImg.onload = onImageLoad;
    backgroundImg.src = '/images/pozadie.png';

    for (let i = 0; i < 360; i++) {
        const img = new Image();
        const paddedIndex = i.toString().padStart(3, '0');
        img.src = `/images/lietadla/lietadlo_${paddedIndex}.png`;
        img.onload = onImageLoad;
        playerImages.push(img);
    }

    window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('keyup', handleKeyUp);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    window.removeEventListener('keyup', handleKeyUp);
});

const handleKeyDown = (e: KeyboardEvent) => {
    keysPressed.value[e.key] = true;
};

const handleKeyUp = (e: KeyboardEvent) => {
    keysPressed.value[e.key] = false;
};

// --- FUNKCIA UPDATE S OPRAVENÝM UHLOM ---
const update = () => {
    const rotationSpeed = 3;

    if (keysPressed.value['ArrowLeft']) {
        player.value.angle -= rotationSpeed;
    }
    if (keysPressed.value['ArrowRight']) {
        player.value.angle += rotationSpeed;
    }

    player.value.angle = mod(player.value.angle, 360);

    // --- OPRAVA UHLA POHYBU ---
    // Ak obrázok pre 0 stupňov smeruje HORE, musíme od uhla odpočítať 90 stupňov,
    // aby sa zosúladil s matematickou súradnicovou sústavou (kde 0 stupňov je DOPRAVA).
    const radians = (player.value.angle - 90) * (Math.PI / 180);
    const dx = player.value.constantSpeed * Math.cos(radians);
    const dy = player.value.constantSpeed * Math.sin(radians);

    camera.value.x += dx;
    camera.value.y += dy;
};

// --- FUNKCIA DRAW S OPRAVOU DEFORMÁCIE ---
const draw = () => {
    if (!ctx || !canvasRef.value || !allImagesLoaded.value) return;
    const canvas = canvasRef.value;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (backgroundImg.width > 0) {
        const offsetX = mod(camera.value.x, backgroundImg.width);
        const offsetY = mod(camera.value.y, backgroundImg.height);
        for (let x = -offsetX; x < canvas.width; x += backgroundImg.width) {
            for (let y = -offsetY; y < canvas.height; y += backgroundImg.height) {
                ctx.drawImage(backgroundImg, x, y);
            }
        }
    }

    const imageIndex = Math.round(player.value.angle) % 360;
    const currentImage = playerImages[imageIndex];

    if (currentImage && currentImage.complete) {
        // --- OPRAVA DEFORMÁCIE ---
        // Použijeme prirodzenú šírku a výšku obrázku (`naturalWidth`, `naturalHeight`),
        // aby sme predišli akémukoľvek skresleniu.
        const w = currentImage.naturalWidth;
        const h = currentImage.naturalHeight;

        ctx.drawImage(
            currentImage,
            player.value.x - w / 2, // Posunieme o polovicu skutočnej šírky
            player.value.y - h / 2, // Posunieme o polovicu skutočnej výšky
            w,                      // Kreslíme so skutočnou šírkou
            h                       // Kreslíme so skutočnou výškou
        );
    }
};

const gameLoop = () => {
    if (allImagesLoaded.value) {
        update();
        draw();
    }
    requestAnimationFrame(gameLoop);
};

</script>

<style scoped>
/* CSS ostáva rovnaké */
.info {
    margin-top: 10px;
    font-family: sans-serif;
    font-size: 1.2rem;
    font-weight: bold;
}
.loading-indicator {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    border-radius: 10px;
    font-size: 1.5rem;
}
</style>
