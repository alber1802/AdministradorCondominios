<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Nómina</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #2563eb;
            color: white;
        }

        .header-table td {
            padding: 20px;
            vertical-align: top;
        }

        .company-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .company-info td {
            padding: 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .info-header {
            background: #f8f9fa;
            font-weight: bold;
            color: #2563eb;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table th {
            background: #2563eb;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        .details-table td {
            padding: 12px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .details-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .totals-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .totals-table td {
            padding: 8px 12px;
            border: 1px solid #ddd;
        }

        .totals-table .label {
            background: #f8f9fa;
            font-weight: bold;
            text-align: right;
        }

        .totals-table .total-row {
            background: #2563eb;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pagado {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-pendiente {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-vencido {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            background: #374151;
            color: white;
            margin-top: 30px;
        }

        .footer-table td {
            padding: 15px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-large {
            font-size: 16px;
        }

        .text-xl {
            font-size: 18px;
        }

        .font-bold {
            font-weight: bold;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .invoice-number {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Header Principal -->
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    <h1 class="text-xl font-bold mb-10">FACTURA DE NÓMINA</h1>
                    <p>Comprobante de pago de servicios</p>
                    <p style="margin-top: 10px; font-size: 11px;">IntelliTower Management System</p>
                </td>
                <td style="width: 40%;" class="text-right">
                    <div class="invoice-number">
                        <p style="font-size: 11px; margin-bottom: 5px;">FACTURA N°</p>
                        <p class="text-large font-bold">#{{ str_pad($nomina->id ?? 1, 6, '0', STR_PAD_LEFT) }}</p>
                        <p style="font-size: 10px; margin-top: 5px;">{{ now()->format('d/m/Y') }}</p>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Información de Empresa y Cliente -->
        <table class="company-info">
            <tr>
                <td class="info-header" style="width: 50%;">DATOS DE LA EMPRESA</td>
                <td class="info-header" style="width: 50%;">DATOS DEL CLIENTE</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <strong>IntelliTower Management</strong><br>
                    Administración de Edificios<br>
                    <br>
                    <strong>Dirección:</strong><br>
                    Av. Principal #123, Ciudad<br>
                    <br>
                    <strong>Contacto:</strong><br>
                    Teléfono: +1 (555) 123-4567<br>
                    Email: facturacion@intellitower.com<br>
                    <br>
                    <strong>RFC:</strong> ITM123456789
                </td>
                <td style="vertical-align: top;">
                    <strong>{{ $user->name }}</strong><br>
                    <br>
                    <strong>Email:</strong><br>
                    {{ $user->email }}<br>
                    <br>
                    <strong>Teléfono:</strong><br>
                    {{ $user->telefono ?? 'No especificado' }}<br>
                    <br>
                    <strong>Carnet Identidad:</strong><br>
                    {{ $user->carnet_identidad ?? 'No especificado' }}<br>
                    <br>
                    <strong>Rol:</strong><br>
                    {{ $user->rol ?? 'Cliente' }}<br>
                    <br>
                    <strong>ID Cliente:</strong> {{ $user->id }}
                </td>
            </tr>
        </table>

        <!-- Detalles de Facturación -->
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 40%;">CONCEPTO</th>
                    <th style="width: 20%;">PERÍODO</th>
                    <th style="width: 15%;">ESTADO</th>
                    <th style="width: 25%;" class="text-right">MONTO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Servicios de Nómina</strong><br>
                        <small>Administración y gestión de nómina</small><br>
                        <small>Procesamiento de pagos</small>
                    </td>
                    <td class="text-center">
                        <strong>{{ $nomina->mes }}</strong>
                    </td>
                    <td class="text-center">
                        <span class="status-badge status-{{ strtolower($nomina->estado) }}">
                            {{ $nomina->estado }}
                        </span>
                    </td>
                    <td class="text-right">
                        <strong class="text-large">${{ number_format($nomina->monto, 2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Información Adicional de Pago -->
        @if ($nomina->fecha_pago)
            <table class="company-info" style="margin-bottom: 20px;">
                <tr>
                    <td class="info-header">INFORMACIÓN DE PAGO</td>
                </tr>
                <tr>
                    <td>
                        <strong>Fecha de Pago:</strong>
                        {{ \Carbon\Carbon::parse($nomina->fecha_pago)->format('d/m/Y') }}<br>
                        <strong>Método de Pago:</strong> Transferencia Bancaria<br>
                        <strong>Referencia:</strong> NOM-{{ $nomina->id }}-{{ date('Y') }}
                    </td>
                </tr>
            </table>
        @endif

        <!-- Totales -->
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="text-right">${{ number_format($nomina->monto, 2) }}</td>
            </tr>
            <tr>
                <td class="label">IVA (0%):</td>
                <td class="text-right">$0.00</td>
            </tr>
            <tr>
                <td class="label">Descuentos:</td>
                <td class="text-right">$0.00</td>
            </tr>
            <tr class="total-row">
                <td class="label">TOTAL A PAGAR:</td>
                <td class="text-right font-bold text-large">${{ number_format($nomina->monto, 2) }}</td>
            </tr>
        </table>

        <!-- Términos y Condiciones -->
        <table class="company-info" style="margin-bottom: 15px;">
            <tr>
                <td class="info-header">TÉRMINOS Y CONDICIONES</td>
            </tr>
            <tr>
                <td style="font-size: 10px; line-height: 1.3;">
                    • Esta factura es válida por 30 días a partir de la fecha de emisión.<br>
                    • Para cualquier aclaración, contacte al departamento de facturación.<br>
                    • Este documento es generado automáticamente por el sistema IntelliTower.<br>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <table class="footer-table">
            <tr>
                <td style="width: 60%;">
                    <strong>INFORMACIÓN DE CONTACTO</strong><br>
                    Para consultas sobre esta factura:<br>
                    Email: facturacion@intellitower.com<br>
                    Teléfono: +1 (555) 123-4567<br>
                    Horario: Lunes a Viernes 9:00 AM - 6:00 PM
                </td>
                <td style="width: 40%;" class="text-right">
                    <small>Factura generada automáticamente</small><br>
                    <small>{{ now()->format('d/m/Y H:i:s') }}</small><br>
                    <br>
                    <strong>IntelliTower Management</strong><br>
                    <small>Sistema de Gestión Empresarial</small>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
