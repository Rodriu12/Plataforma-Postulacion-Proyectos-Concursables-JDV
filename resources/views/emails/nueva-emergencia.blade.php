<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nueva Emergencia - Vecindar</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #fff7f7; color: #333333; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #fecaca;">

        <h2 style="color: #b91c1c; text-align: center; margin-top: 0;">⚠ Vecindar - Aviso de Emergencia</h2>
        <h3 style="color: #111827; border-bottom: 2px solid #fee2e2; padding-bottom: 10px;">Se ha reportado una nueva emergencia</h3>

        <div style="background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <p style="margin: 0 0 8px 0; font-size: 14px; color: #4b5563;">
                <strong style="color: #1e3a8a;">Tipo:</strong> {{ ucfirst($emergencia->tipo) }}
            </p>
            <p style="margin: 0 0 8px 0; font-size: 14px; color: #4b5563;">
                <strong style="color: #1e3a8a;">Ubicación:</strong> {{ $emergencia->ubicacion }}
            </p>
            <p style="margin: 0 0 8px 0; font-size: 14px; color: #4b5563;">
                <strong style="color: #1e3a8a;">Reportado por:</strong> {{ $emergencia->vecino->nombre ?? 'Vecino no identificado' }}
            </p>
            @if($emergencia->organizacion)
                <p style="margin: 0 0 8px 0; font-size: 14px; color: #4b5563;">
                    <strong style="color: #1e3a8a;">Organización:</strong> {{ $emergencia->organizacion->nombre }}
                </p>
            @endif
            <p style="margin: 0; font-size: 14px; color: #4b5563; line-height: 1.5;">
                <strong style="color: #1e3a8a;">Descripción:</strong><br>
                {{ $emergencia->descripcion }}
            </p>
        </div>

        <p style="color: #4b5563; font-size: 13px; line-height: 1.5;">
            Estado actual: <strong style="color: #b45309;">{{ str_replace('_', ' ', ucfirst($emergencia->estado)) }}</strong>
        </p>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/admin') }}" style="background-color: #b91c1c; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Revisar en la Plataforma</a>
        </div>

        <p style="text-align: center; font-size: 11px; color: #9ca3af; margin-top: 40px;">
            © {{ date('Y') }} Vecindar. Este es un correo automático, por favor no respondas a este mensaje.
        </p>
    </div>
</body>
</html>
