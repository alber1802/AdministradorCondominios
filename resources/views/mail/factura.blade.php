<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <title>Notificación de Factura - {{ $factura->id }}</title>

    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:AllowPNG/>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->

    <style type="text/css">
        /* Reset styles */
        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        /* Media queries for responsive design */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 10px !important;
            }

            .mobile-padding {
                padding-left: 15px !important;
                padding-right: 15px !important;
                padding-top: 20px !important;
                padding-bottom: 20px !important;
            }

            .mobile-center {
                text-align: center !important;
            }

            .mobile-hide {
                display: none !important;
            }

            .mobile-full-width {
                width: 100% !important;
                display: block !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-bottom: 20px !important;
            }

            .mobile-stack {
                display: block !important;
                width: 100% !important;
            }

            .mobile-text-center {
                text-align: center !important;
            }

            .mobile-font-size {
                font-size: 16px !important;
            }

            .mobile-small-padding {
                padding: 10px !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .container {
                margin: 0 5px !important;
            }

            .mobile-padding {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            h1 {
                font-size: 24px !important;
            }

            h2 {
                font-size: 20px !important;
            }

            h3 {
                font-size: 16px !important;
            }
        }
    </style>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif; line-height: 1.6; color: #333333;">
    <!-- Main container table -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Email content wrapper -->
                <table role="presentation" class="container" cellspacing="0" cellpadding="0" border="0"
                    width="600"
                    style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px;">
                    <!-- Header section -->
                    <tr>
                        <td class="mobile-padding"
                            style="padding: 40px 40px 20px 40px; border-bottom: 3px solid #007bff;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <!-- Company logo and branding -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            width="100%">
                                            <tr>
                                                <td style="text-align: left; vertical-align: middle;">
                                                    <h1
                                                        style="margin: 0; font-size: 28px; font-weight: bold; color: #007bff; font-family: Arial, sans-serif;">
                                                        🏢 Sistema de Gestión
                                                    </h1>
                                                    <p
                                                        style="margin: 5px 0 0 0; font-size: 16px; color: #6c757d; font-weight: normal;">
                                                        Administración de Condominios
                                                    </p>
                                                </td>
                                                <td style="text-align: right; vertical-align: middle;"
                                                    class="mobile-hide">
                                                    <div
                                                        style="background-color: #007bff; color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: bold;">
                                                        FACTURA
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 30px;">
                                        <!-- Personalized greeting -->
                                        <h2 style="margin: 0; font-size: 24px; color: #333333; font-weight: normal;">
                                            Hola {{ $factura->user->name }},
                                        </h2>
                                        <p
                                            style="margin: 10px 0 0 0; font-size: 16px; color: #666666; line-height: 1.5;">
                                            Te informamos que se ha generado una nueva factura para tu departamento.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Client and department info section -->
                    <tr>
                        <td class="mobile-padding" style="padding: 20px 40px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td
                                        style="background-color: #f8f9fa; padding: 25px; border-radius: 8px; border-left: 4px solid #007bff;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            width="100%">
                                            <tr>
                                                <td style="width: 50%; vertical-align: top; padding-right: 20px;"
                                                    class="mobile-full-width mobile-stack">
                                                    <!-- Client information -->
                                                    <h3 style="margin: 0 0 15px 0; font-size: 18px; color: #007bff; font-weight: bold;"
                                                        role="heading" aria-level="3">
                                                        <span role="img" aria-label="Información">📋</span>
                                                        Información del Cliente
                                                    </h3>
                                                    <table role="presentation" cellspacing="0" cellpadding="0"
                                                        border="0" width="100%">
                                                        <tr>
                                                            <td style="padding: 5px 0; font-size: 14px;">
                                                                <strong style="color: #495057;">Nombre:</strong>
                                                                <span
                                                                    style="color: #6c757d;">{{ $factura->user->name }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 5px 0; font-size: 14px;">
                                                                <strong style="color: #495057;">Email:</strong>
                                                                <span
                                                                    style="color: #6c757d;">{{ $factura->user->email }}</span>
                                                            </td>

                                                        </tr>
                                                        @if ($factura->user->carnet_identidad)
                                                            <tr>
                                                                <td style="padding: 5px 0; font-size: 14px;">
                                                                    <strong style="color: #495057;">Carnet
                                                                        Identidad:</strong>
                                                                    <span
                                                                        style="color: #6c757d;">{{ $factura->user->carnet_identidad }}</span>
                                                                </td>
                                                            </tr>
                                                        @endif


                                                        @if ($factura->user->telefono)
                                                            <tr>
                                                                <td style="padding: 5px 0; font-size: 14px;">
                                                                    <strong style="color: #495057;">Teléfono:</strong>
                                                                    <span
                                                                        style="color: #6c757d;">{{ $factura->user->telefono }}</span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </td>
                                                <td style="width: 50%; vertical-align: top; padding-left: 20px;"
                                                    class="mobile-full-width mobile-stack">
                                                    <!-- Department information -->
                                                    <h3 style="margin: 0 0 15px 0; font-size: 18px; color: #007bff; font-weight: bold;"
                                                        role="heading" aria-level="3">
                                                        <span role="img" aria-label="Departamento">🏠</span>
                                                        Información del Departamento
                                                    </h3>
                                                    <table role="presentation" cellspacing="0" cellpadding="0"
                                                        border="0" width="100%">
                                                        <tr>
                                                            <td style="padding: 5px 0; font-size: 14px;">
                                                                <strong style="color: #495057;">Número:</strong>
                                                                <span
                                                                    style="color: #6c757d;">{{ $factura->departamento->numero }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 5px 0; font-size: 14px;">
                                                                <strong style="color: #495057;">Bloque:</strong>
                                                                <span
                                                                    style="color: #6c757d;">{{ $factura->departamento->bloque }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 5px 0; font-size: 14px;">
                                                                <strong style="color: #495057;">Piso:</strong>
                                                                <span
                                                                    style="color: #6c757d;">{{ $factura->departamento->piso }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Invoice details section -->
                    <tr>
                        <td class="mobile-padding" style="padding: 20px 40px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                width="100%">
                                <tr>
                                    <td>
                                        <h3 style="margin: 0 0 20px 0; font-size: 20px; color: #333333; font-weight: bold; text-align: center;"
                                            role="heading" aria-level="3">
                                            <span role="img" aria-label="Dinero">💰</span> Detalles de la Factura
                                        </h3>

                                        <!-- Invoice details table -->
                                        <table role="table" cellspacing="0" cellpadding="0" border="0"
                                            width="100%"
                                            style="border: 2px solid #007bff; border-radius: 8px; overflow: hidden;"
                                            aria-label="Detalles de la factura">
                                            <!-- Invoice number and type -->
                                            <tr>
                                                <td style="background-color: #007bff; color: white; padding: 15px; font-weight: bold; text-align: center;"
                                                    colspan="2">
                                                    Factura #{{ $factura->id }} - {{ $factura->tipo }}
                                                </td>
                                            </tr>

                                            <!-- Invoice details rows -->
                                            <tr style="background-color: #f8f9fa;">
                                                <td
                                                    style="padding: 12px 20px; font-weight: bold; color: #495057; border-bottom: 1px solid #dee2e6; width: 40%;">
                                                    Fecha de Emisión:
                                                </td>
                                                <td
                                                    style="padding: 12px 20px; color: #6c757d; border-bottom: 1px solid #dee2e6;">
                                                    {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td
                                                    style="padding: 12px 20px; font-weight: bold; color: #495057; border-bottom: 1px solid #dee2e6;">
                                                    Fecha de Vencimiento:
                                                </td>
                                                <td
                                                    style="padding: 12px 20px; color: #6c757d; border-bottom: 1px solid #dee2e6;">
                                                    {{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }}
                                                </td>
                                            </tr>

                                            <tr style="background-color: #f8f9fa;">
                                                <td
                                                    style="padding: 12px 20px; font-weight: bold; color: #495057; border-bottom: 1px solid #dee2e6;">
                                                    Estado:
                                                </td>
                                                <td style="padding: 12px 20px; border-bottom: 1px solid #dee2e6;">
                                                    @if ($factura->estado === 'pendiente')
                                                        <span
                                                            style="background-color: #ffc107; color: #212529; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase;"
                                                            role="status" aria-label="Estado de factura pendiente">
                                                            <span role="img" aria-label="Reloj">⏳</span> Pendiente
                                                        </span>
                                                    @elseif($factura->estado === 'vencido')
                                                        <span
                                                            style="background-color: #dc3545; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase;"
                                                            role="status" aria-label="Estado de factura vencida">
                                                            <span role="img" aria-label="Advertencia">⚠️</span>
                                                            Vencido
                                                        </span>
                                                    @elseif($factura->estado === 'pagado')
                                                        <span
                                                            style="background-color: #28a745; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase;"
                                                            role="status" aria-label="Estado de factura pagada">
                                                            <span role="img" aria-label="Verificado">✅</span>
                                                            Pagado
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>

                                            <!-- Total amount - highlighted -->
                                            <tr>
                                                <td style="background-color: #28a745; color: white; padding: 20px; font-size: 18px; font-weight: bold; text-align: center;"
                                                    colspan="2">
                                                    MONTO TOTAL: ${{ number_format($factura->monto, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </table>

                                        @if ($factura->descripcion)
                                            <!-- Additional description -->
                                            <div
                                                style="margin-top: 20px; padding: 15px; background-color: #e9ecef; border-radius: 6px; border-left: 4px solid #6c757d;">
                                                <p
                                                    style="margin: 0; font-size: 14px; color: #495057; font-style: italic;">
                                                    <strong>Descripción:</strong> {{ $factura->descripcion }}
                                                </p>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Dynamic messages section -->
                    <tr>
                        <td class="mobile-padding" style="padding: 20px 40px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                width="100%">
                                <tr>
                                    <td>
                                        @if ($factura->estado === 'pendiente')
                                            <!-- Pending payment message -->
                                            <div
                                                style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                                                <h4 style="margin: 0 0 10px 0; color: #856404; font-size: 18px; font-weight: bold;"
                                                    role="heading" aria-level="4">
                                                    <span role="img" aria-label="Reloj de alarma">⏰</span>
                                                    Recordatorio de Pago
                                                </h4>
                                                <p
                                                    style="margin: 0 0 10px 0; color: #856404; font-size: 16px; line-height: 1.5;">
                                                    Tu factura está pendiente de pago. Te recordamos que tienes hasta el
                                                    <strong>{{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }}</strong>
                                                    para realizar el pago y evitar recargos.
                                                </p>
                                                <p style="margin: 0; color: #856404; font-size: 14px;">
                                                    Puedes realizar el pago a través de los métodos habituales o
                                                    contactarnos para más información.
                                                </p>
                                            </div>
                                        @elseif($factura->estado === 'vencido')
                                            <!-- Overdue payment message -->
                                            <div
                                                style="background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                                                <h4 style="margin: 0 0 10px 0; color: #721c24; font-size: 18px; font-weight: bold;"
                                                    role="heading" aria-level="4">
                                                    <span role="img" aria-label="Sirena de emergencia">🚨</span>
                                                    PAGO VENCIDO - ACCIÓN REQUERIDA
                                                </h4>
                                                <p
                                                    style="margin: 0 0 10px 0; color: #721c24; font-size: 16px; line-height: 1.5;">
                                                    <strong>¡ATENCIÓN!</strong> Tu factura venció el
                                                    <strong>{{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }}</strong>
                                                    y aún no hemos recibido el pago.
                                                </p>
                                                <p
                                                    style="margin: 0 0 10px 0; color: #721c24; font-size: 16px; line-height: 1.5;">
                                                    Para evitar recargos adicionales y posibles restricciones de
                                                    servicios,
                                                    te solicitamos realizar el pago lo antes posible.
                                                </p>
                                                <p
                                                    style="margin: 0; color: #721c24; font-size: 14px; font-weight: bold;">
                                                    Si ya realizaste el pago, por favor contáctanos para actualizar el
                                                    estado de tu factura.
                                                </p>
                                            </div>
                                        @elseif($factura->estado === 'pagado')
                                            <!-- Payment confirmation message -->
                                            <div
                                                style="background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                                                <h4 style="margin: 0 0 10px 0; color: #155724; font-size: 18px; font-weight: bold;"
                                                    role="heading" aria-level="4">
                                                    <span role="img" aria-label="Marca de verificación">✅</span>
                                                    ¡Pago Confirmado!
                                                </h4>
                                                <p
                                                    style="margin: 0 0 10px 0; color: #155724; font-size: 16px; line-height: 1.5;">
                                                    Hemos recibido tu pago correctamente. Tu factura ha sido marcada
                                                    como
                                                    <strong>PAGADA</strong> en nuestro sistema.
                                                </p>
                                                <p style="margin: 0; color: #155724; font-size: 14px;">
                                                    Gracias por tu puntualidad. Este correo sirve como comprobante de
                                                    que tu pago ha sido procesado.
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Contact information -->
                                        <div
                                            style="background-color: #e9ecef; border-radius: 8px; padding: 20px; text-align: center;">
                                            <h4 style="margin: 0 0 15px 0; color: #495057; font-size: 16px; font-weight: bold;"
                                                role="heading" aria-level="4">
                                                <span role="img" aria-label="Teléfono">📞</span> ¿Necesitas Ayuda?
                                            </h4>
                                            <p
                                                style="margin: 0 0 10px 0; color: #6c757d; font-size: 14px; line-height: 1.5;">
                                                Si tienes alguna consulta sobre tu factura o necesitas asistencia con el
                                                pago,
                                                no dudes en contactarse con el administrador del condiminio :
                                            </p>
                                            <table role="presentation" cellspacing="0" cellpadding="0"
                                                border="0" width="100%">
                                                <tr>
                                                    <td style="text-align: center; padding: 5px;">
                                                        <span
                                                            style="color: #007bff; font-weight: bold; font-size: 14px;">
                                                            <span role="img" aria-label="Email">📧</span>
                                                            <a href="{{ Storage::url($factura->comprobante_pdf) }}"
                                                                style="color: #007bff; text-decoration: none;"
                                                                aria-label="Enviar email a administración">Ver PDF</a>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center; padding: 5px;">
                                                        <span
                                                            style="color: #007bff; font-weight: bold; font-size: 14px;">
                                                            <span role="img" aria-label="Teléfono móvil">📱</span>
                                                            <a href="tel:+56912345678"
                                                                style="color: #007bff; text-decoration: none;"
                                                                aria-label="Llamar al teléfono de administración">+56 9
                                                                1234 5678</a>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center; padding: 5px;">
                                                        <span style="color: #6c757d; font-size: 12px;">
                                                            Horario de atención: Lunes a Viernes, 9:00 - 18:00 hrs
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer section -->
                    <tr>
                        <td class="mobile-padding"
                            style="padding: 30px 40px; background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                width="100%">
                                <tr>
                                    <td style="text-align: center; font-size: 14px; color: #6c757d;">
                                        <p style="margin: 0 0 10px 0;">
                                            Este es un correo automático, por favor no responder.
                                        </p>
                                        <p style="margin: 0; font-size: 12px;">
                                            © {{ date('Y') }} Sistema de Gestión de Condominios
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
