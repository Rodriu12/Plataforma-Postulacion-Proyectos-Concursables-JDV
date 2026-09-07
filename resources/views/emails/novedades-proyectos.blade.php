<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nuevos Proyectos - JuntApp</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fbff; color: #333333; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e5e7eb;">
        
        <h2 style="color: #1d4ed8; text-align: center; margin-top: 0;">JuntApp - Yumbel Comunitaria</h2>
        <h3 style="color: #111827; border-bottom: 2px solid #eff6ff; padding-bottom: 10px;">Nuevos Fondos Disponibles</h3>
        
        <p style="color: #4b5563; font-size: 15px; line-height: 1.5;">Hola,</p>
        <p style="color: #4b5563; font-size: 15px; line-height: 1.5;">Te informamos sobre las últimas convocatorias a las que tu organización puede postular:</p>

        @foreach($proyectos as $proyecto)
            <div style="background-color: #f9fafb; border-left: 4px solid #3b82f6; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                <h4 style="margin: 0 0 8px 0; color: #1e3a8a; font-size: 16px;">
                    {{ $proyecto->nombre }}
                </h4>
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #4b5563; line-height: 1.4;">
                    {{ $proyecto->descripcion ?? 'Ingresa al sistema para ver los requisitos de esta convocatoria.' }}
                </p>
                <p style="margin: 0; font-size: 13px; font-weight: bold; color: #1d4ed8;">
                    Cierra el: {{ $proyecto->fecha_cierre ? \Carbon\Carbon::parse($proyecto->fecha_cierre)->format('d/m/Y') : 'Por definir' }}
                </p>
            </div>
        @endforeach

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/admin') }}" style="background-color: #1d4ed8; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Ingresar a la Plataforma</a>
        </div>

        <p style="text-align: center; font-size: 11px; color: #9ca3af; margin-top: 40px;">
            © {{ date('Y') }} JuntApp. Este es un correo automático, por favor no respondas a este mensaje.
        </p>
    </div>
</body>
</html>