<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $factura->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .company-details {
            color: #666;
            font-size: 11px;
        }
        
        .invoice-info {
            text-align: right;
            flex: 1;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        
        .invoice-number {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-pagado {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-vencido {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .billing-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .billing-info {
            flex: 1;
            margin-right: 20px;
        }
        
        .billing-title {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .billing-details {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
        }
        
        .detail-row {
            margin-bottom: 8px;
        }
        
        .detail-label {
            font-weight: bold;
            color: #374151;
            display: inline-block;
            width: 80px;
        }
        
        .detail-value {
            color: #6b7280;
        }
        
        .invoice-details {
            margin-bottom: 30px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .amount-section {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .amount-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .amount-value {
            font-size: 32px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 11px;
        }
        
        .footer-note {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #f59e0b;
        }
        
        @media print {
            .container {
                padding: 0;
            }
            
            body {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">Sistema de Gestión</div>
                <div class="company-details">
                    Administración de Departamentos<br>
                    Gestión de Facturas y Pagos
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">FACTURA</div>
                <div class="invoice-number">#{{ substr($factura->id, 0, 8)  }}.......</div>
                <span class="status-badge status-{{ $factura->estado }}">
                    {{ ucfirst($factura->estado) }}
                </span>
            </div>
        </div>

        <!-- Billing Information -->
        <div class="billing-section">
            <div class="billing-info">
                <div class="billing-title">DATOS DEL CLIENTE</div>
                <div class="billing-details">
                    <div class="detail-row">
                        <span class="detail-label">Nombre:</span>
                        <span class="detail-value">{{ $factura->user->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $factura->user->email }}</span>
                    </div>
                     <div class="detail-row">
                        <span class="detail-label">Carnet Identidad:</span>
                        <span class="detail-value">{{ $factura->user->carnet_identidad ?? 'No especificado' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Teléfono:</span>
                        <span class="detail-value">{{ $factura->user->telefono ?? 'No especificado' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="billing-info">
                <div class="billing-title">DEPARTAMENTO</div>
                <div class="billing-details">
                    <div class="detail-row">
                        <span class="detail-label">Número:</span>
                        <span class="detail-value">{{ $factura->departamento->numero }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Bloque:</span>
                        <span class="detail-value">{{ $factura->departamento->bloque }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Piso:</span>
                        <span class="detail-value">{{ $factura->departamento->piso }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="billing-title">DETALLES DE LA FACTURA</div>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Tipo de Servicio:</span>
                    <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $factura->tipo)) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha de Emisión:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha de Vencimiento:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Estado:</span>
                    <span class="detail-value">{{ ucfirst($factura->estado) }}</span>
                </div>
            </div>
        </div>

        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-label">MONTO TOTAL A PAGAR</div>
            <div class="amount-value">${{ number_format($factura->monto, 2) }}</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            @if($factura->estado === 'vencido')
                <div class="footer-note">
                    <strong>⚠️ FACTURA VENCIDA:</strong> Esta factura ha superado su fecha de vencimiento. 
                    Por favor, proceda con el pago lo antes posible para evitar recargos adicionales.
                </div>
            @elseif($factura->estado === 'pendiente')
                <div class="footer-note">
                    <strong>📋 FACTURA PENDIENTE:</strong> Esta factura está pendiente de pago. 
                    Fecha límite: {{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }}
                </div>
            @endif
            
            <p>Factura generada automáticamente el {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>Para consultas o aclaraciones, contacte al administrador del edificio.</p>
        </div>
    </div>
</body>
</html>