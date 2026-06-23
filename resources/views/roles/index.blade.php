@extends('layouts.admin')

@section('title', 'Roles')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Gestión de Roles</h2>
        <button id="btn-new" onclick="openNewModal()" class="bg-primary px-4 py-2 rounded-lg text-on-primary-container font-bold">Nuevo Rol</button>
    </div>

    @if(session('success'))
        <div class="p-3 mb-3 rounded bg-emerald-700/10 text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-3 mb-3 rounded bg-error/20 text-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto bg-surface-container p-4 rounded-xl">
        <table id="roles-table" class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400">
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Nombre</th>
                    <th class="px-3 py-2">Descripción</th>
                    <th class="px-3 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $r)
                    <tr class="border-t border-white/5">
                        <td class="px-3 py-2">{{ $r->id }}</td>
                        <td class="px-3 py-2">{{ $r->name }}</td>
                        <td class="px-3 py-2">{{ $r->description }}</td>
                        <td class="px-3 py-2 flex gap-2">
                            <button onclick="openEditModal({{ $r->id }}, '{{ addslashes($r->name) }}', '{{ addslashes($r->description) }}')" class="px-2 py-1 bg-white/5 rounded">Editar</button>
                            <form method="POST" action="{{ route('admin.roles.delete', ['id' => $r->id]) }}" onsubmit="return confirm('¿Eliminar rol?')">
                                @csrf
                                <button type="submit" class="px-2 py-1 bg-error/10 text-error rounded">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal (simple form POST) -->
    <div id="role-modal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
        <div class="bg-surface-container-low p-6 rounded-xl w-full max-w-lg">
            <h3 id="modal-title" class="text-lg font-bold mb-4">Nuevo rol</h3>
            <form id="role-form" method="POST" action="">
                @csrf
                <div class="space-y-3">
                    <input id="r-name" name="name" required class="w-full p-3 bg-surface rounded text-white" placeholder="Nombre">
                    <input id="r-desc" name="description" class="w-full p-3 bg-surface rounded text-white" placeholder="Descripción (opcional)">
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeRoleModal()" class="px-4 py-2 rounded-lg border text-white">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-on-primary-container font-bold">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const roleModal = document.getElementById('role-modal');
    const modalTitle = document.getElementById('modal-title');
    const roleForm = document.getElementById('role-form');
    const inpName = document.getElementById('r-name');
    const inpDesc = document.getElementById('r-desc');

    function openNewModal() {
        modalTitle.textContent = 'Nuevo rol';
        roleForm.action = "{{ route('admin.roles.store') }}";
        inpName.value = '';
        inpDesc.value = '';
        roleModal.classList.remove('hidden');
    }

    function openEditModal(id, name, description) {
        modalTitle.textContent = 'Editar rol';
        roleForm.action = "/admin/roles/" + id;
        inpName.value = name;
        inpDesc.value = description;
        roleModal.classList.remove('hidden');
    }

    function closeRoleModal() {
        roleModal.classList.add('hidden');
    }
</script>
@endsection
