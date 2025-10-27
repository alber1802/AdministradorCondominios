<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #1a1a1a;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #2d2d2d;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px;
            backdrop-filter: blur(10px);
        }
        
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
            color: #ffffff;
        }
        
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        
        .greeting {
            font-size: 18px;
            color: #e0e0e0;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .otp-container {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
        }
        
        .otp-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .otp-label {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }
        
        .otp-code {
            font-size: 48px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 8px;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }
        
        .expiry-info {
            background-color: #3a3a3a;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            border-left: 4px solid #ffd700;
        }
        
        .expiry-text {
            font-size: 16px;
            color: #ffd700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .timer-icon {
            font-size: 20px;
        }
        
        .instructions {
            font-size: 16px;
            color: #b0b0b0;
            line-height: 1.6;
            margin: 30px 0;
        }
        
        .footer {
            background-color: #1a1a1a;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #404040;
        }
        
        .footer-text {
            font-size: 14px;
            color: #808080;
            margin: 0;
            line-height: 1.5;
        }
        
        .security-note {
            background-color: #2a2a2a;
            border: 1px solid #404040;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .security-note h3 {
            color: #ff6b6b;
            font-size: 16px;
            margin: 0 0 10px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .security-note p {
            color: #c0c0c0;
            font-size: 14px;
            margin: 0;
            line-height: 1.5;
        }
        
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .otp-code {
                font-size: 36px;
                letter-spacing: 4px;
            }
            
            .header {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="https://media.licdn.com/dms/image/v2/D4E12AQEvVTvEqQq4Cw/article-cover_image-shrink_720_1280/article-cover_image-shrink_720_1280/0/1713280396806?e=1761782400&v=beta&t=E1_spFQrQqmALFvlfdTXpXtZV6xxLi_Tqe5Wz7uJ2zw" alt="Logo">
            </div>
            <h1>Código de Verificación</h1>
        </div>
        
        <div class="content">
            <p class="greeting">
                Hola,<br>
                Hemos recibido una solicitud para acceder a tu cuenta. Utiliza el siguiente código de verificación:
            </p>
            
            <div class="otp-container">
                <div class="otp-label">Tu código de verificación</div>
                <div class="otp-code">{{ $code }}</div>
            </div>
            
            <div class="expiry-info">
                <p class="expiry-text">
                    <span class="timer-icon">⏱️</span>
                    Este código expira en {{ $seconds }} segundos
                </p>
            </div>
            
            <p class="instructions">
                Ingresa este código en la página de inicio de sesión para completar tu autenticación. 
                Si no solicitaste este código, puedes ignorar este mensaje de forma segura.
            </p>
            
            <div class="security-note">
                <h3>🔒 Nota de Seguridad</h3>
                <p>
                    Nunca compartas este código con nadie. Nuestro equipo nunca te pedirá este código por teléfono o email.
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p class="footer-text">
                Este es un mensaje automático, por favor no respondas a este email.<br>
                Si tienes problemas, contacta a nuestro equipo de soporte.
            </p>
        </div>
    </div>
</body>
</html>