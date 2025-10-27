<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Filament\Models\Contracts\FilamentUser;
use Afsakar\FilamentOtpLogin\Models\Contracts\CanLoginDirectly;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;



use Carbon\Carbon;

use Yebor974\Filament\RenewPassword\Contracts\RenewPasswordContract;

use Yebor974\Filament\RenewPassword\Traits\RenewPassword;
use Yebor974\Filament\RenewPassword\RenewPasswordPlugin;



class User extends Authenticatable implements RenewPasswordContract, FilamentUser , HasAvatar,CanLoginDirectly, MustVerifyEmail
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable,HasRoles;

    use RenewPassword;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'carnet_identidad',
        'avatar_url',
        'rol',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_renew_password_at',
        'force_renew_password',
        'failed_attempts',
        'is_blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_recovery_codes' => 'array',
            'two_factor_confirmed_at' => 'datetime',
            'last_renew_password_at' => 'datetime',
            'force_renew_password' => 'boolean',
        ];
    }
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }


    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($user) {
            if ($user->isDirty('avatar_url')) {
                $user->deleteOldAvatar();
            }
        });
    }

    /**
     * Elimina el avatar anterior del storage
     */
    protected function deleteOldAvatar()
    {
        $oldAvatarUrl = $this->getOriginal('avatar_url');
        
        if ($oldAvatarUrl && Storage::exists($oldAvatarUrl)) {
            Storage::delete($oldAvatarUrl);
        }
    }

    /**
     * Determina si el usuario puede acceder al panel de Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
       
       
        return true; // Ajusta según tus necesidades de autorización
    }

    
    #para ignorar algunos usuerios par el OPT
    public function canLoginDirectly(): bool
    {
        return str($this->email)->endsWith('@admin.com');
    }



    // 1. Un usuario puede tener uno o muchos departamento asignados
    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }

    // 2. Un usuario puede tener muchas facturas
    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    // 3. Un usuario puede estar asociado a pagos en efectivo (si recibe pagos)
    public function pagosEfectivo()
    {
        return $this->hasMany(PagoEfectivo::class);
    }

    // 4. Un usuario puede tener muchas nóminas (si es personal)
    public function nominas()
    {
        return $this->hasMany(Nomina::class);
    }

    // 5. Un usuario puede tener muchos consumos a través de su departamento
    // (relación indirecta, hasManyThrough)
    public function consumos()
    {
        return $this->hasManyThrough(Consumo::class, Departamento::class);
    }

    // 6. Un usuario puede hacer muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    // 7. Un usuario puede crear muchos tickets (reportar problemas)
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // 8. Un usuario también puede ser técnico asignado en tickets
    public function ticketsAsignados()
    {
        return $this->hasMany(Ticket::class, 'tecnico_id');
    }

    // 9. Un usuario puede publicar muchos anuncios
    public function anuncios()
    {
        return $this->hasMany(Anuncio::class);
    }

    // 10. Un usuario puede escribir muchos comentarios en anuncios
    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function mascotas()
    {
        return $this->hasMany(Mascota::class,'propietario_id');
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    /**
     * Determina si el usuario necesita renovar su contraseña
     */
    // public function needRenewPassword(): bool
    // {
    //     try {
    //         // Intentar obtener el plugin desde el panel intelliTower
    //         $plugin = \Filament\Facades\Filament::getPanel('intelliTower')
    //             ->getPlugin('filament-renew-password');
    //     } catch (\Exception $e) {
    //         // Si no se encuentra el plugin, no requiere renovación
    //         return false;
    //     }
    //     // Si no hay plugin configurado, no requiere renovación
    //     if (!$plugin) {
    //         return false;
    //     }

    //     // Verificar si está forzado a renovar contraseña
    //     if ($plugin->getForceRenewPassword() && $this->{$plugin->getForceRenewColumn()}) {
    //         return true;
    //     }

    //     // Verificar si la contraseña ha expirado por tiempo
    //     if (!is_null($plugin->getPasswordExpiresIn())) {
    //         $lastRenewDate = $this->{$plugin->getTimestampColumn()};
            
    //         // Si nunca ha renovado, debe hacerlo
    //         if (is_null($lastRenewDate)) {
    //             return true;
    //         }

    //         // Verificar si han pasado los días configurados
    //         $expirationDate = \Carbon\Carbon::parse($lastRenewDate)
    //             ->addDays($plugin->getPasswordExpiresIn());
            
    //         if ($expirationDate < now()) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

    public function needRenewPassword(): bool
    {
        $plugin = RenewPasswordPlugin::get();
         if ($this->failed_attempts >= 3) {
            return true; // Aquí podrías redirigir al usuario a renovar contraseña o mostrar bloqueo
        }
    
        return
            (
                !is_null($plugin->getPasswordExpiresIn())
                && Carbon::parse($this->{$plugin->getTimestampColumn()})->addDays($plugin->getPasswordExpiresIn()) < now()
            ) || (
                $plugin->getForceRenewPassword()
                && $this->{$plugin->getForceRenewColumn()}
            );
    }
}
