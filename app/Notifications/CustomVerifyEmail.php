<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('🏢 Verificar tu cuenta en IntelliTower')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Te damos la bienvenida a **IntelliTower**, tu plataforma integral para la administración de condominios.')
            ->line('🔐 Para garantizar la seguridad de tu cuenta y acceder a todas las funcionalidades del sistema, necesitamos verificar tu dirección de correo electrónico.')
            ->line('**¿Qué podrás hacer una vez verificada tu cuenta?**')
            ->line('• 📊 Consultar estados de cuenta y facturas')
            ->line('• 💰 Realizar pagos en línea')
            ->line('• 🎫 Crear y dar seguimiento a tickets de soporte')
            ->line('• 📅 Hacer reservas de áreas comunes')
            ->line('• 💬 Comunicarte con otros residentes')
            ->line('• 📢 Mantenerte al día con anuncios importantes')
            ->action('✅ Verificar mi Email', $verificationUrl)
            ->line('⏰ **Importante:** Este enlace de verificación expirará en 60 minutos por seguridad.')
            ->line('Si no creaste una cuenta en IntelliTower, puedes ignorar este mensaje de forma segura.')
            ->line('---')
            ->line('¿Necesitas ayuda? Contáctanos respondiendo a este email.')
            ->salutation('Saludos cordiales,  
**El equipo de IntelliTower**  
*Tu socio en la administración inteligente de condominios*');
    }
}