// Panobooking front-end entry.
// Livewire 4 ships Alpine; everything else here is progressive enhancement on plain markup.

import '@fontsource-variable/inter';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'swiper/css';
import 'swiper/css/pagination';
import 'glightbox/dist/css/glightbox.min.css';

import * as bootstrap from 'bootstrap';
import Swiper from 'swiper';
import { Navigation, Pagination, A11y } from 'swiper/modules';
import GLightbox from 'glightbox';

window.bootstrap = bootstrap;

// ---------------------------------------------------------------------------
// Card rails: <div class="card-rail" data-rail> <div class="swiper">…</div> </div>
// ---------------------------------------------------------------------------
function initRails(root = document) {
    root.querySelectorAll('[data-rail]:not([data-rail-ready])').forEach((rail) => {
        rail.dataset.railReady = '1';
        new Swiper(rail.querySelector('.swiper'), {
            modules: [Navigation, Pagination, A11y],
            slidesPerView: 1.15,
            spaceBetween: 16,
            watchOverflow: true,
            navigation: {
                prevEl: rail.querySelector('[data-rail-prev]'),
                nextEl: rail.querySelector('[data-rail-next]'),
            },
            pagination: { el: rail.querySelector('.swiper-pagination'), clickable: true },
            breakpoints: {
                576: { slidesPerView: 2.1 },
                992: { slidesPerView: 3, spaceBetween: 20 },
                1200: { slidesPerView: Number(rail.dataset.perView || 4), spaceBetween: 24 },
            },
        });
    });
}

// ---------------------------------------------------------------------------
// Photo galleries: <a href="…" data-gallery="name"><img …></a>
// ---------------------------------------------------------------------------
let lightbox;
function initGalleries() {
    if (!document.querySelector('[data-gallery]')) return;
    lightbox?.destroy();
    lightbox = GLightbox({ selector: '[data-gallery]', touchNavigation: true, loop: true });
}

// ---------------------------------------------------------------------------
// Broken listing photos fall back to the placeholder once.
// ---------------------------------------------------------------------------
document.addEventListener('error', (e) => {
    const img = e.target;
    if (img.tagName === 'IMG' && img.dataset.fallback && img.src !== img.dataset.fallback) {
        img.src = img.dataset.fallback;
    }
}, true);

// ---------------------------------------------------------------------------
// Toasts: Livewire $this->dispatch('alert', type: 'success', message: '…')
// and session()->flash('alert', ['type' => …, 'message' => …]) on page load.
// ---------------------------------------------------------------------------
const ICONS = { success: 'bi-check-circle-fill', error: 'bi-exclamation-octagon-fill', warning: 'bi-exclamation-triangle-fill', info: 'bi-info-circle-fill' };

export function toast({ type = 'info', message = '' } = {}) {
    if (!message) return;
    const stack = document.getElementById('toast-stack');
    if (!stack) return;
    const el = document.createElement('div');
    el.className = `pb-toast is-${type}`;
    el.setAttribute('role', type === 'error' ? 'alert' : 'status');
    el.innerHTML = `<i class="bi ${ICONS[type] || ICONS.info}" aria-hidden="true"></i><div></div>
        <button type="button" class="btn-close" aria-label="Dismiss"></button>`;
    el.querySelector('div').textContent = message;
    el.querySelector('button').addEventListener('click', () => el.remove());
    stack.appendChild(el);
    setTimeout(() => el.remove(), type === 'error' ? 8000 : 5000);
}
window.pbToast = toast;

window.addEventListener('alert', (e) => toast(Array.isArray(e.detail) ? e.detail[0] : e.detail));

function flushFlashedToasts() {
    document.querySelectorAll('script[type="application/json"][data-flash-alert]').forEach((node) => {
        try { toast(JSON.parse(node.textContent)); } catch { /* ignore malformed */ }
        node.remove();
    });
}

// ---------------------------------------------------------------------------
// Boot, and re-run after Livewire swaps DOM.
// ---------------------------------------------------------------------------
function initAll() {
    initRails();
    initGalleries();
    flushFlashedToasts();
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => bootstrap.Tooltip.getOrCreateInstance(el));
}

document.addEventListener('DOMContentLoaded', initAll);
document.addEventListener('livewire:navigated', initAll);
document.addEventListener('livewire:init', () => {
    window.Livewire.hook('commit', ({ succeed }) => succeed(() => queueMicrotask(initAll)));
});
