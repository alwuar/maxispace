  <div class="form pb-5 scroll-animate" id="contacto">

        <div class="container ">
            <div class="titular">
                <h4>¿Necesitas espacio extra?</h4>
                <p>Déjanos tus datos y te ayudamos a elegir la minibodega perfecta para lo que quieres guardar. Respuesta rápida y sin compromiso.</p>
            </div>
            <form class="row g-3 formulario" method="POST" action="{{ route('leads.store') }}" novalidate>
                <x-contacto.campos modo="seccion" />
            </form>
        </div>
    </div>

<script>
    // Los botones "Consulta disponibilidad" de cada bodega preseleccionan el tamaño
    (() => {
        const select = document.getElementById('tamano');
        if (!select) return;
        document.querySelectorAll('[data-tamano]').forEach(btn => {
            btn.addEventListener('click', () => {
                select.value = btn.dataset.tamano;
                setTimeout(() => document.getElementById('nombre')?.focus({ preventScroll: true }), 600);
            });
        });
    })();
</script>
