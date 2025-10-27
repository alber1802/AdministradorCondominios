<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .section-title {
            color: #007bff;
            font-size: 20px;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .info-table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: bold;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            width: 30%;
        }
        .info-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            color: #212529;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .info-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .priority-alta {
            color: #dc3545;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority-media {
            color: #fd7e14;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority-baja {
            color: #28a745;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-abierto {
            background-color: #d4edda;
            color: #155724;
        }
        .status-en-progreso {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-cerrado {
            background-color: #f8d7da;
            color: #721c24;
        }
        .description-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            font-style: italic;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .cta-button:hover {
            background-color: #0056b3;
        }
        .department-info {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
        }
        .user-info {
            background-color: #f3e5f5;
            border-left: 4px solid #9c27b0;
        }
        .tech-info {
            background-color: #e8f5e8;
            border-left: 4px solid #4caf50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 Ticket Asignado</h1>
            <p>Se te ha asignado un nuevo ticket para atender</p>
        </div>

        <!-- Información del Ticket -->
        <h2 class="section-title">📋 Información del Ticket</h2>
        <table class="info-table">
            <tr>
                <th>ID del Ticket</th>
                <td><strong>#{{ $ticket->id }}</strong></td>
            </tr>
            <tr>
                <th>Título</th>
                <td><strong>{{ $ticket->titulo }}</strong></td>
            </tr>
            <tr>
                <th>Estado</th>
                <td>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $ticket->estado)) }}">
                        {{ ucfirst($ticket->estado) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Prioridad</th>
                <td>
                    <span class="priority-{{ strtolower($ticket->prioridad) }}">
                        {{ ucfirst($ticket->prioridad) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Fecha de Creación</th>
                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <!-- Información del Residente -->
        <h2 class="section-title">👤 Información del Residente</h2>
        <table class="info-table user-info">
            <tr>
                <th>Nombre Completo</th>
                <td><strong>{{ $ticket->user->name }}</strong></td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $ticket->user->email }}</td>
            </tr>
            @if($ticket->user->telefono)
            <tr>
                <th>Teléfono</th>
                <td>{{ $ticket->user->telefono }}</td>
            </tr>
            @endif
            <tr>
                <th>Rol</th>
                <td>{{ ucfirst($ticket->user->rol) }}</td>
            </tr>
        </table>

        {{-- <!-- Información del Departamento -->
        @if($ticket->user->departamentos->isNotEmpty())
        <h2 class="section-title">🏢 Información del Departamento</h2>
        @foreach($ticket->user->departamentos as $departamento)
        <table class="info-table department-info">
            <tr>
                <th>Número de Departamento</th>
                <td><strong>{{ $departamento->numero }}</strong></td>
            </tr>
            <tr>
                <th>Piso</th>
                <td>{{ $departamento->piso }}</td>
            </tr>
            <tr>
                <th>Bloque</th>
                <td>{{ $departamento->bloque }}</td>
            </tr>
            <tr>
                <th>Ubicación Completa</th>
                <td><strong>Bloque {{ $departamento->bloque }} - Piso {{ $departamento->piso }} - Depto {{ $departamento->numero }}</strong></td>
            </tr>
        </table>
        @endforeach
        @endif --}}

        <!-- Descripción del Problema -->
        <h2 class="section-title">📝 Descripción del Problema</h2>
        <div class="description-box">
            {{ $ticket->descripcion }}
        </div>

        <!-- Información del Técnico Asignado -->
        @if($ticket->tecnico)
        <h2 class="section-title">🔧 Técnico Asignado</h2>
        <table class="info-table tech-info">
            <tr>
                <th>Nombre del Técnico</th>
                <td><strong>{{ $ticket->tecnico->name }}</strong></td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $ticket->tecnico->email }}</td>
            </tr>
            @if($ticket->tecnico->telefono)
            <tr>
                <th>Teléfono</th>
                <td>{{ $ticket->tecnico->telefono }}</td>
            </tr>
            @endif
            <tr>
                <th>Rol</th>
                <td>{{ ucfirst($ticket->tecnico->rol) }}</td>
            </tr>
        </table>
        @endif

        {{-- <div style="text-align: center;">
            <a href="{{ url('/admin/tickets/' . $ticket->id) }}" class="cta-button">
                Ver Ticket Completo
            </a>
        </div> --}}

        <div class="footer">
            <p><strong>Sistema de Gestión de Tickets</strong></p>
            <p>Este es un email automático, por favor no responder directamente a este mensaje.</p>
            <p>Para cualquier consulta, contacta al administrador del sistema.</p>
        </div>
    </div>
</body>
</html>