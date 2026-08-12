@php
    $user = auth()->user();
    $roleName = strtolower(optional($user->rol)->name ?? '');
@endphp

<aside id="sidebar" class="h-screen w-64 fixed left-0 top-0 bg-[#050505] border-r border-white/5 flex flex-col py-6 z-50 transition-all duration-300">
    <!-- Toggles & Mobile Close -->
    <button onclick="toggleMobileMenu()" class="md:hidden absolute top-4 right-4 text-white hover:text-primary transition-colors">
        <span class="material-symbols-outlined">close</span>
    </button>

    <div class="px-6 mb-0 -mt-2 text-center flex flex-col items-center min-h-[100px] justify-center relative">
        <img id="sidebar-logo" src="{{ asset('img/logo.png') }}" alt="Esencia Retro Logo" class="w-56 h-auto mix-blend-screen opacity-100 object-contain -mb-6 transition-all duration-300">
        <img id="sidebar-icon" src="{{ asset('img/icon.png') }}" alt="Esencia Icon" class="w-10 h-auto mix-blend-screen opacity-100 object-contain absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden transition-all">
    </div>
    
    <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto custom-scrollbar overflow-x-hidden">
        <div class="mb-4 text-center">
            <button onclick="openModal('modal-new-order')" class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold py-3 rounded-xl shadow-lg shadow-primary/10 flex items-center justify-center gap-2 hover:scale-95 transition-all mb-6 nav-btn">
                <span class="material-symbols-outlined text-sm">add</span>
                <span class="sidebar-text tracking-normal">Nueva Orden</span>
            </button>
            <span class="px-4 font-['Inter'] uppercase tracking-widest text-[10px] text-slate-500 sidebar-text block text-left">Menú Principal</span>
        </div>
        
        <!-- Dashboard Tab -->
        @if(in_array($roleName, ['admin', 'mesero', 'cocina']))
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">dashboard</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Dashboard</span>
        </a>
        @endif
        
        <!-- Products Tab -->
        @if($roleName === 'admin')
        <a href="{{ route('admin.productos') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.productos') ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">inventory_2</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Productos</span>
        </a>
        @endif

        <!-- Inventory Tab -->
        @if($roleName === 'admin')
        <a href="{{ route('admin.inventario') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.inventario') ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">warehouse</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Inventario</span>
        </a>
        @endif

        <!-- Tables Tab -->
        @if(in_array($roleName, ['admin', 'mesero', 'cocina']))
        <a href="{{ route('admin.mesas') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.mesas') || request()->routeIs('admin.pedido') ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">table_restaurant</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Mesas</span>
        </a>
        @endif

        <!-- Kitchen / Cocina Tab -->
        @if(in_array($roleName, ['admin', 'cocina']))
        <a href="{{ route('admin.cocina') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.cocina') ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">restaurant_menu</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Cocina</span>
        </a>
        @endif

        <!-- Reports Tab -->
        @if($roleName === 'admin')
        <a href="{{ route('admin.reportes') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.reportes') ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">analytics</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Reportes</span>
        </a>
        @endif

        <!-- Rental Tab -->
        @if($roleName === 'admin')
        <a href="{{ route('admin.alquiler') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.alquiler') ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">celebration</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Alquiler Salón</span>
        </a>
        @endif

        <!-- Alquiler List Tab -->
        @if($roleName === 'admin')
        <a href="{{ route('admin.alquiler.list', ['tipo' => 'evento']) }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.alquiler.list') && request('tipo') === 'evento' ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">list_alt</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Alquiler List</span>
        </a>
        @endif

        <!-- Facturas Tab -->
        @if(in_array($roleName, ['admin','cocina']))
        <a href="{{ route('admin.alquiler.list') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.alquiler.list') && request('tipo') !== 'evento' ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">receipt_long</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Facturas</span>
        </a>
        @endif

        <!-- Users Tab -->
        @if($roleName === 'admin')
        <a href="{{ route('admin.users') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users') ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">group</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Usuarios</span>
        </a>
        @endif

        <!-- Roles Tab -->
        @if($roleName === 'admin')
        <a href="{{ route('admin.roles') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.roles') ? 'bg-primary/15 text-primary border-r-4 border-primary backdrop-blur-md rounded-l-xl' : 'text-slate-500 hover:bg-white/5 rounded-xl' }} transition-all group overflow-hidden">
            <span class="material-symbols-outlined group-hover:scale-110 transition-transform">admin_panel_settings</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Roles</span>
        </a>
        @endif
    </nav>

    <!-- Bottom Profile & Settings -->
    <div class="px-4 mt-auto border-t border-white/5 pt-6">
        <button onclick="toggleSidebar()" class="hidden md:flex w-full nav-link items-center gap-3 px-4 py-2 text-slate-500 hover:bg-white/5 hover:text-primary transition-all rounded-xl mb-4 overflow-hidden">
            <span class="material-symbols-outlined text-sm transition-transform duration-300" id="sidebar-toggle-icon">keyboard_double_arrow_left</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Contraer Menú</span>
        </button>

        <div class="nav-link px-4 py-3 flex items-center mb-6 cursor-pointer hover:bg-white/5 rounded-xl transition-all overflow-hidden" onclick="openModal('modal-profile')">
            <div class="w-10 h-10 min-w-[40px] rounded-full bg-surface-container-highest flex items-center justify-center border border-white/10 overflow-hidden mr-3">
                <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCiWenezqJGbD-1rlSh8Is3huor8fZxWZLXx3y1f9a9228ZWoq_mtHho3gXIPj4ssTtBWFIbYpYNH3Dd1P6GFV8jKd3ieKk7oWUP1EZauBfRGLv2b75v0aqlS4tkgPga-ISdrxYZ5PKQQgqkNq6Rkwzj6xARv3r09m8_tcR0OlRG4dnve3aGcpcepAINAsNgglD61KbQ_SYlkfogXTf4LEBGo_caiaVksVMNHv1ar1HblEJlraXJhRw-tq9Jp-T4lPGhucnStjGiYY4" alt="Operator"/>
            </div>
            <div class="sidebar-text opacity-100 transition-opacity">
                <p id="sidebar-profile-name" class="text-xs font-bold text-white whitespace-nowrap">{{ $user->name ?? 'Usuario' }}</p>
                <p id="sidebar-profile-role" class="text-[10px] text-slate-500 whitespace-nowrap">{{ ucfirst($roleName) }}</p>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link flex items-center gap-3 px-4 py-2 text-error/70 hover:bg-error/10 hover:text-error transition-all rounded-xl mt-4 overflow-hidden group">
            <span class="material-symbols-outlined text-sm group-hover:scale-110 transition-transform">logout</span>
            <span class="font-['Inter'] uppercase tracking-widest text-[10px] sidebar-text whitespace-nowrap">Cerrar Sesión</span>
        </a>
    </div>
</aside>
