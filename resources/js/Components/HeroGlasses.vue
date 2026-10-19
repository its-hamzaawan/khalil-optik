<script setup>
// Animated hero spectacles in the style of the Khalil Optik logo.
// Sequence on load: orbit rings appear → orange frame draws itself →
// glass lenses fade in → light glints sweep across the lenses (repeats).
// It floats gently and tilts towards the mouse cursor.
import { onBeforeUnmount, onMounted, ref } from 'vue';

defineProps({ years: Number });

const root = ref(null);
let raf = 0;

function onMove(e) {
    if (!root.value || raf) return;
    raf = requestAnimationFrame(() => {
        raf = 0;
        const r = root.value.getBoundingClientRect();
        const x = (e.clientX - (r.left + r.width / 2)) / window.innerWidth;
        const y = (e.clientY - (r.top + r.height / 2)) / window.innerHeight;
        root.value.style.setProperty('--ry', `${Math.max(-1, Math.min(1, x)) * 14}deg`);
        root.value.style.setProperty('--rx', `${Math.max(-1, Math.min(1, y)) * -10}deg`);
    });
}

onMounted(() => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    window.addEventListener('mousemove', onMove, { passive: true });
});
onBeforeUnmount(() => {
    window.removeEventListener('mousemove', onMove);
    cancelAnimationFrame(raf);
});

// Logo-style frame: right top bar → right lens → bridge → left top bar → left lens (open end, like the logo)
const frame =
    'M222 52 H348 Q366 52 363 66 L351 104 Q337 146 292 146 Q250 146 235 111 Q223 80 205 63 Q192 51 170 51 H52 Q34 51 37 65 L50 104 Q64 146 108 146 Q151 146 165 112 L172 93';
const leftLens = 'M40 54 H176 Q186 54 181 72 L168 110 Q154 143 108 143 Q66 143 53 104 Z';
const rightLens = 'M207 54 H360 L349 104 Q336 143 292 143 Q252 143 238 110 Q227 82 207 54 Z';
</script>

<template>
    <div ref="root" class="hg relative mx-auto aspect-square w-full max-w-[34rem]" aria-hidden="true">
        <!-- Soft orange glow behind -->
        <div class="hg-glow absolute inset-[18%] rounded-full" />

        <!-- Orbit rings -->
        <div class="hg-ring hg-ring-1 absolute inset-[6%] rounded-full border border-white/10" />
        <div class="hg-ring hg-ring-2 absolute inset-[16%] rounded-full border border-dashed border-white/10">
            <span class="hg-dot absolute -top-1.5 left-1/2 size-3 -translate-x-1/2 rounded-full bg-[#DC9A34] shadow-[0_0_14px_#DC9A34]" />
        </div>
        <div class="hg-ring hg-ring-3 absolute inset-0 rounded-full border border-white/5">
            <span class="hg-dot absolute bottom-[14%] right-[6%] size-2 rounded-full bg-cream-100/70" />
        </div>

        <!-- Spectacles -->
        <div class="hg-tilt absolute inset-0 flex items-center justify-center">
            <div class="hg-float w-[92%]">
                <svg viewBox="20 30 360 135" class="w-full overflow-visible" fill="none">
                    <defs>
                        <linearGradient id="hg-glass" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#9DB8DE" stop-opacity="0.28" />
                            <stop offset="0.55" stop-color="#9DB8DE" stop-opacity="0.06" />
                            <stop offset="1" stop-color="#DC9A34" stop-opacity="0.12" />
                        </linearGradient>
                        <linearGradient id="hg-shine" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0" stop-color="#fff" stop-opacity="0" />
                            <stop offset="0.5" stop-color="#fff" stop-opacity="0.55" />
                            <stop offset="1" stop-color="#fff" stop-opacity="0" />
                        </linearGradient>
                        <linearGradient id="hg-frame" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#F0B457" />
                            <stop offset="1" stop-color="#D08A22" />
                        </linearGradient>
                        <clipPath id="hg-lenses">
                            <path :d="leftLens" />
                            <path :d="rightLens" />
                        </clipPath>
                    </defs>

                    <!-- Glass -->
                    <g class="hg-lens">
                        <path :d="leftLens" fill="url(#hg-glass)" />
                        <path :d="rightLens" fill="url(#hg-glass)" />
                    </g>

                    <!-- Light glints sweeping across the lenses -->
                    <g clip-path="url(#hg-lenses)">
                        <rect class="hg-shine" x="-60" y="10" width="34" height="180" fill="url(#hg-shine)" transform="skewX(-22)" />
                        <rect class="hg-shine hg-shine-2" x="-30" y="10" width="10" height="180" fill="url(#hg-shine)" transform="skewX(-22)" />
                    </g>

                    <!-- Small reflections -->
                    <g class="hg-lens" stroke="#E4EBF6" stroke-opacity="0.55" stroke-width="3" stroke-linecap="round">
                        <path d="M70 72 Q86 64 104 66" />
                        <path d="M240 72 Q256 64 274 66" />
                    </g>

                    <!-- Frame shadow + frame (draws itself) -->
                    <path :d="frame" stroke="#000" stroke-opacity="0.25" stroke-width="13" stroke-linecap="round" stroke-linejoin="round" transform="translate(0 6)" class="hg-draw" pathLength="1" />
                    <path :d="frame" stroke="url(#hg-frame)" stroke-width="11" stroke-linecap="round" stroke-linejoin="round" class="hg-draw" pathLength="1" />
                    <!-- Highlight on the frame -->
                    <path :d="frame" stroke="#FFE2AE" stroke-opacity="0.5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" transform="translate(0 -2.5)" class="hg-draw hg-draw-late" pathLength="1" />
                </svg>
            </div>
        </div>

        <!-- Years badge -->
        <div class="hg-badge absolute bottom-[8%] left-[2%] rounded-2xl border border-white/10 bg-white/5 px-5 py-4 backdrop-blur-md">
            <p class="font-display text-4xl text-cream-100">{{ years }}+</p>
            <p class="text-xs uppercase tracking-[0.2em] text-navy-300">Years of trust</p>
        </div>
    </div>
