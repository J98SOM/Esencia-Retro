<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket #{{ $factura->numero_orden ?? '0000' }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            margin: 0 auto;
            padding: 10px;
            width: 300px; /* 80mm approx */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mt-2 { margin-top: 10px; }
        .divider { border-bottom: 1px dashed #000; margin: 5px 0; }
        .logo { width: 180px; margin: 0 auto -15px; display: block; filter: grayscale(100%); }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 2px 0; vertical-align: top; }
        .col-qty { width: 15%; }
        .col-desc { width: 55%; }
        .col-total { width: 30%; text-align: right; }
        
        @media print {
            body { width: 100%; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="text-center mb-2">
        @php
            $logoPath = public_path('img/logo.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }
        @endphp
        <img src="{!! $logoBase64 !!}" class="logo" alt="Logo">
        <div class="font-bold uppercase">ESENCIA RETRO</div>
        <div>NIT: 1,007,450,540</div>
        <div>Tel: 3162218491 - 3209180085</div>
        <div>Ciudad Bogotá</div>
        <div>Correo: esenciaretro10@gmail.com</div>
    </div>

    <div class="divider"></div>

    <div class="mb-2">
        <div><span class="font-bold">Ticket:</span> #{{ $factura->numero_orden ?? str_pad($factura->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div><span class="font-bold">Fecha:</span> {{ $factura->created_at ? $factura->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</div>
        <div><span class="font-bold">Mesa:</span> {{ $factura->mesa->nombre ?? 'POS' }}</div>
    </div>

    <div class="divider"></div>

    <table class="mb-2">
        <thead>
            <tr>
                <th class="text-left col-qty">Cant</th>
                <th class="text-left col-desc">Producto</th>
                <th class="text-right col-total">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->productos as $pxf)
                @php
                    $prodName = $pxf->producto->nombre ?? $pxf->descripcion;
                    $totalProd = $pxf->cantidad * $pxf->precio_unitario;
                @endphp
                <tr>
                    <td class="col-qty">{{ intval($pxf->cantidad) }}</td>
                    <td class="col-desc">{{ $prodName }}</td>
                    <td class="col-total">${{ number_format($totalProd, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="mb-2 font-bold">
        <tr>
            <td class="text-left uppercase">Total a Pagar</td>
            <td class="text-right">${{ number_format($factura->monto_total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    
    <table class="mb-2">
        @foreach($factura->metodosPago as $mp)
            <tr>
                <td class="text-left uppercase">{{ $mp->metodo }}</td>
                <td class="text-right">${{ number_format($mp->valor, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        @if($factura->cambio > 0)
        <tr>
            <td class="text-left uppercase font-bold mt-2">Cambio</td>
            <td class="text-right font-bold mt-2">${{ number_format($factura->cambio, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <div class="text-center mt-2">
        <div class="font-bold uppercase mb-1">¡Gracias por su visita!</div>
        <div>Vuelva pronto</div>
        <div class="mt-2 text-center no-print">
            <button onclick="window.print()" style="padding: 10px; cursor:pointer; background: #000; color: #fff; border:none; border-radius: 5px;">Imprimir Copia</button>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
        // Cierra la pestaña después de imprimir (opcional, dependiendo del navegador)
        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>
</html>
