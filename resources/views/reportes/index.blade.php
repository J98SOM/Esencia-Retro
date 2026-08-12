@extends('layouts.admin')

@section('title', 'Esencia Retro - Reportes')

@section('content')
<div class="p-8 flex-1">
    <!-- Header Section -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-white mb-2">Reportes Analíticos</h1>
            <p class="text-on-surface-variant">Visualiza el rendimiento operativo y métricas reales de ventas de Esencia Retro.</p>
        </div>
        <div class="flex flex-wrap gap-4 items-center">
            <!-- Period Selector Form -->
            <form method="GET" action="{{ route('admin.reportes') }}" id="period-form" class="flex gap-1.5 bg-surface-container-low p-1.5 rounded-xl border border-white/5">
                <!-- If date is selected, clicking period clears the date -->
                <button type="submit" name="periodo" value="diario" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $periodo === 'diario' && !$fechaSelect && !$fechaInicio && !$fechaFin ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'text-slate-400 hover:text-white' }}">Diario</button>
                <button type="submit" name="periodo" value="semanal" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $periodo === 'semanal' && !$fechaSelect && !$fechaInicio && !$fechaFin ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'text-slate-400 hover:text-white' }}">Semanal</button>
                <button type="submit" name="periodo" value="mensual" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $periodo === 'mensual' && !$fechaSelect && !$fechaInicio && !$fechaFin ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'text-slate-400 hover:text-white' }}">Mensual</button>
                <button type="submit" name="periodo" value="anual" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $periodo === 'anual' && !$fechaSelect && !$fechaInicio && !$fechaFin ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'text-slate-400 hover:text-white' }}">Anual</button>
            </form>

            <!-- Date Range Selector Form -->
            <form method="GET" action="{{ route('admin.reportes') }}" id="date-range-form" class="flex items-center bg-surface-container-low rounded-xl px-4 py-2 border border-white/5 gap-2 hover:bg-white/5 transition-colors">
                <span class="material-symbols-outlined text-primary text-sm">date_range</span>
                <input type="datetime-local" name="fecha_inicio" value="{{ $fechaInicio }}" onchange="if(this.form.fecha_fin.value) this.form.submit()" onclick="event.stopPropagation(); this.showPicker();" class="bg-transparent border-0 p-0 text-sm font-semibold text-white focus:outline-none focus:ring-0 cursor-pointer w-40" placeholder="Desde">
                <span class="text-slate-500 text-xs font-bold">a</span>
                <input type="datetime-local" name="fecha_fin" value="{{ $fechaFin }}" onchange="if(this.form.fecha_inicio.value) this.form.submit()" onclick="event.stopPropagation(); this.showPicker();" class="bg-transparent border-0 p-0 text-sm font-semibold text-white focus:outline-none focus:ring-0 cursor-pointer w-40" placeholder="Hasta">
                @if($fechaInicio || $fechaFin)
                    <a href="{{ route('admin.reportes') }}" class="text-slate-500 hover:text-white flex items-center transition-colors ml-1">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </a>
                @endif
            </form>

            <div class="flex gap-2">
                <a href="{{ route('admin.reportes.exportar', ['periodo' => $periodo, 'fecha' => $fechaSelect, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="bg-surface-container-highest hover:bg-white/5 text-white font-semibold py-2.5 px-5 rounded-xl flex items-center gap-2 transition-all border border-white/5 text-sm">
                    <span class="material-symbols-outlined text-sm text-primary">download</span>
                    <span>Exportar CSV</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Key Metrics Bento Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Revenue Card -->
        <div class="bg-surface-container-low border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-primary/30 transition-all">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-primary/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-2 bg-primary/10 rounded-lg border border-primary/20">
                    <span class="material-symbols-outlined text-primary">payments</span>
                </div>
                <span class="text-emerald-400 text-xs font-bold bg-emerald-400/10 border border-emerald-400/20 px-2 py-1 rounded-full">Real</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1 relative z-10">
                @if($fechaInicio && $fechaFin)
                    Ventas ({{ date('d/m/y H:i', strtotime($fechaInicio)) }} - {{ date('d/m/y H:i', strtotime($fechaFin)) }})
                @elseif($fechaSelect)
                    Ventas del Día ({{ date('d/m/Y', strtotime($fechaSelect)) }})
                @else
                    Ingresos Totales ({{ ucfirst($periodo) }})
                @endif
            </p>
            <h3 class="text-3xl font-black text-white relative z-10">${{ number_format($ventasTotales, 2, '.', ',') }}</h3>
        </div>

        <!-- Orders Completed Card -->
        <div class="bg-surface-container-low border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-emerald-500/30 transition-all">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-2 bg-emerald-500/10 rounded-lg border border-emerald-500/20">
                    <span class="material-symbols-outlined text-emerald-400">task_alt</span>
                </div>
                <span class="text-emerald-400 text-xs font-bold bg-emerald-400/10 border border-emerald-400/20 px-2 py-1 rounded-full">Real</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1 relative z-10">Pedidos Completados</p>
            <h3 class="text-3xl font-black text-white relative z-10">{{ $pedidosCompletados }}</h3>
        </div>

        <!-- Tickets Card -->
        <div class="bg-surface-container-low border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-secondary/30 transition-all">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-2 bg-secondary/10 rounded-lg border border-secondary/20">
                    <span class="material-symbols-outlined text-secondary">receipt_long</span>
                </div>
                <span class="text-emerald-400 text-xs font-bold bg-emerald-400/10 border border-emerald-400/20 px-2 py-1 rounded-full">Real</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1 relative z-10">Ticket Promedio</p>
            <h3 class="text-3xl font-black text-white relative z-10">${{ number_format($ticketPromedio, 2, '.', ',') }}</h3>
        </div>

        <!-- Products Sold Card -->
        <div class="bg-surface-container-low border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-error/30 transition-all">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-2 bg-error/10 rounded-lg border border-error/20">
                    <span class="material-symbols-outlined text-error">shopping_cart_checkout</span>
                </div>
                <span class="text-emerald-400 text-xs font-bold bg-emerald-400/10 border border-emerald-400/20 px-2 py-1 rounded-full">Real</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1 relative z-10">Productos Vendidos</p>
            <h3 class="text-3xl font-black text-white relative z-10">{{ intval($productosVendidos) }}</h3>
        </div>
    </section>

    <!-- Mid Section: Operations & Payments -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        
        <!-- Products Sold Details (Scrollable List) -->
        <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6 lg:col-span-1 flex flex-col h-[400px]">
            <h4 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">inventory_2</span> Productos Vendidos
            </h4>
            
            <div class="flex-1 overflow-y-auto pr-1 space-y-3.5 scrollbar-thin">
                @forelse($productsSold as $ps)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container border border-white/5 hover:border-white/10 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center font-black text-xs text-primary shadow-inner">
                                {{ intval($ps->total_qty) }}x
                            </div>
                            <div>
                                <span class="font-bold text-white text-sm block leading-tight tracking-tight">{{ $ps->nombre }}</span>
                                <span class="text-[9px] uppercase font-bold tracking-widest text-slate-500">{{ $ps->categoria }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-emerald-400">${{ number_format($ps->total_revenue, 2, '.', ',') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center text-slate-500 py-12">
                        <span class="material-symbols-outlined text-4xl mb-2 text-slate-600">restaurant</span>
                        <p class="text-xs">No hay productos vendidos en este período.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6 lg:col-span-1 h-[400px] flex flex-col justify-between">
            <h4 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">account_balance_wallet</span> Métodos de Pago
            </h4>
            
            <div class="flex justify-center mb-4">
                <div class="relative w-24 h-24 rounded-full border-[10px] border-surface-container-highest flex items-center justify-center border-t-primary border-r-secondary border-l-emerald-500 border-b-secondary">
                    <span class="text-[10px] font-black text-slate-400">Total</span>
                </div>
            </div>

            <div class="space-y-2.5">
                @php
                    $tarjetaPct = $totalMetodosSum > 0 ? ($metodosPago['tarjeta'] / $totalMetodosSum) * 100 : 0;
                    $efectivoPct = $totalMetodosSum > 0 ? ($metodosPago['efectivo'] / $totalMetodosSum) * 100 : 0;
                    $yapePct = $totalMetodosSum > 0 ? ($metodosPago['yape_plin'] / $totalMetodosSum) * 100 : 0;
                @endphp
                <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container border border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-primary shadow-[0_0_8px_rgba(234,188,78,0.5)]"></span>
                        <span class="text-sm text-white font-bold">Tarjeta</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-white">{{ number_format($tarjetaPct, 0) }}%</span>
                        <p class="text-[10px] text-slate-500">${{ number_format($metodosPago['tarjeta'], 2, '.', ',') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container border border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-secondary"></span>
                        <span class="text-sm text-white font-bold">Efectivo</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-white">{{ number_format($efectivoPct, 0) }}%</span>
                        <p class="text-[10px] text-slate-500">${{ number_format($metodosPago['efectivo'], 2, '.', ',') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container border border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.3)]"></span>
                        <span class="text-sm text-white font-bold">Transferencia</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-white">{{ number_format($yapePct, 0) }}%</span>
                        <p class="text-[10px] text-slate-500">${{ number_format($metodosPago['yape_plin'], 2, '.', ',') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Daily Traffic -->
        <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6 lg:col-span-1 h-[400px] flex flex-col justify-between">
            <h4 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">bar_chart</span> Afluencia por Horas
            </h4>
            
            <div class="flex items-end justify-between h-48 gap-2 mb-4">
                @foreach($afluencia as $hour => $count)
                    @php
                        $heightPercent = $maxTraffic > 0 ? ($count / $maxTraffic) * 100 : 15;
                        if ($heightPercent < 15) $heightPercent = 15; // Min height for aesthetic display
                        
                        $isPeak = $count > 0 && $count == $maxTraffic;
                        $bgClass = $isPeak ? 'bg-primary shadow-[0_0_15px_rgba(234,188,78,0.3)]' : 'bg-surface-container-highest hover:bg-primary/50';
                    @endphp
                    <div class="group relative w-full {{ $bgClass }} transition-all duration-300 rounded-lg" style="height: {{ $heightPercent }}%">
                        <span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-white transition-opacity bg-black/60 px-1 rounded z-20">{{ $count }}</span>
                        <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[9px] font-bold text-slate-500 uppercase tracking-widest select-none">{{ $hour }}h</span>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between text-[10px] uppercase font-bold text-slate-500 tracking-widest px-2 mt-4">
                <span>Almuerzo</span>
                <span>Tarde</span>
                <span class="text-primary">Noche</span>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Category sales -->
    <div class="grid grid-cols-1 gap-6 mb-10">
        <!-- Top Selling Items (Compact) -->
        <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">local_fire_department</span> Ventas por Categoría
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($categories as $cat)
                        @php
                            $catPercent = $totalCategorySum > 0 ? ($cat->total / $totalCategorySum) * 100 : 0;
                            // Colors for categories based on name or index
                            $colorClass = 'bg-primary';
                            if (str_contains(strtolower($cat->categoria), 'bebida') || str_contains(strtolower($cat->categoria), 'coctel')) {
                                $colorClass = 'bg-secondary';
                            } elseif (str_contains(strtolower($cat->categoria), 'entrada') || str_contains(strtolower($cat->categoria), 'snack')) {
                                $colorClass = 'bg-emerald-500';
                            }
                        @endphp
                        <div class="flex items-center gap-4 p-3 hover:bg-white/5 rounded-xl transition-colors border border-transparent hover:border-white/5 bg-surface-container">
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <h5 class="text-sm font-bold text-white capitalize">{{ $cat->categoria }}</h5>
                                    <span class="text-sm font-black text-white">${{ number_format($cat->total, 2, '.', ',') }} ({{ number_format($catPercent, 0) }}%)</span>
                                </div>
                                <div class="w-full bg-surface-container-highest rounded-full h-1 mt-2">
                                    <div class="{{ $colorClass }} h-1 rounded-full" style="width: {{ $catPercent }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-slate-500 py-6">No hay datos de categorías registradas.</div>
                    @endforelse
                </div>
            </div>
            
            <a href="{{ route('admin.reportes.exportar', ['periodo' => $periodo, 'fecha' => $fechaSelect, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="w-full mt-6 py-3 text-xs font-bold text-primary border border-primary/20 hover:bg-primary/10 rounded-xl transition-all uppercase tracking-widest text-center block">Descargar Informe Completo (CSV)</a>
        </div>
    </div>
</div>
@endsection
