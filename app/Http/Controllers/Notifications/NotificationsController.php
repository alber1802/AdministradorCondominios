<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Anuncio;
use App\Models\Comentario;
use App\Models\Consumo;
use App\Models\Factura;
use App\Models\Nomina;
use App\Models\Reserva;
use App\Models\Ticket;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;

use App\Jobs\SendAnuncioNotificationJob;
use App\Jobs\SendComentarioNotificationJob;
use App\Jobs\SendConsumoNotificationJob;
use App\Jobs\SendFacturaCreadaNotificationJob;
use App\Jobs\SendFacturaPagadaNotificationJob;
use App\Jobs\SendNominaNotificationJob;
use App\Jobs\SendReservaNotificationJob;
use App\Jobs\SendTicketActualizadoNotificationJob;
use App\Jobs\SendTicketCreadoNotificationJob;

class NotificationsController extends Controller
{
   

}
