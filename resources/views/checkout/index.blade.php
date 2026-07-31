<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Checkout - Esencia Retro</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
          tailwind.config = {
            darkMode: "class",
            theme: {
              extend: {
                "colors": {
                    "primary": "#eabc4e",
                    "on-primary": "#3a2b00",
                    "primary-container": "#554100",
                    "on-primary-container": "#ffdea2",
                    "secondary": "#d0c5a0",
                    "on-secondary": "#353020",
                    "secondary-container": "#4c4634",
                    "on-secondary-container": "#ece5c0",
                    "tertiary": "#a1d0ba",
                    "on-tertiary": "#043725",
                    "tertiary-container": "#234e3b",
                    "on-tertiary-container": "#bceccf",
                    "error": "#ffb4ab",
                    "on-error": "#690005",
                    "error-container": "#93000a",
                    "on-error-container": "#ffdad6",
                    "background": "#000000",
                    "on-background": "#e4e2de",
                    "surface": "#000000",
                    "on-surface": "#e4e2de",
                    "surface-variant": "#1c2026",
                    "on-surface-variant": "#c8c6c0",
                    "outline": "#919089",
                    "outline-variant": "#46453f",
                    "surface-container-lowest": "#000000",
                    "surface-container-low": "#0a0a0a",
                    "surface-container": "#111111",
                    "surface-container-high": "#1a1a1a",
                    "surface-container-highest": "#242424",
                    "inverse-surface": "#e4e2de",
                    "inverse-on-surface": "#000000",
                    "inverse-primary": "#735c00"
                },
                "borderRadius": {
                    "DEFAULT": "0.5rem",
                    "lg": "0.75rem",
                    "xl": "1rem",
                    "2xl": "1.5rem",
                    "3xl": "2rem",
                    "full": "9999px"
                },
                "fontFamily": {
                    "headline": ["Inter"],
                    "body": ["Inter"],
                    "label": ["Inter"]
                }
              },
            },
          }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #000000; color: #e4e2de; }
        .glass-card {
            background: #111111;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;
        }
        .primary-gradient {
            background: linear-gradient(135deg, #eabc4e 0%, #8a6c1c 100%);
        }
        .keypad-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s;
        }
        .keypad-btn:hover {
            background: rgba(234, 188, 78, 0.1);
            border-color: rgba(234, 188, 78, 0.2);
        }
        .keypad-btn:active {
            transform: scale(0.95);
            background: rgba(234, 188, 78, 0.2);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(234, 188, 78, 0.2);
            border-radius: 10px;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #000000; }
        ::-webkit-scrollbar-thumb { background: #1a1a1a; border-radius: 10px; }
    </style>
</head>
<body class="min-h-screen bg-surface flex flex-col">
    <!-- Header -->
    <header class="p-6 flex items-center justify-between border-b border-white/5 bg-[#050505] sticky top-0 z-50">
        <div class="flex items-center gap-5">
            <a href="{{ route('admin.pedido', ['id' => $mesaId ?? '0']) }}" class="w-11 h-11 rounded-full border border-outline-variant/30 flex items-center justify-center hover:bg-surface-container-high transition-all hover:scale-105 active:scale-95 text-white hover:border-primary/40">
                <span class="material-symbols-outlined text-base">arrow_back</span>
            </a>
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/icon.png') }}" class="w-8 h-8 mix-blend-screen" alt="App Icon">
                <div class="flex flex-col">
                    <span class="text-xs font-black text-primary uppercase tracking-[0.2em] mb-0.5">Esencia Retro • Orden #{{ $factura->numero_orden ?? 'Pendiente' }}</span>
                    <span class="text-sm font-semibold text-white/90">Volver a Pedido</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-[10px] text-on-surface-variant uppercase font-black tracking-widest mb-0.5">Cajero en Turno</p>
                <p class="text-base font-bold text-white">{{ auth()->user()->name ?? 'Cajero' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-11 h-11 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-2xl">account_circle</span>
                </div>
                <!-- Logout Button -->
                <a href="{{ route('login') }}" class="w-11 h-11 rounded-2xl bg-error/10 border border-error/20 flex items-center justify-center text-error hover:bg-error hover:text-on-error transition-all group shadow-lg shadow-error/5" title="Cerrar Sesión">
                    <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">logout</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-[1200px] mx-auto w-full px-6 py-8 flex flex-col gap-8">
        
        <!-- Payment Section (Top) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Payment Inputs -->
            <section class="glass-card rounded-2xl p-8 shadow-xl flex flex-col gap-6">
                <div>
                    <h2 class="text-xl font-black text-white mb-1 tracking-tight">Método de Pago</h2>
                    <p class="text-slate-500 text-sm font-medium">Ingresa el monto recibido por cada método</p>
                </div>
                
                <div class="space-y-4">
                    <!-- Efectivo -->
                    <div class="flex items-center gap-4 bg-surface-container-highest/50 p-4 rounded-xl border border-white/5 focus-within:border-primary/50 transition-all">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center shadow-inner flex-shrink-0">
                            <span class="material-symbols-outlined text-white text-2xl">payments</span>
                        </div>
                        <div class="flex-1">
                            <label class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest block mb-1">Efectivo</label>
                            <div class="relative flex items-center">
                                <span class="text-white font-bold mr-2">$</span>
                                <input id="input-efectivo" type="text" inputmode="numeric" class="w-full bg-transparent text-xl font-black text-white border-none outline-none focus:ring-0 p-0 payment-input" placeholder="0.00" value="">
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta -->
                    <div class="flex items-center gap-4 bg-surface-container-highest/50 p-4 rounded-xl border border-white/5 focus-within:border-blue-400/50 transition-all">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center shadow-inner flex-shrink-0">
                            <span class="material-symbols-outlined text-blue-400 text-2xl">credit_card</span>
                        </div>
                        <div class="flex-1">
                            <label class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest block mb-1">Tarjeta</label>
                            <div class="relative flex items-center">
                                <span class="text-white font-bold mr-2">$</span>
                                <input id="input-tarjeta" type="text" inputmode="numeric" class="w-full bg-transparent text-xl font-black text-white border-none outline-none focus:ring-0 p-0 payment-input" placeholder="0.00" value="">
                            </div>
                        </div>
                    </div>

                    <!-- QR / Transferencia -->
                    <div class="flex items-center gap-4 bg-surface-container-highest/50 p-4 rounded-xl border border-white/5 focus-within:border-purple-400/50 transition-all">
                        <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center shadow-inner flex-shrink-0">
                            <span class="material-symbols-outlined text-purple-400 text-2xl">qr_code_scanner</span>
                        </div>
                        <div class="flex-1">
                            <label class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest block mb-1">QR / Transferencia</label>
                            <div class="relative flex items-center">
                                <span class="text-white font-bold mr-2">$</span>
                                <input id="input-qr" type="text" inputmode="numeric" class="w-full bg-transparent text-xl font-black text-white border-none outline-none focus:ring-0 p-0 payment-input" placeholder="0.00" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Summary -->
            <section class="glass-card rounded-2xl p-8 shadow-xl relative overflow-hidden flex flex-col justify-between">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-primary/10 blur-3xl rounded-full"></div>
                <div class="relative z-10 space-y-2">
                    <!-- Total -->
                    <div class="flex justify-between items-center pb-4 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-xl">receipt_long</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Total a Pagar</p>
                                <p class="text-xs text-slate-500">{{ $mesa->nombre ?? ('Mesa ' . $mesaId) }}</p>
                            </div>
                        </div>
                        <span id="total-to-pay-el" class="text-3xl font-black text-white tracking-tighter" data-total="{{ $total }}">${{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <!-- Monto Recibido -->
                    <div class="flex justify-between items-center py-4 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-emerald-400 text-xl">payments</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Monto Recibido</p>
                                <p class="text-xs text-slate-500">Total Ingresado</p>
                            </div>
                        </div>
                        <span id="received-amount-el" class="text-3xl font-black text-emerald-400 tracking-tighter">$0</span>
                    </div>

                    <!-- Cambio -->
                    <div class="flex justify-between items-center pt-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-xl">currency_exchange</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-primary uppercase tracking-widest">Cambio a Entregar</p>
                                <p class="text-xs text-slate-500">Efectivo</p>
                            </div>
                        </div>
                        <span id="change-amount-el" class="text-4xl font-black text-primary tracking-tighter">$0</span>
                    </div>
                </div>

                <div class="mt-8 pt-4">
                    <form id="checkout-form" action="{{ route('admin.checkout.pay', ['id' => $mesaId]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="monto_efectivo" id="hidden-efectivo" value="0">
                        <input type="hidden" name="monto_tarjeta" id="hidden-tarjeta" value="0">
                        <input type="hidden" name="monto_qr" id="hidden-qr" value="0">
                        
                        <button type="submit" class="w-full flex items-center justify-center primary-gradient text-on-primary py-5 rounded-2xl font-black text-lg tracking-[0.2em] shadow-2xl shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 active:translate-y-0.5 transition-all uppercase">
                            FINALIZAR PAGO
                        </button>
                    </form>
                </div>
            </section>
        </div>

        <!-- Order Items Table (Bottom) -->
        <section class="glass-card rounded-2xl p-8 shadow-xl">
            <h2 class="text-xl font-black text-white mb-6 tracking-tight">Productos Asignados a la Mesa</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/10 text-slate-400 text-xs uppercase tracking-widest">
                            <th class="py-3 font-medium">Producto</th>
                            <th class="py-3 font-medium text-center">Cantidad</th>
                            <th class="py-3 font-medium text-right">Precio Unit.</th>
                            <th class="py-3 font-medium text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($items as $item)
                            @php
                                $prod = $item->producto;
                                $precioTotal = $item->cantidad * $item->precio_unitario;
                            @endphp
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="py-4 font-bold text-white flex items-center gap-3">
                                    @if($prod && $prod->imagen_url)
                                        <div class="w-10 h-10 rounded-md bg-surface-container-highest overflow-hidden">
                                            <img src="{{ $prod->imagen_url }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    {{ $prod->nombre ?? $item->descripcion }}
                                </td>
                                <td class="py-4 text-center text-slate-300 font-medium">{{ intval($item->cantidad) }}</td>
                                <td class="py-4 text-right text-slate-300">${{ number_format($item->precio_unitario, 0, ',', '.') }}</td>
                                <td class="py-4 text-right font-bold text-white">${{ number_format($precioTotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="text-sm">
                            <td colspan="3" class="py-4 text-right text-slate-400 font-medium">Subtotal</td>
                            <td class="py-4 text-right font-bold text-white">${{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const total = parseFloat(document.getElementById('total-to-pay-el').dataset.total);
            const inputEfectivo = document.getElementById('input-efectivo');
            const inputTarjeta = document.getElementById('input-tarjeta');
            const inputQr = document.getElementById('input-qr');
            
            const hiddenEfectivo = document.getElementById('hidden-efectivo');
            const hiddenTarjeta = document.getElementById('hidden-tarjeta');
            const hiddenQr = document.getElementById('hidden-qr');

            const receivedEl = document.getElementById('received-amount-el');
            const changeEl = document.getElementById('change-amount-el');

            function parseAmount(val) {
                if (!val) return 0;
                // Para evitar que "300.000" se convierta en 300, quitamos los puntos y comas.
                // En Colombia no se usan decimales en la caja, así que "300.000" es "300000".
                let clean = String(val).replace(/[.,]/g, '');
                return parseFloat(clean) || 0;
            }

            function updateChange() {
                const valEfectivo = parseAmount(inputEfectivo.value);
                const valTarjeta = parseAmount(inputTarjeta.value);
                const valQr = parseAmount(inputQr.value);
                
                const received = valEfectivo + valTarjeta + valQr;
                receivedEl.textContent = '$' + new Intl.NumberFormat('es-CO').format(received);
                
                // Change is normally only given from cash, but for simplicity here we compute overall change
                const change = Math.max(0, received - total);
                changeEl.textContent = '$' + new Intl.NumberFormat('es-CO').format(change);
                
                // Update hidden inputs
                hiddenEfectivo.value = valEfectivo;
                hiddenTarjeta.value = valTarjeta;
                hiddenQr.value = valQr;
            }

            function formatNumberInput(e) {
                let raw = String(e.target.value).replace(/[^\d]/g, '');
                if (raw === '') {
                    e.target.value = '';
                    return;
                }
                e.target.value = new Intl.NumberFormat('es-CO').format(parseInt(raw, 10));
            }

            [inputEfectivo, inputTarjeta, inputQr].forEach(input => {
                input.addEventListener('input', (e) => {
                    formatNumberInput(e);
                    updateChange();
                });
            });

            const checkoutForm = document.getElementById('checkout-form');
            if (checkoutForm) {
                const items = @json($items->map(fn($item) => ['cantidad' => intval($item->cantidad), 'nombre' => $item->producto->nombre ?? $item->descripcion, 'total' => $item->cantidad * $item->precio_unitario]));
                const orderNumber = '{{ $factura->numero_orden ?? str_pad($factura->id, 4, "0", STR_PAD_LEFT) }}';
                const mesaNombre = '{{ $mesa->nombre ?? "POS" }}';
                
                @php
                    $logoPath = public_path('img/logo-bw.png');
                    $logoBase64 = '';
                    if (file_exists($logoPath)) {
                        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                    }
                @endphp
                const logoSrc = '{!! $logoBase64 !!}';

                checkoutForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const valEfectivo = parseAmount(inputEfectivo.value);
                    const valTarjeta = parseAmount(inputTarjeta.value);
                    const valQr = parseAmount(inputQr.value);
                    const received = valEfectivo + valTarjeta + valQr;
                    
                    if (received < total) {
                        alert('El monto recibido es menor al total a pagar.');
                        return;
                    }

                    if(confirm('¿Desea imprimir el ticket de la factura cobrada?')) {
                        const printWindow = window.open('', '_blank', 'width=400,height=600');
                        
                        let itemsHtml = '';
                        items.forEach(item => {
                            itemsHtml += `
                                <tr>
                                    <td class="col-qty">${item.cantidad}</td>
                                    <td class="col-desc">${item.nombre}</td>
                                    <td class="col-total">$${new Intl.NumberFormat('es-CO').format(item.total)}</td>
                                </tr>
                            `;
                        });

                        let pagosHtml = '';
                        if (valEfectivo > 0) pagosHtml += `<tr><td class="text-left uppercase">EFECTIVO</td><td class="text-right">$${new Intl.NumberFormat('es-CO').format(valEfectivo)}</td></tr>`;
                        if (valTarjeta > 0) pagosHtml += `<tr><td class="text-left uppercase">TARJETA</td><td class="text-right">$${new Intl.NumberFormat('es-CO').format(valTarjeta)}</td></tr>`;
                        if (valQr > 0) pagosHtml += `<tr><td class="text-left uppercase">QR / TRANSF</td><td class="text-right">$${new Intl.NumberFormat('es-CO').format(valQr)}</td></tr>`;

                        let cambioHtml = '';
                        const change = Math.max(0, received - total);
                        if (change > 0) {
                            cambioHtml = `<tr><td class="text-left uppercase font-bold mt-2">CAMBIO</td><td class="text-right font-bold mt-2">$${new Intl.NumberFormat('es-CO').format(change)}</td></tr>`;
                        }

                        const dateStr = new Date().toLocaleString('es-CO', {day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit'});

                        const html = `
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket #${orderNumber}</title>
    <style>
        @page { margin: 0; padding: 0; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 10px; color: #000; margin: 0 auto; padding: 5px; width: 200px; }
        .text-center { text-align: center; } .text-right { text-align: right; } .text-left { text-align: left; }
        .font-bold { font-weight: bold; } .uppercase { text-transform: uppercase; }
        .mb-1 { margin-bottom: 5px; } .mb-2 { margin-bottom: 10px; } .mt-2 { margin-top: 10px; }
        .divider { border-bottom: 1px dashed #000; margin: 5px 0; }
        .logo { max-width: 170px; width: 100%; height: auto; margin: -10px auto -10px auto; display: block; object-fit: contain; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 2px 0; vertical-align: top; }
        .col-qty { width: 15%; } .col-desc { width: 55%; } .col-total { width: 30%; text-align: right; }
    </style>
</head>
<body>
    <div class="text-center mb-2">
        <img src="${logoSrc}" class="logo" alt="Logo">
        <div class="font-bold uppercase">ESENCIA RETRO</div>
        <div>NIT: 1,007,450,540</div>
        <div>Tel: 3162218491 - 3209180085</div>
        <div>Ciudad Soacha San Mateo</div>
        <div>Correo: esenciaretro10@gmail.com</div>
    </div>
    <div class="divider"></div>
    <div class="mb-2">
        <div><span class="font-bold">Ticket:</span> #${orderNumber}</div>
        <div><span class="font-bold">Fecha:</span> ${dateStr}</div>
        <div><span class="font-bold">Mesa:</span> ${mesaNombre}</div>
    </div>
    <div class="divider"></div>
    <table class="mb-2">
        <thead>
            <tr><th class="text-left col-qty">Cant</th><th class="text-left col-desc">Producto</th><th class="text-right col-total">Total</th></tr>
        </thead>
        <tbody>
            ${itemsHtml}
        </tbody>
    </table>
    <div class="divider"></div>
    <table class="mb-2 font-bold">
        <tr><td class="text-left uppercase">Total a Pagar</td><td class="text-right">$${new Intl.NumberFormat('es-CO').format(total)}</td></tr>
    </table>
    <div class="divider"></div>
    <table class="mb-2">
        ${pagosHtml}
        ${cambioHtml}
    </table>
    <div class="divider"></div>
    <div class="text-center mt-2">
        <div class="font-bold uppercase mb-1">¡Gracias por su visita!</div>
        <div>Vuelva pronto</div>
    </div>
    <script>
        window.onload = function() { window.print(); }
        window.onafterprint = function() { window.close(); }
    <\/script>
</body>
</html>`;
                        printWindow.document.open();
                        printWindow.document.write(html);
                        printWindow.document.close();

                        showLoader('Procesando pago...');
                        this.submit();
                    } else {
                        showLoader('Procesando pago...');
                        this.submit();
                    }
                });
            }
        });

        // Global Loading Screen Functions for Checkout
        function showLoader(text = 'Procesando...') {
            const loader = document.getElementById('global-loader');
            const loaderText = document.getElementById('global-loader-text');
            if (loader) {
                if (loaderText) loaderText.textContent = text;
                loader.classList.remove('opacity-0', 'pointer-events-none');
                loader.classList.add('opacity-100');
            }
        }
    </script>

    <!-- Global Loading Screen Overlay -->
    <div id="global-loader" class="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-black/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div class="flex flex-col items-center gap-6 p-8 rounded-3xl bg-[#0f0f0f]/95 border border-white/10 shadow-2xl relative overflow-hidden group max-w-xs w-full text-center">
            <!-- Background glow -->
            <div class="absolute -inset-10 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-all duration-500"></div>
            
            <div class="relative">
                <!-- Dual spin rings -->
                <div class="w-16 h-16 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                <div class="absolute inset-1 w-14 h-14 border-4 border-transparent border-t-[#8a6c1c] rounded-full animate-spin [animation-duration:0.8s] [animation-direction:reverse]"></div>
                <!-- Brand logo center or small dot -->
                <div class="absolute inset-0 m-auto w-3 h-3 bg-primary rounded-full animate-ping"></div>
            </div>
            
            <div class="space-y-1 relative z-10">
                <p id="global-loader-text" class="text-base font-black text-white tracking-tight">Procesando...</p>
                <p class="text-[10px] uppercase font-bold text-primary tracking-widest opacity-80">Esencia Retro</p>
            </div>
        </div>
    </div>

    <!-- Decorative Background Elements -->
    <div class="fixed top-[-10%] right-[-10%] w-[800px] h-[800px] bg-primary/5 rounded-full blur-[150px] -z-10"></div>
    <div class="fixed bottom-[-10%] left-[-10%] w-[700px] h-[700px] bg-primary/3 rounded-full blur-[150px] -z-10"></div>
</body>
</html>
