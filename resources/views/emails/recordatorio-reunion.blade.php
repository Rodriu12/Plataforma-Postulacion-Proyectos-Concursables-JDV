<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recordatorio de reunión - Vecindar</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; padding: 20px; margin: 0;">
    <div style="max-width: 620px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #d1d5db;">
        <h2 style="color: #1d4ed8; text-align: center; margin-top: 0;">Vecindar - Recordatorio de reunión</h2>

        <p style="font-size: 15px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
            @if ($tipo === 'dia')
                Te recordamos que mañana tendrás la reunión:<br>
            @else
                Te recordamos que la reunión comenzará en una hora:<br>
            @endif
        </p>

        <div style="background-color: #eff6ff; border-left: 4px solid #2563eb; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <p style="margin: 0 0 8px; font-size: 15px; color: #111827;"><strong>Título:</strong> {{ $reunion->titulo }}</p>
            <p style="margin: 0 0 8px; font-size: 15px; color: #111827;"><strong>Fecha y hora:</strong> {{ $reunion->fecha_hora->translatedFormat('d \d\e F \d\e\l Y a \l\a\s H:i') }}</p>
            <p style="margin: 0; font-size: 15px; color: #111827;"><strong>Lugar:</strong> {{ $reunion->lugar }}</p>
        </div>

        @if ($reunion->descripcion)
            <p style="font-size: 14px; line-height: 1.6; color: #4b5563; margin-bottom: 20px;">
                <strong>Detalles:</strong><br>
                {{ $reunion->descripcion }}
            </p>
        @endif

        <div style="text-align: center; margin-top: 28px;">
            <a href="{{ url('/admin') }}" style="background-color: #2563eb; color: #ffffff; padding: 12px 22px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Ver en la plataforma</a>
        </div>

        <p style="text-align: center; font-size: 11px; color: #6b7280; margin-top: 28px;">
            © {{ date('Y') }} Vecindar. Este es un correo automático, por favor no respondas a este mensaje.
        </p>
    </div>
</body>
</html>
