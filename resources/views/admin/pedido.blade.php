@extends('layouts.admin')

@section('title', 'Esencia Retro - Detalle de Mesa')

@section('content')
<div class="p-8 flex-1">
    <!-- Header Section -->
    <header class="flex justify-between items-center mb-10">
        <div>
            <nav class="flex items-center space-x-2 text-xs text-on-surface-variant mb-2">
                <span>Gestión</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary hover:underline"><a href="{{ route('admin.mesas') }}">Mesas</a></span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary">Mesa {{ $mesaId ?? '00' }}</span>
            </nav>
            <h2 class="text-4xl font-extrabold tracking-tight text-white flex items-center gap-4">
                Pedido en Curso 
                <span class="px-3 py-1 bg-primary/20 text-primary rounded-lg text-sm uppercase tracking-widest font-black">
                    Mesa {{ $mesaId ?? '00' }}
                </span>
                @if(isset($factura) && $factura->numero_orden)
                <span class="px-3 py-1 bg-white/10 text-white rounded-lg text-sm uppercase tracking-widest font-black">
                    Orden #{{ $factura->numero_orden }}
                </span>
                @endif
            </h2>
            <p class="text-on-surface-variant text-sm mt-1">Añade o modifica los productos ordenados en esta mesa.</p>
        </div>
        <div class="flex items-center gap-4">
            <button @if(isset($factura) && $factura->id) onclick="window.open('{{ route('admin.pos.receipt', $factura->id) }}', '_blank', 'width=400,height=600')" @else onclick="alert('No hay una factura activa para imprimir.')" @endif class="bg-surface-container-high hover:bg-surface-bright text-on-surface p-3 rounded-xl transition-colors font-bold flex items-center gap-2">
                <span class="material-symbols-outlined">print</span>
                Imprimir Pre-cuenta
            </button>
            <button onclick="window.location.href='{{ route('admin.checkout', ['id' => $mesaId ?? '4']) }}'" class="primary-gradient text-on-primary p-3 rounded-xl transition-transform hover:scale-95 font-bold flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">check_circle</span>
                Cerrar Mesa
            </button>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Order List (Left/Main) -->
        <div class="lg:col-span-2 bg-surface-container-low rounded-2xl p-8 border border-white/5 flex flex-col h-full">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-xl font-bold text-white">Detalle del Pedido</h4>
                <button onclick="openModal('modal-add-product-order')" class="text-primary font-bold text-sm flex items-center gap-1 hover:text-primary-fixed-dim transition-colors">
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    Añadir Producto
                </button>
            </div>
            
            <div class="flex-1 overflow-auto space-y-4">
                @forelse($items as $item)
                    @php
                        $prod = $item->producto;
                        $precioTotal = $item->cantidad * $item->precio_unitario;
                    @endphp
                    <div class="bg-surface-container-highest/50 p-4 rounded-xl flex justify-between items-center border border-white/5 hover:border-white/10 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-surface-container-low rounded-lg flex items-center justify-center font-bold text-lg text-white">
                                {{ intval($item->cantidad) }}x
                            </div>
                            <div>
                                <p class="font-bold text-white text-lg">{{ $prod->nombre ?? $item->descripcion }}</p>
                                <p class="text-slate-500 text-xs mt-1">Precio unitario: ${{ number_format($item->precio_unitario, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <p class="text-lg font-bold text-white">${{ number_format($precioTotal, 0, ',', '.') }}</p>
                            <form action="{{ route('admin.pedido.delete_item', ['mesaId' => $mesaId, 'itemId' => $item->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-error hover:text-error-container p-2 rounded-lg bg-error/10 transition-colors">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No hay productos agregados en esta mesa.</p>
                @endforelse
            </div>
        </div>

        <!-- Order Summary (Right Side) -->
        <aside class="flex flex-col gap-6">
            <div class="bg-surface-container-low rounded-2xl p-8 border border-white/5 group">
                <h4 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>
                    Resumen
                </h4>
                <div class="space-y-4 text-sm mb-6">
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Subtotal</span>
                        <span class="font-bold text-white">${{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="pt-6 border-t border-white/5 flex justify-between items-center mb-6">
                    <span class="text-lg font-bold text-on-surface">Total</span>
                    <span class="text-3xl font-black text-primary">${{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <button onclick="window.location.href='{{ route('admin.checkout', ['id' => $mesaId]) }}'" class="w-full py-4 rounded-xl bg-primary text-on-primary font-bold shadow-lg shadow-primary/20 hover:scale-[0.98] transition-all">
                    Cobrar Mesa
                </button>
            </div>

            <div class="bg-surface-container-low rounded-2xl p-6 border border-white/5 flex gap-4 mt-6">
                <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-emerald-400">person</span>
                </div>
                <div>
                    <h5 class="font-bold text-white text-sm">{{ auth()->user()->name }}</h5>
                    <p class="text-xs text-on-surface-variant capitalize">{{ optional(auth()->user()->rol)->name ?? 'Mesero' }} en turno</p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
