<template>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Koka sijas kalkulators</h1>

        <form @submit.prevent="calculate">
            <label class="block mb-2">Platums B (m): <input v-model.number="B" type="number" step="0.01" class="input text-black" /></label>
            <label class="block mb-2">Augstums H (m): <input v-model.number="H" type="number" step="0.01" class="input text-black" /></label>
            <label class="block mb-2">Izturība f (KPa): <input v-model.number="f" type="number" step="0.01" class="input text-black" /></label>
            <label class="block mb-2">Garums L (m): <input v-model.number="L" type="number" step="0.01" class="input text-black" /></label>
            <button type="submit" class="btn">Aprēķināt</button>
        </form>

        <div v-if="result" class="mt-4">
            <p><strong>W:</strong> {{ result.W }} m³</p>
            <p><strong>q:</strong> {{ result.q }} KN/m</p>
            <div ref="canvasContainer" class="mt-4 w-full h-[400px] border"></div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';

const B = ref(0.3);
const H = ref(0.6);
const f = ref(20000);
const L = ref(5);
const result = ref(null);
const canvasContainer = ref(null);
let scene, camera, renderer, mesh;

const calculate = async () => {
    const res = await axios.post('/calculate', { B: B.value, H: H.value, f: f.value, L: L.value });
    result.value = res.data;
    renderBeam();
};

function renderBeam() {
    if (!canvasContainer.value) return;

    // Clean up previous scene
    if (renderer) {
        renderer.dispose?.();
        while (canvasContainer.value.firstChild) {
            canvasContainer.value.removeChild(canvasContainer.value.firstChild);
        }
    }

    const width = canvasContainer.value.clientWidth;
    const height = canvasContainer.value.clientHeight;

    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
    camera.position.set(5, 5, 10);

    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(width, height);
    canvasContainer.value.appendChild(renderer.domElement);

    // Beam
    const geometry = new THREE.BoxGeometry(L.value, H.value, B.value);
    const material = new THREE.MeshStandardMaterial({ color: 0x44aa88 });
    mesh = new THREE.Mesh(geometry, material);
    scene.add(mesh);

    // Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(5, 10, 7);
    scene.add(ambientLight);
    scene.add(directionalLight);

    // Controls
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.update();

    const animate = () => {
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    };
    animate();
}

onMounted(() => {
    renderBeam();
});
</script>

<style>
.input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: white;
    color: black;
}
.btn {
    background: #4f46e5;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    margin-top: 1rem;
    cursor: pointer;
}
</style>
