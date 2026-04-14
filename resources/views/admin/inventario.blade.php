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
            <input type="text" placeholder="Buscar insumo..." class="bg-surface-container-low border border-outline-variant/10 rounded-xl pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-primary transition-all w-64">
        </div>
        <button onclick="openModal('modal-add-insumo')" class="bg-gradient-to-br from-primary to-primary-container text-on-primary-container px-6 py-2.5 rounded-xl font-bold text-sm shadow-xl shadow-primary/10 hover:scale-95 transition-transform">
            Agregar Insumo
        </button>
    </div>
</header>

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
                <div class="flex gap-2">
                    <button class="p-2 hover:bg-white/5 rounded-lg text-outline transition-colors"><span class="material-symbols-outlined">filter_list</span></button>
                    <button class="p-2 hover:bg-white/5 rounded-lg text-outline transition-colors"><span class="material-symbols-outlined">download</span></button>
                </div>
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
                    <tbody class="divide-y divide-white/5">
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined">inventory_2</span>
                                    </div>
                                    <span class="font-semibold text-on-surface">Papas Fritas</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm">25.0 kg</td>
                            <td class="px-6 py-4 font-mono text-sm">10.0 kg</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-secondary-container/20 text-secondary-fixed-dim uppercase tracking-wider">Óptimo</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="openModal('modal-edit-insumo')" class="text-xs font-bold text-primary hover:text-white bg-primary/10 hover:bg-primary px-3 py-1.5 rounded-lg transition-all">Actualizar</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined">inventory_2</span>
                                    </div>
                                    <span class="font-semibold text-on-surface">Carne de Res</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm inline-flex text-error border-b border-dashed border-error/50">8.0 kg</td>
                            <td class="px-6 py-4 font-mono text-sm">10.0 kg</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-error-container/20 text-error uppercase tracking-wider">Crítico</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="openModal('modal-edit-insumo')" class="text-xs font-bold text-primary hover:text-white bg-primary/10 hover:bg-primary px-3 py-1.5 rounded-lg transition-all">Actualizar</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined">inventory_2</span>
                                    </div>
                                    <span class="font-semibold text-on-surface">Pan de Hamburguesa</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm">120.0 und</td>
                            <td class="px-6 py-4 font-mono text-sm">50.0 und</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-secondary-container/20 text-secondary-fixed-dim uppercase tracking-wider">Óptimo</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="openModal('modal-edit-insumo')" class="text-xs font-bold text-primary hover:text-white bg-primary/10 hover:bg-primary px-3 py-1.5 rounded-lg transition-all">Actualizar</button>
                            </td>
                        </tr>
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
                <h4 class="text-4xl font-black text-error">03</h4>
                <span class="text-sm text-on-surface-variant mb-1.5">Requieren atención inmediata</span>
            </div>
            <div class="mt-4 w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                <div class="w-3/4 h-full bg-error rounded-full shadow-[0_0_10px_rgba(255,180,171,0.5)]"></div>
            </div>
        </div>

        <!-- Movimientos Recientes Section -->
        <div class="flex-1 bg-surface-container-low rounded-2xl flex flex-col shadow-2xl border border-white/5 overflow-hidden">
            <div class="p-6 border-b border-white/5">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">history</span>
                    Movimientos Recientes
                </h3>
            </div>
            
            <div class="flex-1 overflow-auto p-6 space-y-6">
                <!-- Log Entry -->
                <div class="flex gap-4">
                    <div class="mt-1 w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-green-400 text-sm">add</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-0.5">
                            <p class="text-sm font-bold text-on-surface truncate">Carne de res</p>
                            <span class="text-[10px] font-mono text-outline">14:20</span>
                        </div>
                        <p class="text-xs text-on-surface-variant font-medium">+15.0 kg <span class="text-[10px] opacity-60 ml-2">Ingreso de proveedor</span></p>
                    </div>
                </div>

                <!-- Log Entry -->
                <div class="flex gap-4">
                    <div class="mt-1 w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-red-400 text-sm">remove</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-0.5">
                            <p class="text-sm font-bold text-on-surface truncate">Papas fritas</p>
                            <span class="text-[10px] font-mono text-outline">13:45</span>
                        </div>
                        <p class="text-xs text-on-surface-variant font-medium">-10.5 kg <span class="text-[10px] opacity-60 ml-2">Consumo ventas</span></p>
                    </div>
                </div>

                <!-- Log Entry -->
                <div class="flex gap-4">
                    <div class="mt-1 w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-red-400 text-sm">remove</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-0.5">
                            <p class="text-sm font-bold text-on-surface truncate">Pan de Hamburguesa</p>
                            <span class="text-[10px] font-mono text-outline">12:30</span>
                        </div>
                        <p class="text-xs text-on-surface-variant font-medium">-24 und <span class="text-[10px] opacity-60 ml-2">Consumo ventas</span></p>
                    </div>
                </div>

                <!-- Log Entry -->
                <div class="flex gap-4">
                    <div class="mt-1 w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-green-400 text-sm">add</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-0.5">
                            <p class="text-sm font-bold text-on-surface truncate">Aceite Vegetal</p>
                            <span class="text-[10px] font-mono text-outline">09:15</span>
                        </div>
                        <p class="text-xs text-on-surface-variant font-medium">+20.0 L <span class="text-[10px] opacity-60 ml-2">Ingreso bodega</span></p>
                    </div>
                </div>
            </div>
            
            <button class="m-6 p-3 bg-surface-container-highest/50 hover:bg-surface-container-highest text-xs font-bold text-outline hover:text-white rounded-xl transition-all border border-white/5 uppercase tracking-widest">
                Ver todo el historial
            </button>
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
        <form class="space-y-4">
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Nombre del Insumo</label>
                <input type="text" name="nombre" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="Ej. Tomates frescos" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Inicial</label>
                    <input type="number" step="0.1" name="stock" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="0.00" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Unidad de Medida</label>
                    <select name="unidad" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all appearance-none cursor-pointer">
                        <option value="kg">Kilogramos (kg)</option>
                        <option value="L">Litros (L)</option>
                        <option value="und">Unidades (und)</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Mínimo (Alerta)</label>
                <input type="number" name="stock_min" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="0.00">
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Guardar Insumo</button>
            </div>
        </form>
    </div>

    <!-- Actualizar Stock Modal -->
    <div id="modal-edit-insumo" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300 text-center">
        <h3 class="text-xl font-black text-white mb-2">Actualizar Stock</h3>
        <p class="text-sm text-primary mb-6">Carne de res (Premium)</p>
        
        <div class="flex items-center justify-center gap-6 mb-8">
            <button class="w-12 h-12 rounded-full bg-surface-container-highest border border-white/10 hover:bg-error/20 hover:text-error hover:border-error/50 transition-colors flex items-center justify-center text-xl font-bold">
                <span class="material-symbols-outlined">remove</span>
            </button>
            <div class="text-center">
                <span class="text-3xl font-black text-white">45.5</span>
                <span class="text-sm text-slate-500 ml-1">kg</span>
            </div>
            <button class="w-12 h-12 rounded-full bg-surface-container-highest border border-white/10 hover:bg-emerald-500/20 hover:text-emerald-400 hover:border-emerald-500/50 transition-colors flex items-center justify-center text-xl font-bold">
                <span class="material-symbols-outlined">add</span>
            </button>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
            <button type="button" onclick="closeModals()" class="flex-1 py-3 bg-primary text-on-primary font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Confirmar</button>
        </div>
    </div>
@endpush

@endsection
