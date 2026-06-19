@extends('layouts.admin')

@section('title', 'Esencia Retro - Lista de Alquileres')

@section('content')
<div class="p-6 lg:p-8 flex-1">
    <header class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-white">Facturas (General)</h2>
            <p class="text-sm text-on-surface-variant">Total: {{ $facturas->total() }}</p>
        </div>
        <div class="flex items-center gap-4">
            <form method="GET" action="{{ route('admin.alquiler.list') }}" class="flex items-center gap-2">
                <select name="tipo" onchange="this.form.submit()" class="bg-surface-container-high border border-white/10 text-white text-xs rounded-lg px-3 py-2">
                    <option value="todos" {{ request('tipo') == 'todos' ? 'selected' : '' }}>Todas</option>
                    <option value="evento" {{ request('tipo') == 'evento' ? 'selected' : '' }}>Alquiler (Evento)</option>
                    <option value="pos" {{ request('tipo') == 'pos' ? 'selected' : '' }}>Punto de Venta (POS)</option>
                </select>
            </form>
            @if(!auth()->user()->rol || auth()->user()->rol->name !== 'cocina')
            <a href="{{ route('admin.alquiler') }}" class="px-4 py-2 rounded-xl bg-primary text-on-primary font-bold uppercase tracking-widest text-xs">Nuevo Alquiler</a>
            @endif
        </div>
    </header>

    @if(session('status'))
        <div class="mb-4 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-surface-container-low border border-white/5 rounded-2xl overflow-hidden shadow-2xl p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">
                        <th class="p-3">No. Orden</th>
                        <th class="p-3">Tipo</th>
                        <th class="p-3">Fecha</th>
                        <th class="p-3">Cliente</th>
                        <th class="p-3">Nit</th>
                        <th class="p-3 text-right">Monto</th>
                        <th class="p-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    @forelse($facturas as $f)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="p-3 font-semibold text-white">{{ $f->numero_orden }}</td>
                            <td class="p-3">
                                @if($f->tipo === 'pos')
                                    <span class="px-2 py-1 rounded-md bg-blue-500/20 text-blue-400 text-[10px] uppercase">POS</span>
                                @else
                                    <span class="px-2 py-1 rounded-md bg-purple-500/20 text-purple-400 text-[10px] uppercase">Alquiler</span>
                                @endif
                            </td>
                            <td class="p-3 text-slate-300">{{ $f->fecha }}</td>
                            <td class="p-3 text-slate-300">{{ $f->persona }}</td>
                            <td class="p-3 text-slate-300">{{ $f->nit }}</td>
                            <td class="p-3 text-right text-white font-semibold">{{ isset($f->monto_total) ? '$'.number_format($f->monto_total,0,',','.') : '$0' }}</td>
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Ver -->
                                    @if($f->tipo === 'pos')
                                        <button onclick="window.open('{{ route('admin.pos.receipt', $f->id) }}', '_blank', 'width=450,height=650')" class="px-3 py-1.5 rounded-lg bg-primary/10 border border-primary/20 text-primary text-xs font-bold uppercase tracking-wider hover:bg-primary hover:text-on-primary transition-all">Ver</button>
                                    @else
                                        <button onclick="verAlquiler('{{ $f->id }}')" class="px-3 py-1.5 rounded-lg bg-primary/10 border border-primary/20 text-primary text-xs font-bold uppercase tracking-wider hover:bg-primary hover:text-on-primary transition-all">Ver</button>
                                    @endif
                                    
                                    @if(!auth()->user()->rol || auth()->user()->rol->name !== 'cocina')
                                    <!-- Editar -->
                                    @if($f->tipo === 'pos' && $f->mesa_id)
                                        <a href="{{ route('admin.pedido', $f->mesa_id) }}" class="px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold uppercase tracking-wider hover:bg-amber-500 hover:text-white transition-all">Editar</a>
                                    @else
                                        <a href="{{ route('admin.alquiler.edit', $f->id) }}" class="px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold uppercase tracking-wider hover:bg-amber-500 hover:text-white transition-all">Editar</a>
                                    @endif

                                    <!-- Eliminar -->
                                    <form action="{{ route('admin.alquiler.delete', $f->id) }}" method="POST" onsubmit="return confirm('¿Eliminar factura #{{ $f->numero_orden }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold uppercase tracking-wider hover:bg-red-500 hover:text-white transition-all">Eliminar</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-sm text-slate-400">No hay facturas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-center gap-2">
            {{ $facturas->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function verAlquiler(id) {
        fetch('/api/alquiler/' + id)
            .then(res => res.json())
            .then(data => {
                sessionStorage.setItem('alquiler_view_invoice', JSON.stringify(data));
                window.location.href = '/admin/alquiler?view=1';
            })
            .catch(err => console.error(err));
    }
</script>
@endpush
