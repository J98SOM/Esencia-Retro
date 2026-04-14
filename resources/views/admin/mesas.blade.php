@extends('layouts.admin')

@section('title', 'Esencia Retro - Mesas')

@section('content')
<div class="p-8 flex-1">
    <!-- Header & Filters Bento -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
        <div class="lg:col-span-8 flex flex-col justify-center">
            <h2 class="text-4xl font-black text-on-surface tracking-tighter mb-2">Gestión de Mesas</h2>
            <p class="text-on-surface-variant text-lg">Monitoreo en tiempo real del área de comedor.</p>
        </div>
        <div class="lg:col-span-4 flex items-center justify-end gap-4">
            <div class="relative w-full">
                <input class="w-full bg-surface-container-low border-none focus:ring-2 focus:ring-primary text-on-surface placeholder:text-slate-500 rounded-xl px-4 py-3 pl-11 outline-none" placeholder="Buscar mesa..." type="text"/>
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">search</span>
            </div>
            <button class="p-3 bg-surface-container-high rounded-xl text-primary hover:bg-surface-bright transition-colors">
                <span class="material-symbols-outlined">filter_list</span>
            </button>
        </div>
    </div>

    <!-- Status Filter Bar -->
    <div class="flex flex-wrap gap-4 mb-10">
        <button class="px-6 py-2 rounded-full bg-primary text-on-primary font-bold text-sm shadow-xl shadow-primary/10">Todas (17)</button>
        <button class="px-6 py-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors font-bold text-sm">Libres (9)</button>
        <button class="px-6 py-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors font-bold text-sm">Ocupadas (5)</button>
        <button class="px-6 py-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors font-bold text-sm">Reservadas (3)</button>
    </div>

    @php
    $mesasMock = [
        ['id'=>1, 'zona'=>'Salón Principal', 'numero'=>'01', 'estado'=>'Ocupada', 'pax'=>4, 'total'=>1240.0, 'tiempo'=>'01:14:00'],
        ['id'=>2, 'zona'=>'Salón Principal', 'numero'=>'02', 'estado'=>'Libre', 'pax'=>2, 'tiempo'=>'', 'total'=>0],
        ['id'=>3, 'zona'=>'Salón Principal', 'numero'=>'03', 'estado'=>'Reservada', 'cliente'=>'G. Rossi (4pax)', 'hora'=>'Hoy - 20:30', 'pax'=>0, 'total'=>0],
        ['id'=>4, 'zona'=>'Salón Principal', 'numero'=>'04', 'estado'=>'Ocupada', 'pax'=>6, 'total'=>4100.0, 'tiempo'=>'00:42:15'],
        ['id'=>5, 'zona'=>'Salón Principal', 'numero'=>'05', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
        ['id'=>6, 'zona'=>'Salón Principal', 'numero'=>'06', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
        ['id'=>7, 'zona'=>'Salón Principal', 'numero'=>'07', 'estado'=>'Ocupada', 'pax'=>2, 'total'=>850.0, 'tiempo'=>'00:15:00'],
        ['id'=>8, 'zona'=>'Salón Principal', 'numero'=>'08', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
        ['id'=>9, 'zona'=>'Salón Principal', 'numero'=>'09', 'estado'=>'Libre', 'pax'=>2, 'tiempo'=>'', 'total'=>0],
        ['id'=>10, 'zona'=>'Salón Principal', 'numero'=>'10', 'estado'=>'Reservada', 'cliente'=>'A. Gomez (2pax)', 'hora'=>'Hoy - 21:00', 'pax'=>0, 'total'=>0],
        ['id'=>11, 'zona'=>'Salón Principal', 'numero'=>'11', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
        ['id'=>12, 'zona'=>'Salón Principal', 'numero'=>'12', 'estado'=>'Ocupada', 'pax'=>4, 'total'=>2300.0, 'tiempo'=>'02:10:05'],
        ['id'=>13, 'zona'=>'Terraza', 'numero'=>'13', 'estado'=>'Libre', 'pax'=>2, 'tiempo'=>'', 'total'=>0],
        ['id'=>14, 'zona'=>'Terraza', 'numero'=>'14', 'estado'=>'Ocupada', 'pax'=>6, 'total'=>5400.0, 'tiempo'=>'01:45:00'],
        ['id'=>15, 'zona'=>'Terraza', 'numero'=>'15', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
        ['id'=>16, 'zona'=>'VIP', 'numero'=>'16', 'estado'=>'Libre', 'pax'=>8, 'tiempo'=>'', 'total'=>0],
        ['id'=>17, 'zona'=>'VIP', 'numero'=>'17', 'estado'=>'Reservada', 'cliente'=>'M. Lopez (10pax)', 'hora'=>'Mañana - 22:00', 'pax'=>0, 'total'=>0],
    ];
    @endphp

    <!-- Tables Grid (Asymmetric Bento Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($mesasMock as $m)
            @if($m['estado'] === 'Ocupada')
            <!-- Table Card: Ocupada -->
            <div class="group relative bg-surface-container-highest rounded-3xl p-6 border border-white/5 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-primary/10">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <span class="text-slate-500 font-['Inter'] uppercase tracking-widest text-[10px] block mb-1">Mesa {{ $m['zona'] }}</span>
                        <h3 class="text-3xl font-black text-white">{{ $m['numero'] }}</h3>
                    </div>
                    <div class="px-3 py-1 bg-primary/20 rounded-lg">
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest">Ocupada</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <div class="text-on-surface-variant">
                            <p class="text-[10px] uppercase tracking-widest font-bold">Total Cuenta</p>
                            <p class="text-2xl font-bold text-primary">${{ number_format($m['total'], 2) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="material-symbols-outlined text-slate-500 mb-1">schedule</span>
                            <p class="text-xs text-on-surface-variant">{{ $m['tiempo'] }}</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white/5 flex gap-2">
                        <a href="{{ route('admin.pedido', ['id' => $m['id']]) }}" class="flex-1 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-center text-[10px] font-bold uppercase tracking-widest block transition-colors">Detalles</a>
                        <button onclick="openModal('modal-close-table')" class="flex-1 py-2 bg-primary/10 text-primary hover:bg-primary/20 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors">Cerrar</button>
                    </div>
                </div>
            </div>
            @elseif($m['estado'] === 'Libre')
            <!-- Table Card: Libre -->
            <div class="group relative bg-surface-container-low rounded-3xl p-6 border border-white/5 overflow-hidden transition-all duration-300 hover:bg-surface-container-high">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <span class="text-slate-500 font-['Inter'] uppercase tracking-widest text-[10px] block mb-1">Mesa {{ $m['zona'] }}</span>
                        <h3 class="text-3xl font-black text-white/40">{{ $m['numero'] }}</h3>
                    </div>
                    <div class="px-3 py-1 bg-emerald-500/10 rounded-lg">
                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Libre</span>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center py-6 opacity-20">
                    <span class="material-symbols-outlined text-4xl">restaurant</span>
                    <p class="text-[10px] uppercase tracking-widest mt-2 font-bold">Sin actividad</p>
                </div>
                <div class="mt-4">
                    <button onclick="openModal('modal-open-table')" class="w-full py-3 border border-white/10 hover:border-primary/50 hover:text-primary rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all">Abrir Mesa</button>
                </div>
            </div>
            @elseif($m['estado'] === 'Reservada')
            <!-- Table Card: Reservada -->
            <div class="group relative bg-surface-container-low rounded-3xl p-6 border border-white/5 overflow-hidden transition-all duration-300 hover:bg-surface-container-high">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <span class="text-slate-500 font-['Inter'] uppercase tracking-widest text-[10px] block mb-1">Mesa {{ $m['zona'] }}</span>
                        <h3 class="text-3xl font-black text-white">{{ $m['numero'] }}</h3>
                    </div>
                    <div class="px-3 py-1 bg-amber-500/10 rounded-lg">
                        <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">Reservada</span>
                    </div>
                </div>
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-500/60 text-sm">person</span>
                        <p class="text-xs text-on-surface">{{ $m['cliente'] }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-500/60 text-sm">event_available</span>
                        <p class="text-xs text-on-surface">{{ $m['hora'] }}</p>
                    </div>
                </div>
                <button class="w-full py-3 bg-amber-500/10 text-amber-200 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all">Confirmar Llegada</button>
            </div>
            @endif
        @endforeach

        <!-- Add New Table (Ghost State) -->
        <button onclick="openModal('modal-add-table')" class="group border-2 border-dashed border-white/5 hover:border-primary/30 hover:bg-primary/5 transition-all rounded-3xl p-6 flex flex-col items-center justify-center gap-4">
            <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-primary text-3xl">add_circle</span>
            </div>
            <p class="text-xs uppercase tracking-[0.2em] font-black text-on-surface-variant group-hover:text-primary transition-colors">Añadir Mesa</p>
        </button>
    </div>
</div>

<!-- FAB for Quick Actions -->
<button class="fixed bottom-8 right-8 w-14 h-14 bg-primary text-on-primary rounded-full shadow-2xl flex items-center justify-center md:hidden z-50">
    <span class="material-symbols-outlined">add</span>
</button>

@push('modals')
    <!-- Abrir Mesa Modal -->
    <div id="modal-open-table" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-white">Abrir Mesa</h3>
            <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form class="space-y-6">
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2 block">Número de Comensales (Pax)</label>
                <div class="flex items-center justify-center gap-6 py-4 bg-surface-container-highest rounded-xl border border-white/10">
                    <button type="button" class="w-10 h-10 rounded-full hover:bg-white/10 transition-colors flex items-center justify-center text-xl font-bold text-white">
                        <span class="material-symbols-outlined">remove</span>
                    </button>
                    <span class="text-3xl font-black text-white">2</span>
                    <button type="button" class="w-10 h-10 rounded-full hover:bg-white/10 transition-colors flex items-center justify-center text-xl font-bold text-white">
                        <span class="material-symbols-outlined">add</span>
                    </button>
                </div>
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                <button type="button" onclick="closeModals()" class="flex-1 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Iniciar Servicio</button>
            </div>
        </form>
    </div>

    <!-- Cerrar Mesa Modal -->
    <div id="modal-close-table" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300 text-center">
        <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center text-error mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">money_off</span>
        </div>
        <h3 class="text-xl font-black text-white mb-2">¿Liberar Mesa?</h3>
        <p class="text-sm text-on-surface-variant mb-6">Si cierras esta mesa sin facturar, el historial de pedidos no registrará pagos. ¿Deseas redirigirte al checkout?</p>
        
        <div class="flex flex-col gap-3">
            <button type="button" onclick="closeModals()" class="w-full py-3 rounded-xl bg-primary text-on-primary font-bold shadow-lg shadow-primary/20 hover:scale-[0.98] transition-transform text-sm">
                Ir a Facturar y Cobrar
            </button>
            <button type="button" onclick="closeModals()" class="w-full py-3 rounded-xl border border-error/20 hover:bg-error/10 hover:text-error text-error/80 transition-colors font-bold text-sm">
                Solo Liberar (Sin Pago)
            </button>
        </div>
    </div>

    <!-- Add Table Modal -->
    <div id="modal-add-table" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-white">Nueva Mesa</h3>
            <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form class="space-y-4">
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Identificador o Número</label>
                <input type="text" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="Ej. VIP 02, Terraza 5">
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Capacidad Max. Pax</label>
                <input type="number" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="4">
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Registrar Mesa</button>
            </div>
        </form>
    </div>
@endpush

@endsection
