<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Residencia</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; line-height: 1.6; margin: 40px; color: #333; }
        .header { text-align: center; margin-bottom: 50px; }
        .header h2 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; font-size: 14px; color: #555; }
        .title { text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 40px; text-decoration: underline; }
        .content { text-align: justify; font-size: 16px; margin-bottom: 60px; }
        .signature { text-align: center; margin-top: 120px; }
        .signature-line { border-top: 1px solid #000; width: 250px; margin: 0 auto; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Junta de Vecinos {{ $vecino->sector }}</h2>
        <p>Comuna de Yumbel, Región del Biobío</p>
    </div>

    <div class="title">CERTIFICADO DE RESIDENCIA</div>

    <div class="content">
        <p>La Directiva de la <strong>Junta de Vecinos {{ $vecino->sector }}</strong>, por medio del presente documento certifica que:</p>
        <br>
        <p>
            Don/Doña <strong>{{ $vecino->nombre }}</strong>, Cédula de Identidad (RUT) N° <strong>{{ $vecino->rut }}</strong>, es residente activo y tiene su domicilio registrado en <strong>{{ $vecino->direccion }}</strong>, perteneciente a nuestra jurisdicción vecinal en la comuna de Yumbel.
        </p>
        <br>
        <p>Se extiende el presente certificado a petición del interesado(a) para los fines que estime conveniente.</p>
    </div>

    <p style="text-align: right; margin-top: 50px;">
        Yumbel, {{ \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y') }}
    </p>

    <div class="signature">
        <div class="signature-line">
            <strong>La Directiva</strong><br>
            Junta de Vecinos {{ $vecino->sector }}
        </div>
    </div>
</body>
</html>