</template>

<style scoped>
.hg {
    --rx: 0deg;
    --ry: 0deg;
    perspective: 900px;
}

/* 1. Rings fade/scale in, then rotate slowly */
.hg-ring {
    opacity: 0;
    animation: hg-ring-in 1.2s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}
.hg-ring-1 { animation-delay: 0.1s; }
.hg-ring-2 { animation: hg-ring-in 1.2s cubic-bezier(0.22, 1, 0.36, 1) 0.25s forwards, hg-spin 26s linear 1.4s infinite; }
.hg-ring-3 { animation: hg-ring-in 1.2s cubic-bezier(0.22, 1, 0.36, 1) 0.4s forwards, hg-spin 48s linear 1.4s infinite reverse; }
@keyframes hg-ring-in {
    from { opacity: 0; transform: scale(0.85); }
    to { opacity: 1; transform: scale(1); }
}
@keyframes hg-spin {
    to { transform: rotate(360deg); }
}

/* Glow breathing */
.hg-glow {
    background: radial-gradient(circle, rgb(220 154 52 / 0.28), rgb(220 154 52 / 0) 65%);
    filter: blur(20px);
    opacity: 0;
    animation: hg-fade 1.5s ease 1.2s forwards, hg-breathe 5s ease-in-out 2.7s infinite;
}
@keyframes hg-breathe {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.12); opacity: 0.65; }
}

/* 2. Frame draws itself */
.hg-draw {
    stroke-dasharray: 1;
    stroke-dashoffset: 1;
    animation: hg-draw 2.2s cubic-bezier(0.65, 0, 0.35, 1) 0.5s forwards;
}
.hg-draw-late { animation-delay: 1.6s; animation-duration: 1.6s; }
@keyframes hg-draw {
    to { stroke-dashoffset: 0; }
}

/* 3. Glass appears */
.hg-lens {
    opacity: 0;
    animation: hg-fade 1s ease 2.2s forwards;
}
@keyframes hg-fade {
    to { opacity: 1; }
}

/* 4. Glints sweep across the lenses every few seconds */
.hg-shine {
    animation: hg-shine 5s cubic-bezier(0.45, 0, 0.25, 1) 2.8s infinite;
    transform-box: fill-box;
}
.hg-shine-2 { animation-delay: 3s; }
@keyframes hg-shine {
    0% { translate: 0 0; }
    45%, 100% { translate: 520px 0; }
}

/* 5. Gentle float + tilt towards the cursor */
.hg-tilt {
    transform: rotateX(var(--rx)) rotateY(var(--ry));
    transform-style: preserve-3d;
    transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}
.hg-float {
    animation: hg-float 7s ease-in-out 2.6s infinite;
    filter: drop-shadow(0 18px 24px rgb(0 0 0 / 0.35));
}
@keyframes hg-float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-12px) rotate(-1.5deg); }
}

.hg-dot { animation: hg-pulse 2.4s ease-in-out infinite; }
@keyframes hg-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.45; }
}

/* Badge slides up last */
.hg-badge {
    opacity: 0;
    animation: hg-badge 0.9s cubic-bezier(0.22, 1, 0.36, 1) 2.6s forwards;
}
@keyframes hg-badge {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: none; }
}

@media (prefers-reduced-motion: reduce) {
    .hg *, .hg-ring, .hg-glow, .hg-draw, .hg-lens, .hg-badge {
        animation: none !important;
        opacity: 1 !important;
        stroke-dashoffset: 0 !important;
    }
    .hg-shine { display: none; }
}
</style>