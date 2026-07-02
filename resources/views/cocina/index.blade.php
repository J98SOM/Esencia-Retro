@extends('layouts.admin')

@section('title','Cocina - Pedidos')

@section('content')
<div class="p-8 max-w-[1600px] mx-auto">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight mb-2">Cocina — Pedidos</h1>
            <p class="text-sm text-slate-400 font-medium">Gestiona la preparación de cada producto por mesa.</p>
        </div>
        <div>
            <button onclick="if(typeof window.playNotificationSound === 'function') { window.playNotificationSound(); }" class="px-5 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20 hover:bg-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2 font-bold text-xs uppercase tracking-wider shadow-sm">
                <span class="material-symbols-outlined text-base">volume_up</span> Probar Sonido
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div id="kitchen-list" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @include('cocina.partials.list')
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Beautiful dark-themed toast notification system
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toast-container') || (() => {
                const container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'fixed bottom-6 right-6 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none';
                document.body.appendChild(container);
                return container;
            })();

            const toast = document.createElement('div');
            toast.className = 'transform translate-y-4 opacity-0 transition-all duration-300 pointer-events-auto bg-surface-container-high/90 border border-white/10 rounded-2xl p-4 shadow-2xl flex items-center gap-3 backdrop-blur-md';
            
            let icon = 'notifications';
            let iconColor = 'text-primary';
            if (type === 'error') {
                icon = 'error';
                iconColor = 'text-error';
            } else if (type === 'success') {
                icon = 'check_circle';
                iconColor = 'text-emerald-400';
            } else if (type === 'info') {
                icon = 'info';
                iconColor = 'text-sky-400';
            }
            
            toast.innerHTML = `
                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center flex-shrink-0 ${iconColor}">
                    <span class="material-symbols-outlined">${icon}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white tracking-tight">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            `;
            
            toastContainer.appendChild(toast);
            
            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });
            
            // Auto dismiss
            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 6000);
        }

        if (window.Echo) {
            window.Echo.channel('pedidos-canal')
                .listen('.pedido.actualizado', (e) => {
                    console.log('Pedido actualizado recibido en cocina:', e);
                    
                    // Play sound chime instantly
                    if (typeof window.playNotificationSound === 'function') {
                        window.playNotificationSound();
                    }
                    
                    // Show a toast message to notify what changed
                    if (e.message) {
                        const alertType = e.tipoCambio === 'eliminado' ? 'error' : 'success';
                        showToast(e.message, alertType);
                    } else {
                        showToast('Pedido actualizado en cocina', 'info');
                    }
                    
                    // Fetch the updated DOM (only the partial list content)
                    fetch(window.location.pathname + '?partial=1')
                        .then(response => response.text())
                        .then(html => {
                            const currentList = document.getElementById('kitchen-list');
                            if (currentList) {
                                currentList.innerHTML = html;
                            }
                        })
                        .catch(err => console.error('Error al actualizar cocina:', err));
                });
        }
    });
</script>
@endpush
@endsection
