<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Nómina - {{ $nomina->mes }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 30px 25px;
            background-color: #ffffff;
        }

        .greeting {
            font-size: 18px;
            color: #2563eb;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .nomina-details {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-left: 4px solid #2563eb;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .nomina-details h3 {
            color: #1e40af;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .detail-label {
            font-weight: 500;
            color: #4b5563;
        }

        .detail-value {
            font-weight: 600;
            color: #1f2937;
        }

        .amount {
            font-size: 20px;
            color: #059669;
            font-weight: bold;
        }

        .download-section {
            text-align: center;
            margin: 30px 0;
            padding: 25px;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border-radius: 12px;
            border: 1px solid #a7f3d0;
        }

        .download-button {
            display: inline-block;
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .download-button:hover {
            background: linear-gradient(135deg, #047857, #065f46);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .download-icon {
            margin-right: 8px;
        }

        .info-list {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .info-list ul {
            list-style: none;
            padding-left: 0;
        }

        .info-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            position: relative;
            padding-left: 25px;
        }

        .info-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #059669;
            font-weight: bold;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .contact-info {
            background-color: #1f2937;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .contact-info h4 {
            margin-bottom: 15px;
            color: #f3f4f6;
        }

        .contact-details {
            font-size: 14px;
            line-height: 1.8;
            color: #d1d5db;
        }

        .footer {
            background-color: #374151;
            color: #9ca3af;
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }

        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pagado {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>

<body>
    <div class="email-container">

        <!-- Header -->
        <div class="header">
            <div class="company-logo">IntelliTower</div>
            <h1>Comprobante de Nómina</h1>
            <p>{{ $nomina->mes }} - Factura #{{ str_pad($nomina->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Estimado/a {{ $nomina->user->name }},
            </div>

            <p>Esperamos que se encuentre bien. Le informamos que su comprobante de nómina correspondiente al período
                <strong>{{ $nomina->mes }}</strong> ya está disponible para su descarga.</p>

            <!-- Detalles de la Nómina -->
            <div class="nomina-details">
                <h3>📋 Detalles de su Nómina</h3>

                <div class="detail-row">
                    <span class="detail-label">Número de Factura:</span>
                    <span class="detail-value">#{{ str_pad($nomina->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Período:</span>
                    <span class="detail-value">{{ $nomina->mes }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Estado:</span>
                    <span class="status-badge status-{{ strtolower($nomina->estado) }}">{{ $nomina->estado }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Monto Total:</span>
                    <span class="detail-value amount">${{ number_format($nomina->monto, 2) }}</span>
                </div>

                @if ($nomina->fecha_pago)
                    <div class="detail-row">
                        <span class="detail-label">Fecha de Pago:</span>
                        <span
                            class="detail-value">{{ \Carbon\Carbon::parse($nomina->fecha_pago)->format('d/m/Y') }}</span>
                    </div>
                @endif

                <div class="detail-row">
                    <span class="detail-label">Fecha de Generación:</span>
                    <span class="detail-value">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <!-- Botón de Descarga -->
            @if ($nomina->comprobante_pdf)
                <div class="download-section">
                    <h3 style="color: #059669; margin-bottom: 15px;">📄 Descargar Comprobante</h3>
                    <p style="margin-bottom: 20px; color: #374151;">Haga clic en el botón de abajo para descargar su
                        comprobante de nómina en formato PDF:</p>

                    <a href="{{Storage::url($nomina->comprobante_pdf)}}" class="download-button" target="_blank">
                        <span class="download-icon">⬇️</span>
                        Descargar Comprobante PDF
                    </a>

                    <p style="font-size: 12px; color: #6b7280; margin-top: 15px;">
                        El enlace estará disponible por 30 días. Guarde el archivo en un lugar seguro.
                    </p>
                </div>
            @endif

            <!-- Información Incluida -->
            <div class="info-list">
                <h4 style="color: #1f2937; margin-bottom: 15px;">📊 Su comprobante incluye:</h4>
                <ul>
                    <li>Información detallada del empleado</li>
                    <li>Desglose completo de conceptos</li>
                    <li>Cálculos de subtotales y totales</li>
                    <li>Estado del pago y fechas importantes</li>
                    <li>Información de contacto para consultas</li>
                    <li>Términos y condiciones aplicables</li>
                </ul>
            </div>

            <p style="margin-top: 25px;">Si tiene alguna pregunta sobre su nómina o necesita aclaraciones sobre los
                conceptos incluidos, no dude en contactar a nuestro departamento de Recursos Humanos.</p>

            <p style="margin-top: 20px;">Agradecemos su dedicación y compromiso con la empresa.</p>

            <p style="margin-top: 25px; color: #2563eb; font-weight: 500;">
                Saludos cordiales,<br>
                <strong>Departamento de Recursos Humanos</strong><br>
                <strong>IntelliTower Management</strong>
            </p>
        </div>

        <!-- Información de Contacto -->
        <div class="contact-info">
            <h4>📞 Información de Contacto</h4>
            <div class="contact-details">
                <strong>Departamento de Recursos Humanos</strong><br>
                Email: rrhh@intellitower.com<br>
                Teléfono: +1 (555) 123-4567<br>
                Horario de Atención: Lunes a Viernes, 9:00 AM - 6:00 PM
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>IntelliTower Management System</strong></p>
            <p>Este es un correo automático generado por el sistema. Por favor, no responda directamente a esta
                dirección.</p>
            <p>© {{ date('Y') }} IntelliTower Management. Todos los derechos reservados.</p>
        </div>

    </div>
</body>

</html>
