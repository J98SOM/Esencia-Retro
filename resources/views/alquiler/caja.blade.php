@extends('layouts.admin')

@section('title', 'Esencia Retro - Caja')

@push('styles')
    @vite('resources/css/alquiler.css')
    <style>
        .caja-top { display:flex; gap:1rem; align-items:center; justify-content:space-between; }
        .caja-logo { display:flex; gap:1rem; align-items:center; }
        .caja-summary { display:flex; gap:1rem; align-items:center; }
        .summary-box { background:rgba(255,255,255,0.03); padding:1rem; border-radius:.75rem; min-width:160px; }
        .payments { display:flex; gap:.75rem; flex-direction:column;  }
        .payments input { width:180px; }
        /* Table inputs: high-contrast for dark background */
        table.caja-table { width:100%; border-collapse: separate; border-spacing: 0; }
        table.caja-table thead th { background: rgba(255,255,255,0.04) !important; color: #ffffff !important; padding: .75rem; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.06); }
        table.caja-table thead th:first-child { padding-left: .75rem; }
        table.caja-table tbody tr { background: rgba(255,255,255,0.01); border-bottom: 1px solid rgba(255,255,255,0.03); }
        table.caja-table tbody tr:nth-child(odd) { background: rgba(255,255,255,0.015); }
        table.caja-table tbody tr:hover { background: rgba(255,255,255,0.03); }
        table.caja-table th, table.caja-table td { color: #ffffff !important; }

        table.caja-table input, table.caja-table select {
            color: #ffffff !important;
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.08) !important;
            padding: .45rem .5rem !important;
            border-radius: .375rem !important;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.01);
        }
        table.caja-table input::placeholder { color: rgba(255,255,255,0.55) !important; }
        table.caja-table input[type="number"] { text-align: right; }
        table.caja-table .caja-sub { color: #ffffff !important; font-weight: 700; }

        /* Make remove button visible and consistent */
        .remove-row { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.08); padding: .2rem .5rem; border-radius: .375rem; font-size: .9rem; }
        .remove-row.hidden { display: none !important; }
        /* Ensure payment controls are readable on dark background */
        .payment-method, .payment-amount { color: #ffffff !important; }
        .payment-method option { color: #000000; }
        .payment-amount::placeholder { color: rgba(255,255,255,0.6); }
        /* Small legend for available payment methods */
        .payments-legend { color: #ffffff; font-size: 0.85rem; margin-bottom: .25rem; opacity: .9; }
        table.caja-table td, table.caja-table th { padding:.5rem; }
        /* Autocomplete dropdown */
        .caja-dd { position: absolute; background: #0b1220; border: 1px solid rgba(255,255,255,0.06); color: #fff; z-index: 1200; max-height: 220px; overflow-y: auto; border-radius: .375rem; box-shadow: 0 6px 18px rgba(0,0,0,0.6); }
        .caja-dd .item { padding: .45rem .6rem; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.02); }
        .caja-dd .item:hover { background: rgba(255,255,255,0.02); }
        /* Table scroll area: limit height and scroll only the table body */
        .caja-table-wrap { max-height: calc(100vh - 360px); overflow-y: auto; }
        .caja-table thead th { position: sticky; top: 0; z-index: 5; }
    </style>
@endpush

@section('content')
<div class="p-6 lg:p-8 flex-1">
    
    @if(!$activeCaja)
        <!-- APERTURA DE CAJA (Bloqueante si no hay caja abierta) -->
        <div class="max-w-lg mx-auto my-12 bg-surface-container-low border border-white/5 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 border border-primary/20 shadow-lg shadow-primary/5">
                    <span class="material-symbols-outlined text-3xl">point_of_sale</span>
                </div>
                <h3 class="text-2xl font-black text-white">Apertura de Caja</h3>
                <p class="text-sm text-slate-400 mt-2">Registra el monto inicial en caja y asigna el trabajador responsable</p>
            </div>

            @if(session('error'))
                <div class="mb-5 p-4 rounded-xl bg-error/10 border border-error/20 text-error text-sm font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">error</span>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.caja.apertura') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 mb-2">Trabajador Responsable</label>
                    <input type="text" name="trabajador" required placeholder="Escribe el nombre del trabajador..." class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all text-sm">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 mb-2">Monto Inicial en Efectivo ($)</label>
                    <input type="number" name="monto_inicial" min="0" step="0.01" required placeholder="0.00" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all font-bold text-lg">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 mb-2">Notas / Observaciones (Opcional)</label>
                    <textarea name="notas" placeholder="Escribe observaciones iniciales si las hay..." class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all text-sm h-24 resize-none"></textarea>
                </div>

                <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-black shadow-lg shadow-primary/10 hover:scale-[0.98] transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                    <span class="material-symbols-outlined text-lg">lock_open</span>
                    Abrir Caja e Iniciar Turno
                </button>
            </form>
        </div>
    @else
        <!-- VISTA DE CAJA POS (Con caja activa) -->
        <header class="mb-6 no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Caja - Registrar Productos</h2>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <p class="text-xs text-slate-400">
                        Turno asignado a: <strong class="text-white">{{ $activeCaja->trabajador }}</strong> | 
                        Monto inicial: <strong class="text-primary">${{ number_format($activeCaja->monto_inicial, 2) }}</strong> | 
                        Apertura: <span class="text-slate-300">{{ $activeCaja->fecha_apertura->format('d/m/Y H:i') }}</span>
                    </p>
                </div>
            </div>
            <div>
                <button onclick="openModal('modal-cierre-caja')" class="px-4 py-2.5 rounded-xl bg-error/10 border border-error/20 text-error hover:bg-error hover:text-white transition-all flex items-center gap-2 text-sm font-bold shadow-lg shadow-error/5">
                    <span class="material-symbols-outlined text-sm">lock</span>
                    Cerrar Turno / Caja
                </button>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-5 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if($cajaPreload)
            <!-- VISTA DE REGISTRO DE VENTA (POS) - Sólo visible al facturar una mesa/orden -->
            <div class="bg-surface-container-low border border-white/5 rounded-2xl overflow-hidden shadow-2xl p-6 mb-8">
                <div class="caja-top mb-6">
                    <div class="caja-logo">
                        <img src="{{ asset('img/logo.png') }}" alt="logo" class="w-20 h-20 object-contain">
                        <div>
                            <h3 class="text-white font-black">Esencia Retro - Caja</h3>
                            <p class="text-on-surface-variant text-sm">Registra productos y pagos</p>
                            <div class="text-on-surface-variant text-xs mt-2">
                                <div>NIT: 1,007,450,540</div>
                                <div>Tel: 3162218491 - 3209180085</div>
                                <div>Ciudad: Bogotá</div>
                                <div>Correo: esenciaretro10@gmail.com</div>
                            </div>
                            <div class="mt-3">
                                <label class="text-on-surface-variant text-xs">No. Orden</label>
                                <input id="caja-factura-no" type="text" readonly data-auto-generated="1" class="mt-1 px-2 py-1 rounded bg-surface-container-highest text-white text-sm" value="{{ ($nextInvoiceNo ?? '') ?: '0001' }}">
                            </div>
                        </div>
                    </div>

                    <div class="caja-summary">
                        <div class="summary-box">
                            <div class="text-on-surface-variant text-xs">Total</div>
                            <div id="caja-total" class="text-white text-2xl font-black">$0</div>
                        </div>
                        <div class="summary-box">
                            <div class="text-on-surface-variant text-xs">Total recibido</div>
                            <div id="caja-recibido" class="text-white text-2xl font-black">$0</div>
                        </div>
                        <div class="summary-box">
                            <div class="text-on-surface-variant text-xs">Vueltas</div>
                            <div id="caja-vueltas" class="text-white text-xl font-bold">$0</div>
                        </div>
                    </div>

                    <div class="payments">
                        <div class="payments-legend">Ingrese montos por método</div>
                        <div class="grid grid-cols-1 gap-2">
                            <div class="flex items-center gap-2">
                                <label class="text-on-surface-variant text-sm w-24">Efectivo</label>
                                <input id="p-efectivo" type="number" min="0" step="0.01" class="px-3 py-2 rounded-lg bg-surface-container-highest text-white" placeholder="0">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-on-surface-variant text-sm w-24">Tarjeta</label>
                                <input id="p-tarjeta" type="number" min="0" step="0.01" class="px-3 py-2 rounded-lg bg-surface-container-highest text-white" placeholder="0">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-on-surface-variant text-sm w-24">QR</label>
                                <input id="p-qr" type="number" min="0" step="0.01" class="px-3 py-2 rounded-lg bg-surface-container-highest text-white" placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <button id="add-row" class="px-4 py-2 rounded-lg bg-primary/10 border border-primary/20 text-primary">Agregar fila</button>
                        <button id="btn-guardar-caja" class="ml-3 px-4 py-2 rounded-lg bg-surface-container-highest border border-white/10 text-white">Guardar</button>
                        <button id="btn-imprimir-ticket" class="ml-3 px-4 py-2 rounded-lg bg-primary/10 border border-primary/20 text-primary">Imprimir Ticket</button>
                    </div>
                </div>

                <div class="overflow-x-auto caja-table-wrap">
                    <table class="w-full text-sm caja-table">
                        <thead>
                            <tr class="bg-surface-container-high border-b border-white/10">
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="caja-body">
                            <!-- La tabla inicia limpia. Usa 'Agregar fila' para añadir ítems manualmente -->
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- VISTA DE REPORTE DEL TURNO ACTIVO - Visible por defecto -->
            @php
                $efectivoVentas = 0;
                foreach($ventasMetodos as $v) {
                    if (strtolower($v->metodo) === 'efectivo') {
                        $efectivoVentas = $v->total;
                    }
                }
                $totalEsperado = $activeCaja->monto_inicial + $efectivoVentas;
            @endphp
            <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6 shadow-2xl mb-8">
                <div class="mb-5 flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-white/5 pb-4">
                    <div>
                        <h3 class="text-xl font-black text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">receipt_long</span>
                            Productos Vendidos en el Turno
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">Listado consolidado de todos los productos vendidos desde la apertura de caja</p>
                    </div>
                    <div class="p-3 bg-surface-container-high border border-white/5 rounded-xl text-right text-xs">
                        <span class="text-slate-400">Total Esperado en Caja:</span>
                        <strong class="text-primary font-black text-sm ml-1">${{ number_format($totalEsperado, 2) }}</strong>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm caja-table">
                        <thead>
                            <tr class="bg-surface-container-high border-b border-white/10 text-left">
                                <th class="p-3">ID</th>
                                <th class="p-3">Nombre</th>
                                <th class="p-3 text-right">Cantidad</th>
                                <th class="p-3 text-right">Precio Unitario Promedio</th>
                                <th class="p-3 text-right">Subtotal Recaudado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productosVendidos as $prod)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="p-3 font-semibold text-slate-300">#{{ $prod->producto_id ?? 'N/A' }}</td>
                                    <td class="p-3 text-white font-bold">{{ $prod->producto_nombre }}</td>
                                    <td class="p-3 text-right text-slate-300 font-semibold">{{ (int)$prod->cantidad_total }}</td>
                                    <td class="p-3 text-right text-slate-300">${{ number_format($prod->total_valor / max(1, $prod->cantidad_total), 2) }}</td>
                                    <td class="p-3 text-right text-primary font-black">${{ number_format($prod->total_valor, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-500 italic">No se han vendido productos en este turno.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection

@push('modals')
    @if($activeCaja)
        <!-- MODAL DE CIERRE DE CAJA -->
        <div id="modal-cierre-caja" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300">
            <h3 class="text-2xl font-black text-white mb-2">Cierre de Caja</h3>
            <p class="text-sm text-slate-400 mb-6">Revisa el arqueo del turno actual antes de confirmar el cierre.</p>
            
            <div class="p-4 rounded-xl bg-surface-container-high border border-white/5 space-y-3 mb-6">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Trabajador asignado:</span>
                    <span class="font-bold text-white">{{ $activeCaja->trabajador }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Apertura:</span>
                    <span class="text-white">{{ $activeCaja->fecha_apertura->format('d/m/Y H:i') }}</span>
                </div>
                <hr class="border-white/5">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Monto Inicial:</span>
                    <span class="font-bold text-white">${{ number_format($activeCaja->monto_inicial, 2) }}</span>
                </div>
                
                @php
                    $efectivoVentas = 0;
                    $otrasVentas = 0;
                    foreach($ventasMetodos as $v) {
                        if (strtolower($v->metodo) === 'efectivo') {
                            $efectivoVentas = $v->total;
                        } else {
                            $otrasVentas += $v->total;
                        }
                    }
                    $totalEsperado = $activeCaja->monto_inicial + $efectivoVentas;
                @endphp

                @foreach($ventasMetodos as $v)
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Ventas ({{ ucfirst($v->metodo) }}):</span>
                        <span class="font-semibold text-white">${{ number_format($v->total, 2) }}</span>
                    </div>
                @endforeach

                <hr class="border-white/5">
                <div class="flex justify-between text-sm">
                    <span class="text-primary font-bold">Efectivo Esperado en Caja:</span>
                    <span class="text-primary font-black">${{ number_format($totalEsperado, 2) }}</span>
                </div>
            </div>

            <!-- Listado de Productos Vendidos -->
            <div class="mb-6 p-4 rounded-xl bg-surface-container-high border border-white/5">
                <h4 class="text-xs uppercase tracking-widest font-bold text-slate-400 mb-3 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">receipt_long</span>
                    Productos Vendidos
                </h4>
                <div class="max-h-36 overflow-y-auto pr-1 space-y-2 custom-scrollbar">
                    @forelse($productosVendidos as $prod)
                        <div class="flex justify-between text-xs py-1 border-b border-white/5">
                            <span class="text-slate-300">{{ $prod->producto_nombre }} <span class="text-slate-500 font-semibold">x{{ $prod->cantidad_total }}</span></span>
                            <span class="font-bold text-white">${{ number_format($prod->total_valor, 2) }}</span>
                        </div>
                    @empty
                        <div class="text-xs text-slate-500 italic text-center py-2">No se vendieron productos en este turno.</div>
                    @endforelse
                </div>
            </div>

            <form action="{{ route('admin.caja.cierre') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 mb-2">Monto Real en Caja ($)</label>
                    <input type="number" name="monto_final" min="0" step="0.01" required placeholder="0.00" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all font-bold text-lg">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 mb-2">Notas / Novedades</label>
                    <textarea name="notas" placeholder="Escribe diferencias o notas sobre el cierre..." class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all text-sm h-20 resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-4 border-t border-white/10">
                    <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-error text-on-error hover:bg-error-container transition-all font-bold text-sm">Cerrar Turno</button>
                </div>
            </form>
        </div>
    @endif
@endpush

@push('scripts')
    <script src="/js/pos-printer.js"></script>
    @php
        // Prefer inlining the local source when present to avoid Vite/dev-server 404s in development environments.
        // Prefer a pre-copied public file if present to avoid inlining issues
        if (file_exists(public_path('js/pages/caja.inline.js'))) {
            echo '<script src="' . asset('js/pages/caja.inline.js') . '"></script>';
        } elseif (file_exists(resource_path('js/pages/caja.js'))) {
            $__c = file_get_contents(resource_path('js/pages/caja.js'));
            // strip BOM if present and escape closing script tags to avoid truncation
            $__c = preg_replace('/^\xEF\xBB\xBF/', '', $__c);
            $__c = str_replace('</script>', '<\/script>', $__c);
            echo '<script>' . PHP_EOL . $__c . PHP_EOL . '</script>';
        } else {
            if (function_exists('vite')) {
                try {
                    echo vite('resources/js/pages/caja.js');
                } catch (\Illuminate\Foundation\ViteException $e) {
                    if (file_exists(public_path('build/manifest.json'))) {
                        echo '<script type="module" src="' . asset('js/pages/caja.js') . '"></script>';
                    } else {
                        echo '<script type="module" src="http://localhost:5173/resources/js/pages/caja.js"></script>';
                    }
                }
            } else {
                if (file_exists(public_path('build/manifest.json'))) {
                    echo '<script type="module" src="' . asset('js/pages/caja.js') . '"></script>';
                } else {
                    // Last resort: point to vite dev server
                    echo '<script type="module" src="http://localhost:5173/resources/js/pages/caja.js"></script>';
                }
            }
        }
    @endphp

    <script>
        window.COMPANY = {
            name: 'ESENCIA RETRO',
            nit: '1,007,450,540',
            rut: '',
            phone: '3162218491 - 3209180085',
            city: 'Bogotá',
            email: 'esenciaretro10@gmail.com',
            address: ''
        };
        window.NEXT_INVOICE_NO = '{{ ($nextInvoiceNo ?? '') ?: '0001' }}';
        window.CAJA_PRELOAD = {!! json_encode($cajaPreload ?? null) !!};
        window.ALL_PRODUCTS = {!! json_encode(\App\Models\Producto::all(['id', 'nombre', 'precio'])) !!};
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.CAJA_PRELOAD && Array.isArray(window.CAJA_PRELOAD.items) && window.CAJA_PRELOAD.items.length) {
                const products = window.ALL_PRODUCTS || [];
                const byId = {};
                products.forEach(p => byId[p.id] = p);
                
                const tbody = document.getElementById('caja-body');
                if (!tbody) return;
                tbody.innerHTML = '';
                let total = 0;
                
                window.CAJA_PRELOAD.items.forEach(it => {
                    const prod = byId[it.producto_id] || { id: it.producto_id, nombre: '', precio: 0 };
                    const qty = parseInt(it.cant, 10) || 1;
                    const price = parseFloat(prod.precio) || 0;
                    const subtotal = price * qty;
                    total += subtotal;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td><input class="caja-id text-sm" value="${prod.id}"></td>
                        <td><input class="caja-name w-full text-sm" value="${prod.nombre}" placeholder="Nombre del producto"></td>
                        <td><input type="number" min="0" step="1" class="caja-cant" value="${qty}"></td>
                        <td><div class="caja-price" data-price="${price}">$${price.toFixed(2)}</div></td>
                        <td class="caja-sub">$${subtotal.toFixed(2)}</td>
                        <td><input type="button" class="remove-row" value="Quitar"></td>
                    `;
                    tbody.appendChild(tr);
                });

                const totalEl = document.getElementById('caja-total');
                if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
                const recibidoEl = document.getElementById('caja-recibido');
                if (recibidoEl) recibidoEl.textContent = '$0';
            }
        });
    </script>
@endpush
