@extends('layouts.admin')

@section('title','Cocina - Pedidos')

@section('content')
<div class="p-8 max-w-[1600px] mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-white tracking-tight mb-2">Cocina — Pedidos</h1>
        <p class="text-sm text-slate-400 font-medium">Gestiona la preparación de cada producto por mesa.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div id="kitchen-list" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($orders as $o)
            @if($o->productos->count() > 0)
            <div class="rounded-2xl bg-surface-container-low border border-white/5 shadow-2xl overflow-hidden flex flex-col transition-all duration-300 hover:border-white/10 hover:shadow-primary/5">
                <!-- Header: Order Info -->
                <div class="p-6 border-b border-white/5 bg-surface-container-high/40 flex justify-between items-center">
                    <div>
                        <div class="flex items-center gap-3 mb-1.5">
                            <span class="bg-primary/20 text-primary px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest">Pedido #{{ $o->id }}</span>
                            <span class="text-xs text-slate-400 font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                {{ \Carbon\Carbon::parse($o->created_at ?? $o->fecha)->format('h:i A') }}
                            </span>
                        </div>
                        <h2 class="text-xl font-black text-white">
                            {{ str_contains(strtolower($o->mesa->nombre ?? ''), 'mesa') ? ($o->mesa->nombre ?? 'Sin Mesa') : 'Mesa ' . ($o->mesa->nombre ?? $o->mesa_id ?? 'Sin Mesa') }}
                        </h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-surface-container-highest flex items-center justify-center shadow-inner">
                        <span class="material-symbols-outlined text-primary">restaurant</span>
                    </div>
                </div>

                <!-- Body: Items List -->
                <div class="p-6 flex-1 flex flex-col gap-5">
                    @foreach($o->productos as $p)
                        @php
                            $tracking = $p->estatusTracking;
                            $status = $tracking ? strtolower($tracking->estatus) : 'pendiente';
                            
                            $statusColor = 'text-amber-400 bg-amber-400/10 border-amber-400/20'; // Pendiente
                            $statusBorder = 'border-l-4 border-l-amber-500/80';
                            $statusIcon = 'schedule';
                            if ($status === 'en preparacion') {
                                $statusColor = 'text-cyan-400 bg-cyan-400/10 border-cyan-400/20';
                                $statusBorder = 'border-l-4 border-l-cyan-500/80';
                                $statusIcon = 'skillet';
                            } elseif ($status === 'entregado') {
                                $statusColor = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
                                $statusBorder = 'border-l-4 border-l-emerald-500/80';
                                $statusIcon = 'check_circle';
                            }
                        @endphp
                        
                        <div class="flex flex-col gap-4 p-5 rounded-xl bg-surface-container-lowest border-y border-r border-white/5 shadow-md transition-all duration-300 {{ $statusBorder }}">
                            <!-- Item Info & Current Status -->
                            <div class="flex justify-between items-center gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center font-black text-sm text-primary shadow-inner">
                                        {{ intval($p->cantidad) }}x
                                    </div>
                                    <span class="font-bold text-white text-base tracking-tight">{{ $p->producto->nombre ?? $p->descripcion }}</span>
                                </div>
                                <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full border text-[9px] font-black uppercase tracking-widest {{ $statusColor }}">
                                    <span class="material-symbols-outlined text-[12px]">{{ $statusIcon }}</span>
                                    {{ $status }}
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-3 border-t border-white/5">
                                @if($status !== 'en preparacion' && $status !== 'entregado')
                                <form method="POST" action="{{ route('admin.cocina.item.status', ['id' => $p->id]) }}" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="estatus" value="en preparacion">
                                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2.5 rounded-lg bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 hover:bg-cyan-500 hover:text-on-primary transition-all hover:scale-[1.02] active:scale-[0.98] text-xs font-bold shadow-sm">
                                        <span class="material-symbols-outlined text-[16px]">skillet</span> Preparar
                                    </button>
                                </form>
                                @endif
                                
                                @if($status !== 'entregado')
                                <form method="POST" action="{{ route('admin.cocina.item.status', ['id' => $p->id]) }}" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="estatus" value="entregado">
                                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2.5 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-on-primary transition-all hover:scale-[1.02] active:scale-[0.98] text-xs font-bold shadow-sm">
                                        <span class="material-symbols-outlined text-[16px]">done_all</span> Entregado
                                    </button>
                                </form>
                                @endif
                                
                                @if($status === 'entregado')
                                <div class="flex-1 text-center py-2.5 text-xs font-bold text-slate-500 bg-white/5 rounded-lg border border-white/5 select-none">
                                    Completado
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center glass-card rounded-2xl border border-white/5">
                <span class="material-symbols-outlined text-6xl text-slate-600 mb-4">restaurant</span>
                <p class="text-lg text-slate-400 font-medium">No hay pedidos pendientes en cocina.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
