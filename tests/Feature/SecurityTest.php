<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_brute_force_protection_on_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct-password')
        ]);

        // Simular múltiples intentos fallidos
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/admin/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password'
            ]);
        }

        // El siguiente intento debe ser limitado
        $response = $this->post('/admin/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        // Verificar que hay rate limiting activo
        $this->assertTrue(RateLimiter::tooManyAttempts('login:test@example.com', 5));
    }

    public function test_csrf_protection()
    {
        $user = User::factory()->create();
        
        // Intentar hacer request sin token CSRF
        $response = $this->post('/admin/users', [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(419); // CSRF token mismatch
    }

    public function test_sql_injection_protection()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $maliciousInput = "'; DROP TABLE users; --";
        
        // Intentar inyección SQL en búsqueda
        $response = $this->get('/admin/users?search=' . urlencode($maliciousInput));
        
        // Verificar que la tabla users aún existe
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_xss_protection()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $xssPayload = '<script>alert("XSS")</script>';
        
        // Intentar XSS en formulario
        $response = $this->post('/admin/users', [
            'name' => $xssPayload,
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        
        // Verificar que el script no se ejecuta
        $response = $this->get('/admin/users');
        $response->assertDontSee($xssPayload, false);
    }

    public function test_password_hashing()
    {
        $user = User::factory()->create([
            'password' => Hash::make('test-password')
        ]);

        // Verificar que la contraseña no se almacena en texto plano
        $this->assertNotEquals('test-password', $user->password);
        $this->assertTrue(Hash::check('test-password', $user->password));
    }

    public function test_session_security()
    {
        $response = $this->get('/');
        
        // Verificar configuración segura de cookies
        $this->assertEquals('lax', config('session.same_site'));
        $this->assertTrue(config('session.secure') || !config('app.env') === 'production');
        $this->assertTrue(config('session.http_only'));
    }

    public function test_file_upload_security()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Intentar subir archivo malicioso
        $maliciousFile = \Illuminate\Http\UploadedFile::fake()->create('malicious.php', 100);
        
        $response = $this->post('/admin/upload', [
            'file' => $maliciousFile
        ]);

        // Verificar que archivos PHP no son permitidos
        $response->assertSessionHasErrors(['file']);
    }

    public function test_two_factor_authentication()
    {
        $user = User::factory()->create([
            'two_factor_secret' => encrypt('test-secret')
        ]);

        // Login con credenciales correctas
        $response = $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        // Debe redirigir a 2FA si está habilitado
        if ($user->two_factor_secret) {
            $response->assertRedirect('/admin/two-factor-challenge');
        }
    }
}