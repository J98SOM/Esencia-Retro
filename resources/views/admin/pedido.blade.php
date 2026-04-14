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
            </h2>
            <p class="text-on-surface-variant text-sm mt-1">Añade o modifica los productos ordenados en esta mesa.</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="alert('Enviando instrucción a la impresora térmica...')" class="bg-surface-container-high hover:bg-surface-bright text-on-surface p-3 rounded-xl transition-colors font-bold flex items-center gap-2">
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
                <!-- Order Item -->
                <div class="bg-surface-container-highest/50 p-4 rounded-xl flex justify-between items-center border border-white/5 hover:border-white/10 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-surface-container-low rounded-lg flex items-center justify-center font-bold text-lg text-white">
                            2x
                        </div>
                        <div>
                            <p class="font-bold text-white text-lg">Hamburguesa Premium</p>
                            <p class="text-xs text-on-surface-variant flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">edit_note</span> Sin cebolla
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <p class="text-lg font-bold text-white">$25.98</p>
                        <button class="text-error hover:text-error-container p-2 rounded-lg bg-error/10 transition-colors">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>

                <!-- Order Item -->
                <div class="bg-surface-container-highest/50 p-4 rounded-xl flex justify-between items-center border border-white/5 hover:border-white/10 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-surface-container-low rounded-lg flex items-center justify-center font-bold text-lg text-white">
                            1x
                        </div>
                        <div>
                            <p class="font-bold text-white text-lg">Cerveza Artesanal</p>
                            <p class="text-xs text-on-surface-variant">Bebidas</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <p class="text-lg font-bold text-white">$5.99</p>
                        <button class="text-error hover:text-error-container p-2 rounded-lg bg-error/10 transition-colors">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
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
                        <span class="font-bold text-white">$31.97</span>
                    </div>
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Impuestos (10%)</span>
                        <span class="font-bold text-white">$3.20</span>
                    </div>
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Propina sugerida</span>
                        <span class="font-bold text-white">$3.20</span>
                    </div>
                </div>
                <div class="pt-6 border-t border-white/5 flex justify-between items-center mb-6">
                    <span class="text-lg font-bold text-on-surface">Total</span>
                    <span class="text-3xl font-black text-primary">$38.37</span>
                </div>
                <button onclick="window.location.href='{{ route('admin.checkout', ['id' => $mesaId ?? '4']) }}'" class="w-full py-4 rounded-xl bg-primary text-on-primary font-bold shadow-lg shadow-primary/20 hover:scale-[0.98] transition-all">
                    Cobrar Mesa
                </button>
            </div>
            
            <div class="bg-surface-container-low rounded-2xl p-6 border border-white/5 flex gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-emerald-400">person</span>
                </div>
                <div>
                    <h5 class="font-bold text-white text-sm">Elena Martínez</h5>
                    <p class="text-xs text-on-surface-variant">Mesera a cargo</p>
                </div>
            </div>
        </aside>
    </div>
</div>

@push('modals')
    <!-- Añadir Producto a la orden Modal -->
    <div id="modal-add-product-order" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-2xl shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-white">Menú de Productos</h3>
            <div class="flex gap-4 items-center">
                <input type="text" class="bg-surface-container-highest border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-primary transition-all w-48" placeholder="Buscar producto...">
                <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
        
        <div class="flex gap-4 mb-6 overflow-x-auto pb-2 scrollbar-hidden">
            <button class="px-4 py-1.5 rounded-full bg-primary text-on-primary text-xs font-bold whitespace-nowrap">Todas</button>
            <button class="px-4 py-1.5 rounded-full bg-surface-container-highest text-on-surface hover:text-white text-xs font-bold border border-white/5 whitespace-nowrap">Hamburguesas</button>
            <button class="px-4 py-1.5 rounded-full bg-surface-container-highest text-on-surface hover:text-white text-xs font-bold border border-white/5 whitespace-nowrap">Bebidas</button>
            <button class="px-4 py-1.5 rounded-full bg-surface-container-highest text-on-surface hover:text-white text-xs font-bold border border-white/5 whitespace-nowrap">Postres</button>
        </div>

        <div class="grid grid-cols-2 gap-4 max-h-80 overflow-y-auto pr-2">
            <!-- Items -->
            <div class="flex items-center gap-4 bg-surface p-3 rounded-xl border border-white/5 hover:border-primary/50 cursor-pointer group transition-all" onclick="closeModals()">
                <div class="w-12 h-12 rounded-lg overflow-hidden bg-surface-container-highest">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDx96IqLjluj-_LkE6-J1VCOJZZOqEmzEhiFj5jykJexJzTYwN5050gn5-YGneirui8k1vb9O4pJ7DXEbpFDKIWP4kNdoiyeq3zBUXCnK6BfP0pqFKPr4kGvCT0LltfiHstWVHjzVTEfySBxw2e5F_vONYOWFp6RUYA7ck_FjWxrnI1PTeuxX8BMRJOTdxykjyAqtJ5gbHJ_NVB8CWn6DhgXyMViX7DSaUKXIxr-wt-30ReyoPUSeoIXfbASLr99lpj46hul6SYGcxt" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <p class="font-bold text-white text-sm group-hover:text-primary transition-colors">Wagyu Burger</p>
                    <p class="text-xs text-primary font-bold">$18.50</p>
                </div>
                <span class="material-symbols-outlined text-outline group-hover:text-primary">add_circle</span>
            </div>
            
            <div class="flex items-center gap-4 bg-surface p-3 rounded-xl border border-white/5 hover:border-primary/50 cursor-pointer group transition-all" onclick="closeModals()">
                <div class="w-12 h-12 rounded-lg overflow-hidden bg-surface-container-highest">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAF8vyemrZ7-4rBUvJYl8yRd27IQdeMnmlLV30QhqAxXUsPQMTuo1nysyEyEZ7osY59Yji1743M2XNqwwBUzR9EbxLX9gpL57IvL1UV5ufJBLm4i-140ceRDlQXL2WDZlk6on3IpTp25TmaZV906d8EipbkI2NgLezfk5xamf-Whgtlv64y-9vNXFHB2QmUNn9_wArSwLfxmxoB3z0S1ylcgsl3dNBlhm0y6tGjwt2N2NS09W8X5eRApvQM9WLlnvZ9FjbSfnIqRM_c" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <p class="font-bold text-white text-sm group-hover:text-primary transition-colors">BBQ Ribs</p>
                    <p class="text-xs text-primary font-bold">$22.00</p>
                </div>
                <span class="material-symbols-outlined text-outline group-hover:text-primary">add_circle</span>
            </div>
        </div>
    </div>
@endpush

@endsection
