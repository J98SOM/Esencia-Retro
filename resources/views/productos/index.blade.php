@extends('layouts.admin')

@section('title', 'Esencia Retro - Productos')

@section('content')
    <!-- Header Section -->
    <header class="px-6 md:px-10 py-6 md:py-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 md:gap-0">
        <div>
            <nav class="flex items-center space-x-2 text-xs text-on-surface-variant mb-2">
                <span>Gestión</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary">Productos</span>
            </nav>
            <h2 class="text-4xl font-black tracking-tight text-white">Gestión de Productos</h2>
            <p class="text-on-surface-variant mt-2">Control total del catálogo gastronómico de <span
                    class="text-primary font-semibold">Esencia Retro</span>.</p>
        </div>
        <button onclick="openModal('modal-add-product')"
            class="primary-gradient w-full md:w-auto px-6 py-3 md:px-8 md:py-4 rounded-xl text-on-primary font-bold shadow-lg shadow-primary/20 flex items-center justify-center space-x-2 transform transition-transform hover:scale-105 active:scale-95">
            <span class="material-symbols-outlined">add</span>
            <span>Nuevo Producto</span>
        </button>
    </header>

    @if(session('success'))
        <div class="px-6 md:px-10 mb-4">
            <div class="p-3 rounded bg-emerald-700/10 text-emerald-300">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="px-6 md:px-10 mb-4">
            <div class="p-3 rounded bg-error/20 text-error">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <!-- Filters & Navigation -->
    <section class="px-6 md:px-10 mb-8">
        <div class="overflow-x-auto pb-2 -mb-2 custom-scrollbar">
            <div id="productos-categories" class="flex items-center space-x-1 p-1 bg-surface-container-low rounded-xl w-fit min-w-max">
                <button data-cat="Todas" class="px-6 py-2 rounded-lg bg-surface-container-highest text-primary font-semibold text-sm">Todas</button>
                @foreach($productos->pluck('categoria')->unique()->filter() as $cat)
                    <button data-cat="{{ $cat }}" class="px-6 py-2 rounded-lg text-on-surface-variant hover:text-white text-sm">{{ $cat }}</button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Bento Grid of Products -->
    <section class="px-6 md:px-10 pb-20 flex-1">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div class="w-1/2">
                <input id="productos-search" type="text" placeholder="Buscar producto..." class="w-full bg-surface-container-low border border-outline-variant/10 rounded-xl py-3 pl-4 pr-4 focus:outline-none focus:ring-2 focus:ring-primary transition-all text-white" />
            </div>
            <div class="text-sm text-on-surface-variant">Resultados: <span id="productos-count">{{ $productos->count() }}</span></div>
        </div>

        <div id="productos-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            @foreach($productos as $p)
                <div class="product-item-card group relative overflow-hidden rounded-xl bg-surface-container-low p-4 transition-all hover:bg-surface-container-high" data-categoria="{{ $p->categoria }}" data-nombre="{{ strtolower($p->nombre) }}">
                    <div class="aspect-square rounded-lg overflow-hidden mb-4 relative bg-surface-container-highest">
                        @if($p->imagen_url)
                            <img src="{{ $p->imagen_url }}" alt="{{ $p->nombre }}" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110" />
                        @else
                            <div class="w-full h-full flex items-center justify-center text-on-surface-variant">Sin imagen</div>
                        @endif
                    </div>
                    <div class="flex flex-col gap-1 mb-2">
                        <h3 class="text-sm md:text-base font-bold text-white group-hover:text-primary transition-colors truncate" title="{{ $p->nombre }}">{{ $p->nombre }}</h3>
                        <span class="text-sm md:text-base font-black text-primary">${{ number_format($p->precio, 2) }}</span>
                    </div>
                    <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-4">{{ $p->categoria }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-white/5">
                        <div class="flex items-center text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm mr-1">inventory_2</span>
                            ID {{ $p->id }}
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="openEditModal({{ $p->id }}, '{{ addslashes($p->nombre) }}', {{ $p->precio }}, '{{ addslashes($p->categoria) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-white hover:bg-primary/20 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.productos.delete', ['id' => $p->id]) }}" onsubmit="return confirm('¿Eliminar producto?')">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-error hover:bg-error/20 transition-colors">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @push('modals')
        <!-- Añadir Producto Modal -->
        <div id="modal-add-product"
            class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-black text-white">Nuevo Producto</h3>
                <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="form-add-product" method="POST" action="{{ route('admin.productos.store') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div class="flex justify-center mb-6">
                    <div
                        onclick="document.getElementById('new-product-image').click()"
                        class="w-32 h-32 rounded-2xl bg-surface-container-highest border-2 border-dashed border-white/10 flex flex-col items-center justify-center text-outline hover:text-primary hover:border-primary/50 cursor-pointer transition-colors group">
                        <span
                            class="material-symbols-outlined text-3xl mb-1 group-hover:scale-110 transition-transform">add_a_photo</span>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-center mt-1">Subir<br>Imagen</span>
                    </div>
                    <input id="new-product-image" name="imagen" type="file" accept="image/*" class="hidden" />
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Nombre Comercial</label>
                    <input type="text" name="nombre" required
                        class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all"
                        placeholder="Ej. Hamburguesa Wagyu">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Precio Venta</label>
                        <input type="number" step="0.01" name="precio" required
                            class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all"
                            placeholder="$ 0.00">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Categoría</label>
                        <input name="categoria" type="text" required
                            class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="Categoría" value="Platos Fuertes">
                    </div>
                </div>
                <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                    <button type="button" onclick="closeModals()"
                        class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Guardar Producto</button>
                </div>
            </form>
        </div>

        <!-- Editar Producto Modal -->
        <div id="modal-edit-product"
            class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-black text-white">Editar Producto</h3>
                <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="form-edit-product" method="POST" action="" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div class="flex justify-center mb-6">
                    <div
                        onclick="document.getElementById('edit-product-image').click()"
                        class="w-32 h-32 rounded-2xl bg-surface-container-highest border-2 border-dashed border-white/10 flex flex-col items-center justify-center text-outline hover:text-primary hover:border-primary/50 cursor-pointer transition-colors group">
                        <span
                            class="material-symbols-outlined text-3xl mb-1 group-hover:scale-110 transition-transform">add_a_photo</span>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-center mt-1">Cambiar<br>Imagen</span>
                    </div>
                    <input id="edit-product-image" name="imagen" type="file" accept="image/*" class="hidden" />
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Nombre Comercial</label>
                    <input type="text" id="edit-nombre" name="nombre" required
                        class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Precio Venta</label>
                        <input type="number" step="0.01" id="edit-precio" name="precio" required
                            class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Categoría</label>
                        <input type="text" id="edit-categoria" name="categoria" required
                            class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all">
                    </div>
                </div>
                <div class="flex gap-3 pt-6 border-t border-white/10">
                    <button type="button" onclick="closeModals()"
                        class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-primary text-on-primary font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Actualizar</button>
                </div>
            </form>
        </div>
    @endpush

    @push('scripts')
    <script>
    (function(){
        const searchInput = document.getElementById('productos-search');
        const countEl = document.getElementById('productos-count');
        const gridItems = document.querySelectorAll('.product-item-card');
        const categoryButtons = document.querySelectorAll('#productos-categories button');
        let currentCategory = 'Todas';

        function filterList() {
            const query = searchInput.value.toLowerCase().trim();
            let count = 0;
            gridItems.forEach(item => {
                const nombre = item.getAttribute('data-nombre') || '';
                const categoria = item.getAttribute('data-categoria') || '';
                const matchesQuery = nombre.includes(query);
                const matchesCategory = (currentCategory === 'Todas') || (categoria === currentCategory);

                if (matchesQuery && matchesCategory) {
                    item.style.display = '';
                    count++;
                } else {
                    item.style.display = 'none';
                }
            });
            countEl.textContent = count;
        }

        searchInput.addEventListener('input', filterList);

        categoryButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                categoryButtons.forEach(b => b.classList.remove('bg-surface-container-highest', 'text-primary', 'font-semibold'));
                btn.classList.add('bg-surface-container-highest', 'text-primary', 'font-semibold');
                currentCategory = btn.getAttribute('data-cat');
                filterList();
            });
        });

        // Image preview handlers
        const newImgInput = document.getElementById('new-product-image');
        if (newImgInput) {
            newImgInput.addEventListener('change', () => {
                const area = newImgInput.closest('form').querySelector('.w-32');
                if (area && newImgInput.files && newImgInput.files[0]) {
                    const url = URL.createObjectURL(newImgInput.files[0]);
                    area.innerHTML = `<img src="${url}" class="w-full h-full object-cover rounded-2xl" />`;
                }
            });
        }

        const editImgInput = document.getElementById('edit-product-image');
        if (editImgInput) {
            editImgInput.addEventListener('change', () => {
                const area = editImgInput.closest('form').querySelector('.w-32');
                if (area && editImgInput.files && editImgInput.files[0]) {
                    const url = URL.createObjectURL(editImgInput.files[0]);
                    area.innerHTML = `<img src="${url}" class="w-full h-full object-cover rounded-2xl" />`;
                }
            });
        }

        window.openEditModal = function(id, nombre, precio, categoria) {
            const form = document.getElementById('form-edit-product');
            form.action = "/admin/productos/" + id;
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-precio').value = precio;
            document.getElementById('edit-categoria').value = categoria;
            openModal('modal-edit-product');
        };
    })();
    </script>
    @endpush
@endsection