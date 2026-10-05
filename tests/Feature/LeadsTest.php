<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LeadsTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true, 'password' => 'secreto123']);
    }

    private function lead(array $attrs = [], ?string $fecha = null): Lead
    {
        $lead = Lead::create(array_merge([
            'nombre' => 'Prospecto',
            'telefono' => '9991234567',
            'estado' => LeadStatus::Recibido,
        ], $attrs));

        if ($fecha) {
            $lead->forceFill(['created_at' => Carbon::parse($fecha)])->save();
        }

        return $lead;
    }

    // ===== Formulario público =====

    public function test_formulario_guarda_prospecto_y_redirige_a_whatsapp(): void
    {
        $res = $this->post('/contacto', [
            'nombre' => 'Ana López',
            'telefono' => '999 123 4567',
            'email' => 'ana@example.com',
            'tamano' => '3x3',
            'que_almacenar' => 'Muebles de sala',
            'ciudad' => 'Mérida',
            'consentimiento' => '1',
        ]);

        $lead = Lead::firstOrFail();
        $this->assertSame('Ana López', $lead->nombre);
        $this->assertSame(LeadStatus::Recibido, $lead->estado);
        $this->assertNotNull($lead->consentimiento_at);
        $this->assertCount(1, $lead->activities);

        $destino = $res->headers->get('Location');
        $this->assertStringStartsWith('https://wa.me/529993515866?text=', $destino);

        $mensaje = rawurldecode(explode('?text=', $destino)[1]);
        $this->assertStringContainsString('*Folio:* '.$lead->folio(), $mensaje);
        $this->assertStringContainsString('*Nombre:* Ana López', $mensaje);
        $this->assertStringContainsString('*Tamaño de interés:* Bodega 3m x 3m', $mensaje);
        $this->assertStringContainsString('*Qué quiero guardar:* Muebles de sala', $mensaje);
    }

    public function test_formulario_valida_campos_y_consentimiento(): void
    {
        $this->post('/contacto', ['nombre' => '', 'telefono' => '12'])
            ->assertRedirect(url('/').'#contacto')
            ->assertSessionHasErrors(['nombre', 'telefono', 'consentimiento'], null, 'contacto');

        $this->assertSame(0, Lead::count());
    }

    public function test_bots_con_campo_trampa_no_se_guardan(): void
    {
        $this->post('/contacto', [
            'nombre' => 'Bot', 'telefono' => '9991234567', 'consentimiento' => '1', 'sitio_web' => 'http://spam',
        ])->assertRedirect();

        $this->assertSame(0, Lead::count());
    }

    public function test_la_landing_muestra_el_formulario(): void
    {
        $this->get('/')->assertOk()->assertSee('name="telefono"', false)->assertSee('Aún no lo sé');
    }

    // ===== Ventana emergente de los botones de WhatsApp =====

    public function test_botones_de_whatsapp_abren_la_ventana_con_instrucciones(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('id="modal-contacto"', false)
            ->assertSee('Llena tus datos y da clic en', false)
            ->assertSee('data-abrir-contacto data-origen="boton-menu"', false)
            ->assertSee('data-abrir-contacto data-origen="boton-flotante"', false)
            ->assertSee('id="m-nombre"', false);
    }

    public function test_ventana_guarda_el_origen_del_boton(): void
    {
        $res = $this->post('/contacto', [
            'nombre' => 'Pedro Ruiz', 'telefono' => '9997654321', 'consentimiento' => '1', 'origen' => 'boton-menu',
        ]);

        $this->assertStringStartsWith('https://wa.me/', $res->headers->get('Location'));
        $this->assertSame('boton-menu', Lead::firstOrFail()->origen);
    }

    public function test_origen_desconocido_se_guarda_como_formulario(): void
    {
        $this->post('/contacto', [
            'nombre' => 'Pedro Ruiz', 'telefono' => '9997654321', 'consentimiento' => '1', 'origen' => '<script>',
        ]);

        $this->assertSame('formulario-web', Lead::firstOrFail()->origen);
    }

    public function test_errores_en_la_ventana_la_vuelven_a_abrir(): void
    {
        // (sin assertSessionHasErrors aquí: consumiría los errores antes de la siguiente petición)
        $this->post('/contacto', ['nombre' => 'Pe', 'origen' => 'boton-flotante'])
            ->assertRedirect(url('/'));

        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('data-abrir-al-cargar', $html);
        // El aviso de errores aparece solo una vez: en la ventana, no en la sección
        $this->assertSame(1, substr_count($html, 'Revisa los campos marcados'));
    }

    // ===== Login =====

    public function test_invitado_es_enviado_al_login(): void
    {
        $this->get('/admin/prospectos')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee('Panel de administración');
    }

    public function test_solo_administradores_pueden_entrar(): void
    {
        User::factory()->create(['email' => 'normal@example.com', 'password' => 'secreto123', 'is_admin' => false]);

        $this->post('/admin/login', ['email' => 'normal@example.com', 'password' => 'secreto123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $admin = $this->admin();
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'secreto123'])
            ->assertRedirect('/admin/prospectos');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_usuario_no_admin_con_sesion_es_expulsado(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin/prospectos')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    // ===== Listado, filtros y exportación =====

    public function test_filtros_por_periodo_y_estado(): void
    {
        Carbon::setTestNow('2026-10-15 12:00:00'); // jueves

        $this->lead(['nombre' => 'Hoy Uno'], '2026-10-15 09:00');
        $this->lead(['nombre' => 'Lunes Semana', 'estado' => LeadStatus::Ganado], '2026-10-12 10:00');
        $this->lead(['nombre' => 'Inicio Mes'], '2026-10-01 08:00');
        $this->lead(['nombre' => 'Marzo Anio'], '2026-03-10 08:00');
        $this->lead(['nombre' => 'Anio Pasado'], '2025-12-31 23:00');

        $admin = $this->admin();

        $ver = fn (array $q) => $this->actingAs($admin)->get('/admin/prospectos?'.http_build_query($q))->assertOk();

        $ver(['periodo' => 'hoy'])->assertSee('Hoy Uno')->assertDontSee('Lunes Semana');
        $ver(['periodo' => 'semana'])->assertSee('Lunes Semana')->assertDontSee('Inicio Mes');
        $ver(['periodo' => 'mes'])->assertSee('Inicio Mes')->assertDontSee('Marzo Anio');
        $ver(['periodo' => 'anio'])->assertSee('Marzo Anio')->assertDontSee('Anio Pasado');
        $ver([])->assertSee('Anio Pasado');
        $ver(['periodo' => 'personalizado', 'desde' => '2025-12-01', 'hasta' => '2026-03-31'])
            ->assertSee('Anio Pasado')->assertSee('Marzo Anio')->assertDontSee('Inicio Mes');
        // Fechas al revés se acomodan solas
        $ver(['periodo' => 'personalizado', 'desde' => '2026-03-31', 'hasta' => '2025-12-01'])
            ->assertSee('Anio Pasado')->assertDontSee('Inicio Mes');
        $ver(['periodo' => 'mes', 'estado' => 'ganado'])->assertSee('Lunes Semana')->assertDontSee('Hoy Uno');
        $ver(['q' => 'marzo'])->assertSee('Marzo Anio')->assertDontSee('Hoy Uno');
    }

    public function test_exporta_con_el_filtro_seleccionado(): void
    {
        Carbon::setTestNow('2026-10-15 12:00:00');

        $this->lead(['nombre' => 'Dentro Mes', 'email' => 'a@b.com'], '2026-10-05 10:00');
        $this->lead(['nombre' => 'Fuera Mes'], '2026-09-20 10:00');

        $res = $this->actingAs($this->admin())->get('/admin/prospectos/exportar?periodo=mes');

        $res->assertOk();
        $this->assertStringContainsString('text/csv', $res->headers->get('Content-Type'));
        $this->assertStringContainsString('prospectos-maxispace-mes-2026-10-15.csv', $res->headers->get('Content-Disposition'));

        $csv = $res->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString('Dentro Mes', $csv);
        $this->assertStringNotContainsString('Fuera Mes', $csv);
    }

    // ===== Estado e historial =====

    public function test_registrar_contacto_cambia_estado_y_guarda_historial(): void
    {
        $admin = $this->admin();
        $lead = $this->lead(['nombre' => 'Carlos']);

        $this->actingAs($admin)->post("/admin/prospectos/{$lead->id}/actividad", [
            'estado' => 'contactado',
            'asunto' => 'Primer contacto',
            'medio' => 'llamada',
            'nota' => 'Le interesa la 3x3 por 3 meses.',
        ])->assertRedirect("/admin/prospectos/{$lead->id}");

        $lead->refresh();
        $this->assertSame(LeadStatus::Contactado, $lead->estado);
        $this->assertNotNull($lead->ultimo_contacto_at);

        $act = $lead->activities()->first();
        $this->assertSame(LeadStatus::Recibido, $act->estado_anterior);
        $this->assertSame($admin->id, $act->user_id);

        $this->actingAs($admin)->get("/admin/prospectos/{$lead->id}")
            ->assertOk()
            ->assertSee('Primer contacto')
            ->assertSee('Llamada')
            ->assertSee('Le interesa la 3x3 por 3 meses.')
            ->assertSee('Recibido → ', false);
    }

    public function test_registrar_contacto_requiere_asunto_y_medio(): void
    {
        $lead = $this->lead();

        $this->actingAs($this->admin())
            ->post("/admin/prospectos/{$lead->id}/actividad", ['estado' => 'ganado'])
            ->assertSessionHasErrors(['asunto', 'medio']);

        $this->assertSame(LeadStatus::Recibido, $lead->fresh()->estado);
    }
}
