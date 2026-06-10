@extends('layouts.admin')

@section('title', 'Esencia Retro - Lista de Alquileres')

@section('content')
<div class="p-6 lg:p-8 flex-1">
    <header class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold">Alquileres (evento)</h2>
            <p class="text-sm text-on-surface-variant">Total: <span id="alquiler-count">—</span></p>
        </div>
        <div>
            <button onclick="fetchAlquileres(1)" class="px-4 py-2 rounded-xl bg-primary text-on-primary">Refrescar</button>
        </div>
    </header>

    <div class="bg-surface-container-low rounded-2xl overflow-hidden shadow p-4">
        <div class="overflow-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-on-surface-variant uppercase tracking-widest">
                        <th class="p-3 text-left">#</th>
                        <th class="p-3 text-left">No. Orden</th>
                        <th class="p-3 text-left">Fecha</th>
                        <th class="p-3 text-left">Persona</th>
                        <th class="p-3 text-right">Monto</th>
                        <th class="p-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody id="alquileres-body">
                    <tr><td colspan="6" class="p-6 text-center text-sm text-slate-400">Cargando…</td></tr>
                </tbody>
            </table>
        </div>

        <div id="alquileres-pagination" class="mt-4 flex items-center justify-center gap-2"></div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/alquiler_list.js') }}"></script>
@endpush

@push('modals')
    <div id="modal-alquiler-detail" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/60" onclick="closeAlquilerModal()"></div>
        <div class="bg-surface-container-low rounded-2xl p-6 z-10 w-[90%] max-w-3xl">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-alq-title" class="text-lg font-bold">Factura</h3>
                <button onclick="closeAlquilerModal()" class="px-3 py-1 rounded bg-white/5">Cerrar</button>
            </div>
            <div id="modal-alq-body">
                <p class="text-sm text-slate-400">Cargando…</p>
            </div>
        </div>
    </div>
@endpush
