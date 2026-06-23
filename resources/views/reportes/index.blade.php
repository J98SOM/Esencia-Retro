@extends('layouts.admin')

@section('title', 'Esencia Retro - Reportes')

@section('content')
<div class="p-8 flex-1">
    <!-- Header Section -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-white mb-2">Reportes Analíticos</h1>
            <p class="text-on-surface-variant">Visualiza el rendimiento operativo y métricas financieras de Esencia Retro.</p>
        </div>
        <div class="flex flex-wrap gap-4 items-center">
            <!-- Date Range Picker Mock -->
            <div class="flex items-center bg-surface-container-low rounded-xl px-4 py-2 border border-white/5">
                <span class="material-symbols-outlined text-primary text-sm mr-2">calendar_month</span>
                <span class="text-sm font-medium mr-4 text-white">Hoy, Octubre 24</span>
                <span class="material-symbols-outlined text-slate-500 text-sm cursor-pointer">expand_more</span>
            </div>
            <div class="flex gap-2">
                <button class="bg-surface-container-highest hover:bg-white/5 text-white font-semibold py-2 px-4 rounded-xl flex items-center gap-2 transition-all border border-white/5">
                    <span class="material-symbols-outlined text-sm text-primary">download</span>
                    <span>Exportar</span>
                </button>
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
                <span class="text-emerald-400 text-xs font-bold bg-emerald-400/10 border border-emerald-400/20 px-2 py-1 rounded-full">+12.5%</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1 relative z-10">Ingresos Totales (Hoy)</p>
            <h3 class="text-3xl font-black text-white relative z-10">$12,458.00</h3>
        </div>

        <!-- Profit Card -->
        <div class="bg-surface-container-low border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-emerald-500/30 transition-all">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-2 bg-emerald-500/10 rounded-lg border border-emerald-500/20">
                    <span class="material-symbols-outlined text-emerald-400">trending_up</span>
                </div>
                <span class="text-emerald-400 text-xs font-bold bg-emerald-400/10 border border-emerald-400/20 px-2 py-1 rounded-full">+8.2%</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1 relative z-10">Utilidad Bruta</p>
            <h3 class="text-3xl font-black text-white relative z-10">$7,105.50</h3>
        </div>

        <!-- Tickets Card -->
        <div class="bg-surface-container-low border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-secondary/30 transition-all">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-2 bg-secondary/10 rounded-lg border border-secondary/20">
                    <span class="material-symbols-outlined text-secondary">receipt_long</span>
                </div>
                <span class="text-rose-400 text-xs font-bold bg-rose-400/10 border border-rose-400/20 px-2 py-1 rounded-full">-2.1%</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1 relative z-10">Ticket Promedio</p>
            <h3 class="text-3xl font-black text-white relative z-10">$45.50</h3>
        </div>

        <!-- Cost Card -->
        <div class="bg-surface-container-low border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-error/30 transition-all">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-2 bg-error/10 rounded-lg border border-error/20">
                    <span class="material-symbols-outlined text-error">shopping_cart_checkout</span>
                </div>
                <span class="text-slate-400 text-xs font-bold bg-white/5 px-2 py-1 rounded-full border border-white/10">=</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1 relative z-10">Costos Operativos</p>
            <h3 class="text-3xl font-black text-white relative z-10">$5,352.50</h3>
        </div>
    </section>

    <!-- Mid Section: Operations & Payments -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        
        <!-- Service & Efficiency Metrics -->
        <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6 lg:col-span-1">
            <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">timer</span> Eficiencia de Servicio
            </h4>
            
            <div class="space-y-6">
                <!-- Prep Time -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-400">Promedio de Preparación</span>
                        <span class="font-bold text-white text-right">14 min <br><span class="text-[10px] text-emerald-400">Óptimo</span></span>
                    </div>
                    <div class="w-full bg-surface-container-highest rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full shadow-[0_0_10px_rgba(234,188,78,0.5)]" style="width: 45%"></div>
                    </div>
                </div>
                
                <!-- Table Turnover -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-400">Estadía por Mesa</span>
                        <span class="font-bold text-white text-right">45 min <br><span class="text-[10px] text-emerald-400">Rotación Rápida</span></span>
                    </div>
                    <div class="w-full bg-surface-container-highest rounded-full h-2">
                        <div class="bg-secondary h-2 rounded-full" style="width: 60%"></div>
                    </div>
                </div>

                <!-- Cancellation Rate -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-400">Tasa de Anulaciones</span>
                        <span class="font-bold text-white text-right">3.2% <br><span class="text-[10px] text-rose-400">Requiere Revisión</span></span>
                    </div>
                    <div class="w-full bg-surface-container-highest rounded-full h-2">
                        <div class="bg-error h-2 rounded-full" style="width: 15%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6 lg:col-span-1">
            <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">account_balance_wallet</span> Métodos de Pago
            </h4>
            
            <div class="flex justify-center mb-6">
                <div class="relative w-28 h-28 rounded-full border-[10px] border-surface-container-highest flex items-center justify-center border-t-primary border-r-secondary border-l-emerald-500 border-b-secondary">
                    <span class="text-lg font-black text-white">100%</span>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container border border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-primary shadow-[0_0_8px_rgba(234,188,78,0.5)]"></span>
                        <span class="text-sm text-white font-bold">Tarjetas (POS)</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-white">55%</span>
                        <p class="text-[10px] text-slate-500">$6,851.90</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container border border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-secondary"></span>
                        <span class="text-sm text-white font-bold">Efectivo</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-white">25%</span>
                        <p class="text-[10px] text-slate-500">$3,114.50</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container border border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.3)]"></span>
                        <span class="text-sm text-white font-bold">Yape / Plin</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-white">20%</span>
                        <p class="text-[10px] text-slate-500">$2,491.60</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Daily Traffic -->
        <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6 lg:col-span-1">
            <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">bar_chart</span> Afluencia por Horas
            </h4>
            
            <div class="flex items-end justify-between h-56 gap-2 mb-4">
                <div class="group relative w-full bg-surface-container-highest hover:bg-primary/50 transition-colors rounded-lg h-[15%]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-300">12p</span></div>
                <div class="group relative w-full bg-surface-container-highest hover:bg-primary/50 transition-colors rounded-lg h-[40%]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-white">1p</span></div>
                <div class="group relative w-full bg-surface-container-highest hover:bg-primary/50 transition-colors rounded-lg h-[75%]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-white">2p</span></div>
                <div class="group relative w-full bg-surface-container-highest hover:bg-primary/50 transition-colors rounded-lg h-[35%]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-300">3p</span></div>
                <div class="group relative w-full bg-surface-container-highest hover:bg-primary/50 transition-colors rounded-lg h-[20%]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-300">4p</span></div>
                <div class="group relative w-full bg-surface-container-highest hover:bg-primary/50 transition-colors rounded-lg h-[25%]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-300">5p</span></div>
                <div class="group relative w-full bg-surface-container-highest hover:bg-primary/50 transition-colors rounded-lg h-[50%]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-white">6p</span></div>
                <div class="group relative w-full bg-primary transition-colors rounded-lg h-[80%] shadow-[0_0_15px_rgba(234,188,78,0.3)]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] text-primary font-bold">19h</span></div>
                <div class="group relative w-full bg-primary transition-colors rounded-lg h-[100%] shadow-[0_0_15px_rgba(234,188,78,0.3)]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] text-primary font-bold">20h</span></div>
                <div class="group relative w-full bg-surface-container-highest hover:bg-primary/50 transition-colors rounded-lg h-[65%]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-white">9p</span></div>
                <div class="group relative w-full bg-surface-container-highest hover:bg-primary/50 transition-colors rounded-lg h-[40%]"><span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] opacity-0 group-hover:opacity-100 font-bold text-white">10p</span></div>
            </div>
            <div class="flex justify-between text-[10px] uppercase font-bold text-slate-500 tracking-widest px-2">
                <span>Almuerzo</span>
                <span>Tarde</span>
                <span class="text-primary">Noche (Pico)</span>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Tables & Rankings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Staff Performance Ranking -->
        <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6">
            <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">groups_3</span> Rendimiento del Staff (Hoy)
            </h4>
            
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-white/5">
                        <th class="pb-4 font-bold">Mesero / Capitán</th>
                        <th class="pb-4 font-bold text-center">Órdenes</th>
                        <th class="pb-4 font-bold text-center">Ticket Prom.</th>
                        <th class="pb-4 font-bold text-right">Facturado</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="group hover:bg-white/5 transition-colors">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-primary/20 text-primary border border-primary/30 flex items-center justify-center font-bold text-xs">JP</div>
                                <div>
                                    <p class="font-bold text-white">Juan Pérez</p>
                                    <p class="text-[10px] text-primary uppercase font-bold tracking-widest flex items-center"><span class="material-symbols-outlined text-[12px] mr-1">star</span> Destacado</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-center font-medium text-slate-300">42</td>
                        <td class="py-4 text-center text-slate-500">$38.50</td>
                        <td class="py-4 text-right font-black text-emerald-400">$1,617.00</td>
                    </tr>
                    <tr class="group hover:bg-white/5 transition-colors border-t border-white/5">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-surface-container text-slate-400 border border-white/10 flex items-center justify-center font-bold text-xs">LM</div>
                                <div>
                                    <p class="font-bold text-white">Lorena M.</p>
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Mesera</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-center font-medium text-slate-300">35</td>
                        <td class="py-4 text-center text-slate-500">$40.20</td>
                        <td class="py-4 text-right font-bold text-white">$1,407.00</td>
                    </tr>
                    <tr class="group hover:bg-white/5 transition-colors border-t border-white/5">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-surface-container text-slate-400 border border-white/10 flex items-center justify-center font-bold text-xs">CG</div>
                                <div>
                                    <p class="font-bold text-white">Carlos G.</p>
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Cajero</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-center font-medium text-slate-300">85</td>
                        <td class="py-4 text-center text-slate-500">$18.50</td>
                        <td class="py-4 text-right font-bold text-white">$1,572.50</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Top Selling Items (Compact) -->
        <div class="bg-surface-container-low border border-white/5 rounded-2xl p-6">
            <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">local_fire_department</span> Categorías Más Vendidas
            </h4>
            
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-3 hover:bg-white/5 rounded-xl transition-colors border border-transparent hover:border-white/5 bg-surface-container">
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h5 class="text-sm font-bold text-white">Platos Fuertes</h5>
                            <span class="text-sm font-black text-white">$4,850.00</span>
                        </div>
                        <div class="w-full bg-surface-container-highest rounded-full h-1 mt-2">
                            <div class="bg-primary h-1 rounded-full shadow-[0_0_10px_rgba(234,188,78,0.5)]" style="width: 75%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-3 hover:bg-white/5 rounded-xl transition-colors border border-transparent hover:border-white/5 bg-surface-container">
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h5 class="text-sm font-bold text-white">Bebidas & Cocteles</h5>
                            <span class="text-sm font-black text-white">$3,200.50</span>
                        </div>
                        <div class="w-full bg-surface-container-highest rounded-full h-1 mt-2">
                            <div class="bg-secondary h-1 rounded-full" style="width: 55%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-3 hover:bg-white/5 rounded-xl transition-colors border border-transparent hover:border-white/5 bg-surface-container">
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h5 class="text-sm font-bold text-white">Entradas</h5>
                            <span class="text-sm font-black text-white">$1,800.00</span>
                        </div>
                        <div class="w-full bg-surface-container-highest rounded-full h-1 mt-2">
                            <div class="bg-emerald-500 h-1 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="w-full mt-6 py-3 text-xs font-bold text-primary border border-primary/20 hover:bg-primary/10 rounded-xl transition-all uppercase tracking-widest">Descargar Informe Completo</button>
        </div>
    </div>
</div>
@endsection
