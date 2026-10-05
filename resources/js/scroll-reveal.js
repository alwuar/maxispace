// ============================================================
// Maxispace · Revelado de secciones + scroll suave
// ============================================================
import Lenis from 'lenis';

// ---- Ajustes ----
const SECCIONES = '.scroll-animate, .valor-agregado, .products, .cta, .mapa, .form';
const ELEMENTOS = '.bread, .titular, h2, h3, h4, h5, p, ul > li, .producto, .mapa__sucursal, iframe, img, .formulario, a.btn';
const ESCALON_MS = 90;   // tiempo entre un elemento y el siguiente
const REPETIR = true;    // true = se ocultan al salir y vuelven a aparecer · false = solo una vez

const reducirMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// ------------------------------------------------------------
// 1. Scroll suave (rueda del mouse y trackpad). En celular se
//    deja el scroll nativo, que ya es suave.
// ------------------------------------------------------------
let lenis = null;
if (!reducirMovimiento) {
    lenis = new Lenis({ duration: 1.1, smoothWheel: true });
    const raf = (t) => { lenis.raf(t); requestAnimationFrame(raf); };
    requestAnimationFrame(raf);
}

// Espacio para que el título no quede debajo del navbar flotante.
// Se guarda como scroll-padding-top, que respetan Lenis y el navegador.
const espacioNavbar = () => {
    const nav = document.querySelector('.navbar-cristal');
    const espacio = nav ? Math.round(nav.offsetHeight + 40) : 0; // alto + margen de arriba + aire
    document.documentElement.style.scrollPaddingTop = `${espacio}px`;
    return espacio;
};
espacioNavbar();
window.addEventListener('resize', espacioNavbar);

// Enlaces a secciones (#ubicacion, #contacto, ...) del navbar y del footer
document.addEventListener('click', (e) => {
    const enlace = e.target.closest('a[href^="#"]');
    if (!enlace) return;

    const hash = enlace.getAttribute('href');
    if (hash === '#') return;

    // #inicio sube hasta arriba aunque no exista una sección con ese id
    let destino = document.getElementById(hash.slice(1));
    if (!destino && hash === '#inicio') destino = 'top';
    if (!destino) return; // si no existe, deja el comportamiento normal

    e.preventDefault();
    const arriba = destino === 'top';
    marcarActivo(hash, true); // se marca de inmediato, sin pasar por las secciones intermedias

    if (lenis) {
        lenis.scrollTo(arriba ? 0 : destino, { onComplete: () => { viajando = false; actualizarActivo(); } });
    } else {
        const top = arriba ? 0 : destino.getBoundingClientRect().top + window.scrollY - espacioNavbar();
        window.scrollTo({ top, behavior: reducirMovimiento ? 'auto' : 'smooth' });
    }
    history.replaceState(null, '', hash);
});

// ------------------------------------------------------------
// 2. Revelado al hacer scroll
// ------------------------------------------------------------
if (!reducirMovimiento && 'IntersectionObserver' in window) {
    const items = [];

    document.querySelectorAll(SECCIONES).forEach((seccion) => {
        const candidatos = [...seccion.querySelectorAll(ELEMENTOS)];
        // Evita animar doble: si un elemento ya está dentro de otro que se anima, se omite
        const propios = candidatos.filter((el) =>
            !candidatos.some((otro) => otro !== el && otro.contains(el))
        );
        (propios.length ? propios : [seccion]).forEach((el) => {
            el.classList.add('reveal-item');
            items.push(el);
        });
    });

    const observer = new IntersectionObserver((entries) => {
        // Los que entran juntos salen uno tras otro, en orden
        entries
            .filter((en) => en.isIntersecting)
            .sort((a, b) => (a.target.compareDocumentPosition(b.target) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1))
            .forEach((en, i) => {
                en.target.style.setProperty('--reveal-delay', `${Math.min(i * ESCALON_MS, 600)}ms`);
                en.target.classList.add('is-revealed');
                if (!REPETIR) observer.unobserve(en.target);
            });

        if (REPETIR) {
            entries.filter((en) => !en.isIntersecting).forEach((en) => {
                // Si salió por arriba se esconde hacia arriba; si salió por abajo, hacia abajo.
                // Así al regresar aparece desde el lado correcto y nunca "parpadea" en el borde.
                en.target.classList.toggle('reveal-arriba', en.boundingClientRect.top < 0);
                en.target.classList.remove('is-revealed');
            });
        }
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -8% 0px', // empieza un poco antes del borde inferior
    });

    items.forEach((el) => observer.observe(el));
}

// ------------------------------------------------------------
// 3. Navbar: marca la opción de la sección en la que vas
// ------------------------------------------------------------
const enlacesNav = [...document.querySelectorAll('.navbar-cristal__enlaces a[href^="#"]')];
let viajando = false; // true mientras el scroll suave va hacia una sección elegida

// Pares [enlace, sección] en el orden en que aparecen en la página
const secciones = enlacesNav
    .map((a) => {
        const hash = a.getAttribute('href');
        const el = document.getElementById(hash.slice(1)) || (hash === '#inicio' ? document.querySelector('.header') : null);
        return el ? { hash, el } : null; // enlaces sin sección (p. ej. #tipos-de-almacenamiento) se ignoran
    })
    .filter(Boolean)
    .sort((a, b) => (a.el.compareDocumentPosition(b.el) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1));

function marcarActivo(hash, porClic = false) {
    if (porClic) viajando = true;
    enlacesNav.forEach((a) => {
        if (a.getAttribute('href') === hash) a.setAttribute('aria-current', 'location');
        else a.removeAttribute('aria-current');
    });
}

function actualizarActivo() {
    if (viajando || !secciones.length) return;

    // "Línea de lectura": un poco debajo del navbar, a ~35% de la pantalla
    const padding = parseInt(document.documentElement.style.scrollPaddingTop, 10) || 0;
    const linea = Math.max(padding + 10, window.innerHeight * 0.35);

    let actual = secciones[0].hash;
    secciones.forEach(({ hash, el }) => {
        if (el.getBoundingClientRect().top <= linea) actual = hash;
    });

    // Hasta abajo de la página: la última sección, aunque no llegue a la línea
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2) {
        actual = secciones[secciones.length - 1].hash;
    }
    marcarActivo(actual);
}

let pendiente = false;
const alHacerScroll = () => {
    if (pendiente) return;
    pendiente = true;
    requestAnimationFrame(() => { pendiente = false; actualizarActivo(); });
};
window.addEventListener('scroll', alHacerScroll, { passive: true });
window.addEventListener('resize', alHacerScroll);
// Sin Lenis (scroll nativo) el clic no tiene "onComplete": se libera al terminar de moverse
window.addEventListener('scrollend', () => { if (viajando && !lenis) { viajando = false; actualizarActivo(); } });
actualizarActivo();
