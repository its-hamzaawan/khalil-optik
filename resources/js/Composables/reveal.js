// v-reveal — fades/slides an element in the first time it scrolls into view.
// Usage: <div v-reveal>…</div>   or   <div v-reveal="150"> (delay in ms)
const prefersReduced = () =>
    typeof window !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let observer;
function getObserver() {
    if (observer || typeof IntersectionObserver === 'undefined') return observer;
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
    );
    return observer;
}

export default {
    mounted(el, binding) {
        el.classList.add('reveal');
        if (binding.value) el.style.transitionDelay = `${binding.value}ms`;

        const io = getObserver();
        if (!io || prefersReduced()) {
            el.classList.add('is-visible');
            return;
        }
        io.observe(el);
    },
    unmounted(el) {
        observer?.unobserve(el);
    },
};
