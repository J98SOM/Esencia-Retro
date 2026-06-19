@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Gestión de Usuarios</h2>
        <button id="btn-new" onclick="openNewModal()" class="bg-primary px-4 py-2 rounded-lg text-on-primary-container font-bold">Nuevo Usuario</button>
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
        <table id="users-table" class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400">
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Nombre</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Rol</th>
                    <th class="px-3 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr class="border-t border-white/5">
                        <td class="px-3 py-2">{{ $u->id }}</td>
                        <td class="px-3 py-2">{{ $u->name }}</td>
                        <td class="px-3 py-2">{{ $u->email }}</td>
                        <td class="px-3 py-2">{{ $u->rol->name ?? 'Sin Rol' }}</td>
                        <td class="px-3 py-2 flex gap-2">
                            <button onclick="openEditModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}', {{ $u->role_id ?? 'null' }})" class="px-2 py-1 bg-white/5 rounded">Editar</button>
                            <form method="POST" action="{{ route('admin.users.delete', ['id' => $u->id]) }}" onsubmit="return confirm('¿Eliminar usuario?')">
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
    <div id="user-modal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
        <div class="bg-surface-container-low p-6 rounded-xl w-full max-w-lg">
            <h3 id="modal-title" class="text-lg font-bold mb-4">Nuevo usuario</h3>
            <form id="user-form" method="POST" action="">
                @csrf
                <div class="space-y-3">
                    <input id="u-name" name="name" required class="w-full p-3 bg-surface rounded text-white" placeholder="Nombre">
                    <input id="u-email" name="email" type="email" required class="w-full p-3 bg-surface rounded text-white" placeholder="Email">
                    <input id="u-password" name="password" type="password" class="w-full p-3 bg-surface rounded text-white" placeholder="Contraseña">
                    <label class="text-xs text-slate-400 font-bold block mb-1">ROL</label>
                    <select id="u-role" name="role_id" required class="w-full p-3 bg-surface rounded text-white">
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeUserModal()" class="px-4 py-2 rounded-lg border text-white">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-on-primary-container font-bold">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const userModal = document.getElementById('user-modal');
    const modalTitle = document.getElementById('modal-title');
    const userForm = document.getElementById('user-form');
    const inpName = document.getElementById('u-name');
    const inpEmail = document.getElementById('u-email');
    const inpPassword = document.getElementById('u-password');
    const selectRole = document.getElementById('u-role');

    function openNewModal() {
        modalTitle.textContent = 'Nuevo usuario';
        userForm.action = "{{ route('admin.users.store') }}";
        inpName.value = '';
        inpEmail.value = '';
        inpPassword.value = '';
        inpPassword.placeholder = 'Contraseña';
        inpPassword.required = true;
        userModal.classList.remove('hidden');
    }

    function openEditModal(id, name, email, roleId) {
        modalTitle.textContent = 'Editar usuario';
        userForm.action = "/admin/users/" + id;
        inpName.value = name;
        inpEmail.value = email;
        inpPassword.value = '';
        inpPassword.placeholder = 'Contraseña (dejar en blanco para mantener)';
        inpPassword.required = false;
        if (roleId) selectRole.value = roleId;
        userModal.classList.remove('hidden');
    }

    function closeUserModal() {
        userModal.classList.add('hidden');
    }
</script>
@endsection
