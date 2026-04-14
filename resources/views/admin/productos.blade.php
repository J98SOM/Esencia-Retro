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

    <!-- Filters & Navigation -->
    <section class="px-6 md:px-10 mb-8">
        <div class="overflow-x-auto pb-2 -mb-2 custom-scrollbar">
            <div class="flex items-center space-x-1 p-1 bg-surface-container-low rounded-xl w-fit min-w-max">
            <button
                class="px-6 py-2 rounded-lg bg-surface-container-highest text-primary font-semibold text-sm">Todas</button>
            <button
                class="px-6 py-2 rounded-lg text-on-surface-variant hover:text-white transition-colors text-sm">Entradas</button>
            <button class="px-6 py-2 rounded-lg text-on-surface-variant hover:text-white transition-colors text-sm">Platos
                Fuertes</button>
            <button
                class="px-6 py-2 rounded-lg text-on-surface-variant hover:text-white transition-colors text-sm">Bebidas</button>
            <button
                class="px-6 py-2 rounded-lg text-on-surface-variant hover:text-white transition-colors text-sm">Postres</button>
        </div>
        </div>
    </section>

    <!-- Bento Grid of Products -->
    <section class="px-6 md:px-10 pb-20 flex-1">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 md:gap-8">
            <!-- Card 1 -->
            <div
                class="group relative overflow-hidden rounded-xl bg-surface-container-low p-4 transition-all hover:bg-surface-container-high">
                <div class="aspect-square rounded-lg overflow-hidden mb-4 relative">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDKTf5QiAVGfx5b_opHJwGbDT3uTNpYh_XECxuoK0WIg1wFRuJIFs_fQ6VDWWMnHp-AsexPuPDK-PoTitZVyDlQyAZRSp6_3RH0vAOkwKxOZkURe1ucnS7v6HiGdL0xwxiHC2KeQxvVI5YVfguTHPHqVoJpBsiG1Y3I8QlcXD9yfWe0Sb4QVDpX1MufYNLLiYid5pMQfcSJ01EOL1WmIyBmCf9NjMtX4Q9hqCfU8CNxBNFsTw62TEKsdcrrr9yxvKQizRQ-XZnDrXAT"
                        alt="Hamburguesa Premium"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                    <div
                        class="absolute top-3 right-3 bg-secondary-container/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-on-secondary-container uppercase tracking-widest">
                        Best Seller
                    </div>
                </div>
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-lg font-bold text-white group-hover:text-primary transition-colors">Hamburguesa Premium
                    </h3>
                    <span class="text-lg font-black text-secondary-fixed-dim">$12.99</span>
                </div>
                <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-4">Platos Fuertes</p>
                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <div class="flex items-center text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm mr-1">schedule</span>
                        15-20 min
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="openModal('modal-edit-product')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-white hover:bg-primary/20 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div
                class="group relative overflow-hidden rounded-xl bg-surface-container-low p-4 transition-all hover:bg-surface-container-high">
                <div class="aspect-square rounded-lg overflow-hidden mb-4 relative">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuA41QVfAoPd1BHRzc7Eg-2m872otHV-ZG5qS0OB3GiYup1I2U1TsAiS5hSRVJBRBVoh6AxCe6XZQd4boDGCYtEzYyL-eQrjpnFVDOeIu_i3bYBC-yEsa4DXfLfjr4mZTESnDx9S0TJ1M1zlPbJ4zxbMNT8AgFN1UBAuDMq7fFwj3FFD7cc2kw8DsGFdSYaVqVrD9sjuE6jro5RnP6N3XYxwTVgoUG1XIEn7ILX9x7r2UgkiZWs7g_DVx9HmffZ3_cJGhstCX0IO8ihF"
                        alt="Ensalada César"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-lg font-bold text-white group-hover:text-primary transition-colors">Ensalada César</h3>
                    <span class="text-lg font-black text-secondary-fixed-dim">$8.50</span>
                </div>
                <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-4">Entradas</p>
                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <div class="flex items-center text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm mr-1">schedule</span>
                        10 min
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="openModal('modal-edit-product')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-white hover:bg-primary/20 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div
                class="group relative overflow-hidden rounded-xl bg-surface-container-low p-4 transition-all hover:bg-surface-container-high">
                <div class="aspect-square rounded-lg overflow-hidden mb-4 relative">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC154KauYhsHcqFQ_GrBSFFcsbqEImGWSTEczEptvI8RUgsF4N4HtdYhdrhsrzx6b6fFghhV8jLbJwowIRjVfX8an155oHQ2In-GeTpTYRu58fdNAIZEKVD4GUWD1r3oZli8I9r2SSBcQzNRe957hSnH6KXgVV6nOU6U0KJixYUwHT-B-xQzBuao8isiO9qu74MUJ2k0OdFiL5zIEvvjo_Vz3Mvj1bLTySsHIKlERQAUSv-ZhDBz-mQeggE1AHrNdxDLZqWMKm_QvLk"
                        alt="Cerveza Artesanal"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-lg font-bold text-white group-hover:text-primary transition-colors">Cerveza Artesanal
                    </h3>
                    <span class="text-lg font-black text-secondary-fixed-dim">$5.99</span>
                </div>
                <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-4">Bebidas</p>
                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <div class="flex items-center text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm mr-1">schedule</span>
                        3 min
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="openModal('modal-edit-product')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-white hover:bg-primary/20 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div
                class="group relative overflow-hidden rounded-xl bg-surface-container-low p-4 transition-all hover:bg-surface-container-high">
                <div class="aspect-square rounded-lg overflow-hidden mb-4 relative">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBFqOjaZ6v4uhrihiA5DeMiOsV0skkr4yOzNKuY-_TP_SB7D4fe2jj2HfjU1lpACu5TUVhjzGyUkZEnYFK_rNE40VgeWjoPmWs7l-BzhcCo2LGZQlc8t1jZSuBB3bPXVFg8r5LcOnvz99VHFcDsWhDHG6wcGsGbvEoTCkwc93aGOVNb1_AtbQwiHlHb6BQC2z8GQCFZG8zFKGhz7hwhK4QE8-Rt7wnve4gF1xXDd2fWMG5uwQJ12EKpjSwl5Qlcx0pfS0thLdHb0Bib"
                        alt="Pizza Margarita"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-lg font-bold text-white group-hover:text-primary transition-colors">Pizza Margarita</h3>
                    <span class="text-lg font-black text-secondary-fixed-dim">$14.99</span>
                </div>
                <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-4">Platos Fuertes</p>
                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <div class="flex items-center text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm mr-1">schedule</span>
                        12-15 min
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="openModal('modal-edit-product')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-white hover:bg-primary/20 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div
                class="group relative overflow-hidden rounded-xl bg-surface-container-low p-4 transition-all hover:bg-surface-container-high">
                <div class="aspect-square rounded-lg overflow-hidden mb-4 relative">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAlTvQ9KvHT_HYD-H7bdwSA7daBmAQV4SW4aM_JY6b7ZX_vbaPXaz6jhwTNf9LR34SkaxQDR90spRETsVVetXMS81EXu0i05cv8JnM6j5DxYe7KHhq4h_FlazKrJ49HkoydAiFNKKc3cCx5-PQqN9X3IagmijFTk9IeLQwUS3h7M-HxIvTvkud8fGy7BCSmBTET_MNMYnw1WGtt-BTAgKji2KTxyYXHhxpUFLVR6_KPV1peA5r1A75Yts6sTx75ggEHbyCSrRi-i7rg"
                        alt="Volcán de Chocolate"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                </div>
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-lg font-bold text-white group-hover:text-primary transition-colors">Volcán de Chocolate
                    </h3>
                    <span class="text-lg font-black text-secondary-fixed-dim">$7.25</span>
                </div>
                <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-4">Postres</p>
                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <div class="flex items-center text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm mr-1">schedule</span>
                        8 min
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="openModal('modal-edit-product')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-white hover:bg-primary/20 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty Add State -->
            <div onclick="openModal('modal-add-product')"
                class="group relative overflow-hidden rounded-xl border-2 border-dashed border-white/5 flex flex-col items-center justify-center p-8 transition-all hover:border-primary/40 hover:bg-primary/5 cursor-pointer">
                <div
                    class="w-16 h-16 rounded-full bg-surface-container-highest flex items-center justify-center mb-4 transition-colors group-hover:bg-primary/20">
                    <span
                        class="material-symbols-outlined text-3xl text-on-surface-variant group-hover:text-primary">add_circle</span>
                </div>
                <p class="font-bold text-on-surface-variant group-hover:text-white">Añadir Nuevo</p>
                <p class="text-xs text-slate-500 text-center mt-1">Sube una imagen y define los detalles del producto</p>
            </div>
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
            <form class="space-y-4">
                @csrf
                <div class="flex justify-center mb-6">
                    <div
                        class="w-32 h-32 rounded-2xl bg-surface-container-highest border-2 border-dashed border-white/10 flex flex-col items-center justify-center text-outline hover:text-primary hover:border-primary/50 cursor-pointer transition-colors group">
                        <span
                            class="material-symbols-outlined text-3xl mb-1 group-hover:scale-110 transition-transform">add_a_photo</span>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-center mt-1">Subir<br>Imagen</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Nombre
                        Comercial</label>
                    <input type="text" name="nombre"
                        class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all"
                        placeholder="Ej. Hamburguesa Wagyu" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Precio
                            Venta</label>
                        <input type="number" step="0.01" name="precio"
                            class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all"
                            placeholder="$ 0.00" required>
                    </div>
                    <div>
                        <label
                            class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Categoría</label>
                        <select name="categoria"
                            class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all appearance-none cursor-pointer">
                            <option value="Entradas">Entradas</option>
                            <option value="Platos Fuertes" selected>Platos Fuertes</option>
                            <option value="Bebidas">Bebidas</option>
                            <option value="Postres">Postres</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                    <button type="button" onclick="closeModals()"
                        class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Guardar
                        Producto</button>
                </div>
            </form>
        </div>

        <!-- Editar Producto Modal -->
        <div id="modal-edit-product"
            class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-black text-white">Editar Detalle</h3>
                <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form class="space-y-4">
                <div>
                    <label
                        class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Disponibilidad</label>
                    <div class="flex items-center gap-4 py-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" checked>
                            <div
                                class="w-11 h-6 bg-surface-container-highest rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                            </div>
                            <span class="ml-3 text-sm font-bold text-white">Activo en menú</span>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Actualizar
                            Precio</label>
                        <input type="number"
                            class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all"
                            value="12.99">
                    </div>
                </div>
                <div class="flex gap-3 pt-6 border-t border-white/10">
                    <button type="button" onclick="closeModals()"
                        class="flex-1 py-3 bg-primary text-on-primary font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Actualizar</button>
                </div>
            </form>
        </div>
    @endpush
@endsection