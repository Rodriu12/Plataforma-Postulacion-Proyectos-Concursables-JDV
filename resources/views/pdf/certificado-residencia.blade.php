@php
    $longitudNombreOrganizacion = mb_strlen($nombreOrganizacion);
    $tamanoNombreTimbre = match (true) {
        $longitudNombreOrganizacion > 55 => '6.5pt',
        $longitudNombreOrganizacion > 42 => '7.2pt',
        $longitudNombreOrganizacion > 30 => '8pt',
        default => '9pt',
    };
    $fechaActual = \Carbon\Carbon::now()->locale('es');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Residencia</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.9cm 2.0cm 1.7cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #202020;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11.5pt;
            line-height: 1.55;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 42px;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .organization-block {
            width: 48%;
            padding-right: 12px !important;
            font-size: 9.5pt;
            line-height: 1.4;
        }

        .organization-name {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            overflow-wrap: break-word;
        }

        .organization-meta {
            margin: 4px 0 0;
            color: #444;
            font-size: 8.5pt;
        }

        .title-block {
            width: 52%;
            text-align: right;
        }

        .document-title {
            display: inline-block;
            border-bottom: 1px solid #202020;
            padding-bottom: 3px;
            font-size: 14pt;
            line-height: 1.25;
            font-weight: bold;
            white-space: nowrap;
        }

        .opening {
            margin: 0 0 20px;
            text-align: justify;
        }

        .resident-details {
            margin: 0 0 20px;
            text-align: justify;
            line-height: 1.8;
        }

        .closing {
            margin: 0;
            text-align: justify;
        }

        .date {
            margin-top: 35px;
            text-align: right;
            font-size: 10.5pt;
        }

        .stamp-area {
            margin-top: 37px;
            text-align: center;
            page-break-inside: avoid;
        }

        .stamp {
            width: 158px;
            height: 158px;
            margin: 0 auto;
            padding: 25px 14px 12px;
            border: 3px double #244477;
            border-radius: 50%;
            color: #244477;
            text-align: center;
            line-height: 1.18;
            overflow: hidden;
        }

        .stamp-inner-border {
            border: 1px solid #244477;
            border-radius: 50%;
            height: 100%;
            padding: 9px 5px 6px;
        }

        .stamp-organization {
            font-size: {{ $tamanoNombreTimbre }};
            font-weight: bold;
            text-transform: uppercase;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .stamp-rule {
            width: 58%;
            margin: 5px auto;
            border-top: 1px solid #244477;
        }

        .stamp-certifies {
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .stamp-document-type {
            margin-top: 2px;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .stamp-location {
            margin-top: 5px;
            font-size: 7pt;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="organization-block">
                <div class="organization-name">{{ $nombreOrganizacion }}</div>
                @if ($sectorOrganizacion)
                    <div>{{ $sectorOrganizacion }}</div>
                @endif
                <div>Comuna de {{ $comuna }}, Región del Biobío</div>
                @if ($organizacion?->rut_juridico || $organizacion?->fecha_constitucion)
                    <p class="organization-meta">
                        @if ($organizacion?->rut_juridico)
                            RUT jurídico: {{ $organizacion->rut_juridico }}
                        @endif
                        @if ($organizacion?->fecha_constitucion)
                            @if ($organizacion?->rut_juridico) · @endif
                            Constituida el {{ \Carbon\Carbon::parse($organizacion->fecha_constitucion)->format('d/m/Y') }}
                        @endif
                    </p>
                @endif
            </td>
            <td class="title-block">
                <div class="document-title">CERTIFICADO DE RESIDENCIA</div>
            </td>
        </tr>
    </table>

    <p class="opening">
        La directiva de <strong>{{ $nombreOrganizacion }}</strong>@if ($sectorOrganizacion), del sector
        <strong>{{ $sectorOrganizacion }}</strong>@endif, comuna de {{ $comuna }}, certifica que:
    </p>

    <p class="resident-details">
        Don/Doña <strong>{{ $vecino->nombre }}</strong>, Cédula de Identidad N.º
        <strong>{{ $vecino->rut }}</strong>, reside en el domicilio registrado en
        <strong>{{ $vecino->direccion }}</strong>, sector/localidad
        <strong>{{ $vecino->sector }}</strong>, comuna de {{ $comuna }}, y pertenece a la jurisdicción vecinal de esta organización.
    </p>

    <p class="closing">
        Se extiende el presente certificado a solicitud de la persona interesada para fines laborales.
    </p>

    <p class="date">
        {{ $comuna }}, {{ $fechaActual->translatedFormat('d \\d\\e F \\d\\e Y') }}.
    </p>

    <div class="stamp-area">
        <div class="stamp">
            <div class="stamp-inner-border">
                <div class="stamp-organization">{{ $nombreOrganizacion }}</div>
                <div class="stamp-rule"></div>
                <div class="stamp-certifies">CERTIFICA</div>
                <div class="stamp-document-type">RESIDENCIA</div>
                <div class="stamp-location">Comuna de {{ $comuna }}</div>
            </div>
        </div>
    </div>
</body>
</html>
