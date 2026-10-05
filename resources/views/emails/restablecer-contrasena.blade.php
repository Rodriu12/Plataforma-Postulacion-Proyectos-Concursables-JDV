<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Define tu contraseña - Vecindar</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #eff6ff; color: #333333; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #bfdbfe;">

        <h2 style="color: #1d4ed8; text-align: center; margin-top: 0;">🔑 Vecindar</h2>
        <h3 style="color: #111827; border-bottom: 2px solid #eff6ff; padding-bottom: 10px;">Define tu contraseña</h3>

        <p style="font-size: 14px; color: #4b5563; line-height: 1.6;">
            Hola {{ $usuario->name }},
        </p>

        <p style="font-size: 14px; color: #4b5563; line-height: 1.6;">
            Para poder ingresar a Vecindar necesitas definir tu contraseña. Sigue estos pasos:
        </p>

        <ol style="font-size: 14px; color: #4b5563; line-height: 1.8; padding-left: 20px;">
            <li>Haz clic en el botón <strong>"Definir mi contraseña"</strong> de abajo.</li>
            <li>Se abrirá una página donde debes escribir tu nueva contraseña (y repetirla para confirmarla).</li>
            <li>Guarda los cambios: quedarás con tu contraseña lista para iniciar sesión con tu correo ({{ $usuario->email }}).</li>
        </ol>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #1d4ed8; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Definir mi contraseña</a>
        </div>

        <p style="font-size: 13px; color: #6b7280; line-height: 1.5;">
            Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
            <span style="word-break: break-all; color: #1d4ed8;">{{ $url }}</span>
        </p>

        <p style="font-size: 12px; color: #9ca3af; line-height: 1.5; margin-top: 20px;">
            Este enlace vence en un tiempo limitado por seguridad. Si no solicitaste este correo ni esperabas una cuenta en Vecindar, puedes ignorarlo.
        </p>

        <p style="text-align: center; font-size: 11px; color: #9ca3af; margin-top: 30px;">
            © {{ date('Y') }} Vecindar. Este es un correo automático, por favor no respondas a este mensaje.
        </p>
    </div>
</body>
</html>
