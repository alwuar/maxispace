// Panel administrativo
import 'bootstrap/js/dist/collapse';

document.addEventListener('DOMContentLoaded', () => {
    // --- Filtros: mostrar fechas solo en "Personalizado" y aplicar al elegir periodo ---
    const form = document.getElementById('filtros');
    if (form) {
        const rango = form.querySelector('.rango-fechas');
        form.querySelectorAll('input[name="periodo"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                const personalizado = radio.value === 'personalizado';
                if (rango) rango.hidden = !personalizado;
                if (personalizado) {
                    form.querySelector('input[name="desde"]')?.focus();
                } else {
                    form.requestSubmit();
                }
            });
        });
    }

    // --- Tabla: toda la fila abre el prospecto ---
    document.querySelectorAll('tr[data-href]').forEach((tr) => {
        tr.addEventListener('click', (e) => {
            if (e.target.closest('a, button')) return;
            window.location = tr.dataset.href;
        });
    });
});
