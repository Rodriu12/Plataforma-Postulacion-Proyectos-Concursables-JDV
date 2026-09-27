<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Asignación de Emergencia - Vecindar</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f0f9ff; color: #333333; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #bae6fd;">

        <h2 style="color: #0369a1; text-align: center; margin-top: 0;">🙋 Vecindar - Asignación de Emergencia</h2>
        <h3 style="color: #111827; border-bottom: 2px solid #e0f2fe; padding-bottom: 10px;">Has sido asignado como voluntario</h3>

        <div style="background-color: #f0f9ff; border-left: 4px solid #0284c7; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <p style="margin: 0 0 8px 0; font-size: 14px; color: #4b5563;">
                <strong style="color: #1e3a8a;">Tipo:</strong> {{ ucfirst($emergencia->tipo) }}
            </p>
            <p style="margin: 0 0 8px 0; font-size: 14px; color: #4b5563;">
                <strong style="color: #1e3a8a;">Ubicación:</strong> {{ $emergencia->ubicacion }}
            </p>
            <p style="margin: 0; font-size: 14px; color: #4b5563; line-height: 1.5;">
                <strong style="color: #1e3a8a;">Descripción:</strong><br>
                {{ $emergencia->descripcion }}
            </p>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/admin') }}" style="background-color: #0284c7; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Ver en la Plataforma</a>
        </div>

        <p style="text-align: center; font-size: 11px; color: #9ca3af; margin-top: 40px;">
            © {{ date('Y') }} Vecindar. Este es un correo automático, por favor no respondas a este mensaje.
        </p>
    </div>
</body>
</html>
