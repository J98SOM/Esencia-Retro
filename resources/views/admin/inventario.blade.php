@extends('layouts.admin')

@section('title', 'Esencia Retro - Inventario')

@section('content')
<!-- Header Section -->
<header class="h-20 flex items-center justify-between px-8 bg-surface-container-low/50 backdrop-blur-md">
    <div>
        <h2 class="text-2xl font-extrabold text-on-surface tracking-tight">Inventario: Esencia Retro</h2>
        <p class="text-sm text-on-surface-variant font-medium">Control central de insumos y materia prima</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input id="insumo-search" type="text" placeholder="Buscar insumo..." class="bg-surface-container-low border border-outline-variant/10 rounded-xl pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-primary transition-all w-64 text-white">
        </div>
        <button onclick="openModal('modal-add-insumo')" class="bg-gradient-to-br from-primary to-primary-container text-on-primary-container px-6 py-2.5 rounded-xl font-bold text-sm shadow-xl shadow-primary/10 hover:scale-95 transition-transform">
            Agregar Insumo
        </button>
    </div>
</header>

@if(session('success'))
    <div class="px-8 mt-4">
        <div class="p-3 rounded bg-emerald-700/10 text-emerald-300">
            {{ session('success') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="px-8 mt-4">
        <div class="p-3 rounded bg-error/20 text-error">
            {{ $errors->first() }}
        </div>
    </div>
@endif

<!-- Main Workspace -->
<div class="flex-1 flex overflow-hidden p-8 gap-8">
    <!-- Left Side: Data Table -->
    <section class="flex-[3] flex flex-col min-w-0">
        <div class="bg-surface-container-low rounded-2xl overflow-hidden shadow-2xl shadow-black/20 flex flex-col h-full border border-white/5">
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">format_list_bulleted</span>
                    Lista de Insumos
                </h3>
            </div>
            
            <div class="flex-1 overflow-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-surface-container-low z-10">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline">Insumo</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline">Stock Actual</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline">Stock Mínimo</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline text-center">Estado</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="inventarios-body" class="divide-y divide-white/5">
                        @foreach($inventarios as $i)
                            @php
                                $isCritical = $i->stock_inicial <= $i->stock_minimo;
                            @endphp
                            <tr class="hover:bg-white/[0.02] transition-colors group insumo-row" data-nombre="{{ strtolower($i->nombre) }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined">inventory_2</span>
                                        </div>
                                        <span class="font-semibold text-on-surface">{{ $i->nombre }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm {{ $isCritical ? 'text-error border-b border-dashed border-error/50' : '' }}">
                                    {{ $i->stock_inicial }} {{ $i->unidad_medida }}
                                </td>
                                <td class="px-6 py-4 font-mono text-sm">
                                    {{ $i->stock_minimo }} {{ $i->unidad_medida }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($isCritical)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-error-container/20 text-error uppercase tracking-wider">Crítico</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-secondary-container/20 text-secondary-fixed-dim uppercase tracking-wider">Óptimo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button onclick="openEditModal({{ $i->id }}, '{{ addslashes($i->nombre) }}', {{ $i->stock_inicial }}, {{ $i->stock_minimo }}, '{{ $i->unidad_medida }}', {{ $i->descuento_inventario }})"
                                        class="text-xs font-bold text-primary hover:text-white bg-primary/10 hover:bg-primary px-3 py-1.5 rounded-lg transition-all">Actualizar</button>
                                    <form method="POST" action="{{ route('admin.inventario.delete', ['id' => $i->id]) }}" onsubmit="return confirm('¿Eliminar insumo?')">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-error hover:text-white bg-error/10 hover:bg-error px-3 py-1.5 rounded-lg transition-all">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Right Side: Sidebar Analytics & Log -->
    <aside class="flex-1 flex flex-col gap-8 min-w-[320px]">
        <!-- Quick Metric Card -->
        <div class="bg-gradient-to-br from-surface-container-high to-surface-container shadow-2xl rounded-2xl p-6 border border-white/5 relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-primary/10 blur-3xl rounded-full transition-all group-hover:bg-primary/20"></div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-outline mb-1">Insumos Críticos</p>
            <div class="flex items-end gap-3">
                <h4 class="text-4xl font-black text-error">{{ str_pad($alertasInventario, 2, '0', STR_PAD_LEFT) }}</h4>
                <span class="text-sm text-on-surface-variant mb-1.5">Requieren atención inmediata</span>
            </div>
            <div class="mt-4 w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                <div class="w-3/4 h-full bg-error rounded-full shadow-[0_0_10px_rgba(255,180,171,0.5)]"></div>
            </div>
        </div>

        <!-- Movimientos Recientes Section (Fallback) -->
        <div class="flex-1 bg-surface-container-low rounded-2xl flex flex-col shadow-2xl border border-white/5 overflow-hidden">
            <div class="p-6 border-b border-white/5">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">history</span>
                    Movimientos Recientes
                </h3>
            </div>
            <div id="movimientos-list" class="flex-1 overflow-auto p-6 space-y-6">
                <!-- client-side mock movements -->
                <div class="text-xs text-on-surface-variant">Historial guardado localmente en el navegador.</div>
            </div>
        </div>
    </aside>
</div>

@push('modals')
    <!-- Agregar Insumo Modal -->
    <div id="modal-add-insumo" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-white">Nuevo Insumo</h3>
            <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-add-insumo" method="POST" action="{{ route('admin.inventario.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Nombre del Insumo</label>
                <input type="text" name="nombre" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="Ej. Tomates frescos" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Inicial</label>
                    <input type="number" step="0.1" name="stock_inicial" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="0.00" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Unidad de Medida</label>
                    <select name="unidad_medida" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all appearance-none cursor-pointer">
                        <option value="kg">Kilogramos (kg)</option>
                        <option value="L">Litros (L)</option>
                        <option value="und">Unidades (und)</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Mínimo (Alerta)</label>
                <input type="number" name="stock_minimo" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="0.00" required>
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Descuento Inventario (%)</label>
                <input type="number" step="0.01" min="0" name="descuento_inventario" value="0"
                    class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="0">
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Guardar Insumo</button>
            </div>
        </form>
    </div>

    <!-- Actualizar Insumo Modal (Editar todos los campos) -->
    <div id="modal-edit-insumo" class="modal-content hidden bg-surface-container-low border border-white/10 p-6 rounded-3xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300">
        <h3 class="text-xl font-black text-white mb-2">Editar Insumo</h3>
        <form id="form-edit-insumo" method="POST" action="" class="space-y-4">
            @csrf
            <input type="hidden" name="id" id="edit-insumo-id">
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Nombre del Insumo</label>
                <input id="edit-insumo-nombre" name="nombre" type="text" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary" required>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Actual</label>
                    <input id="edit-insumo-stock" name="stock_inicial" type="number" step="0.1" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Mínimo</label>
                    <input id="edit-insumo-stock-min" name="stock_minimo" type="number" step="0.1" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Unidad</label>
                    <select id="edit-insumo-unidad" name="unidad_medida" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary appearance-none">
                        <option value="kg">kg</option>
                        <option value="L">L</option>
                        <option value="und">und</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Descuento Inventario (%)</label>
                <input id="edit-insumo-descuento" name="descuento_inventario" type="number" step="0.01" min="0" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary">
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-4">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                <button type="submit" id="edit-insumo-submit" class="flex-1 py-3 bg-primary text-on-primary font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Guardar cambios</button>
            </div>
        </form>
    </div>
@endpush

@push('scripts')
<script>
(function(){
    const searchInput = document.getElementById('insumo-search');
    const rows = document.querySelectorAll('.insumo-row');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase().trim();
        rows.forEach(r => {
            const nombre = r.getAttribute('data-nombre') || '';
            if (nombre.includes(query)) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    });

    window.openEditModal = function(id, nombre, stock, stockMin, unidad, descuento) {
        const form = document.getElementById('form-edit-insumo');
        form.action = "/admin/inventario/" + id;
        document.getElementById('edit-insumo-id').value = id;
        document.getElementById('edit-insumo-nombre').value = nombre;
        document.getElementById('edit-insumo-stock').value = stock;
        document.getElementById('edit-insumo-stock-min').value = stockMin;
        document.getElementById('edit-insumo-unidad').value = unidad;
        document.getElementById('edit-insumo-descuento').value = descuento;
        openModal('modal-edit-insumo');
    };
})();
</script>
@endpush

@endsection
