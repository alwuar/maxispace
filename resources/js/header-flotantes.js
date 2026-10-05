// Hace aparecer los objetos del header al entrar en pantalla
// y desaparecer al salir (y volver a aparecer al regresar con el scroll).
document.addEventListener('DOMContentLoaded', () => {
    const flotantes = document.querySelectorAll('.flotante');
    if (!flotantes.length) return;

    // Navegadores viejos: se muestran directo, sin animación
    if (!('IntersectionObserver' in window)) {
        flotantes.forEach(el => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            entry.target.classList.toggle('is-visible', entry.isIntersecting);
        });
    }, {
        threshold: 0.2 // aparece cuando se ve al menos el 20% del objeto
    });

    flotantes.forEach(el => observer.observe(el));
});
