@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Gestión de Usuarios</h2>
        <button id="btn-new" class="bg-primary px-4 py-2 rounded-lg text-on-primary-container font-bold">Nuevo Usuario</button>
    </div>

    <div id="alerts"></div>

    <div class="overflow-x-auto bg-surface-container p-4 rounded-xl">
        <table id="users-table" class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400">
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Nombre</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Modal (simple) -->
    <div id="modal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
        <div class="bg-surface-container-low p-6 rounded-xl w-full max-w-lg">
            <h3 id="modal-title" class="text-lg font-bold mb-4">Nuevo usuario</h3>
            <div class="space-y-3">
              <input id="u-name" class="w-full p-3 bg-surface rounded" placeholder="Nombre">
              <input id="u-email" class="w-full p-3 bg-surface rounded" placeholder="Email">
              <input id="u-password" class="w-full p-3 bg-surface rounded" placeholder="Contraseña (solo para crear/actualizar)">
              <select id="u-roles" class="w-full p-3 bg-surface rounded" multiple style="height:120px"></select>
            </div>
            <div class="mt-4 flex justify-end gap-3">
                <button id="modal-cancel" class="px-4 py-2 rounded-lg border">Cancelar</button>
                <button id="modal-save" class="px-4 py-2 rounded-lg bg-primary text-on-primary-container font-bold">Guardar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(async function(){
  const API = (window && window.location && window.location.origin) ? window.location.origin.replace(/:\/\/[0-9.]+/,'http://127.0.0.1:8000') + '/api' : '/api';
  const token = localStorage.getItem('auth_token');
  const headers = { 'Content-Type': 'application/json' };
  if (token) headers['Authorization'] = 'Bearer ' + token;

  const elTable = document.querySelector('#users-table tbody');
  const rolesSelect = document.getElementById('u-roles');
  const modal = document.getElementById('modal');
  const btnNew = document.getElementById('btn-new');
  const modalTitle = document.getElementById('modal-title');
  const inpName = document.getElementById('u-name');
  const inpEmail = document.getElementById('u-email');
  const inpPassword = document.getElementById('u-password');
  const btnSave = document.getElementById('modal-save');
  const btnCancel = document.getElementById('modal-cancel');
  const alerts = document.getElementById('alerts');

  let editingId = null;

  function showAlert(msg, type='success'){
    alerts.innerHTML = `<div class="p-3 mb-3 rounded ${type==='error'? 'bg-error/20 text-error' : 'bg-emerald-700/10 text-emerald-300'}">${msg}</div>`;
    setTimeout(()=> alerts.innerHTML = '', 4000);
  }

  async function loadUsers(){
    elTable.innerHTML = '<tr><td colspan="4" class="p-4">Cargando...</td></tr>';
    try{
      const res = await fetch('/api/users', { headers });
      if (!res.ok) throw new Error('Error cargando usuarios');
      const body = await res.json();
      const rows = body.data || body;
      elTable.innerHTML = rows.map(u => `
        <tr class="border-t border-white/5">
          <td class="px-3 py-2">${u.id}</td>
          <td class="px-3 py-2">${u.name}</td>
          <td class="px-3 py-2">${u.email}</td>
          <td class="px-3 py-2">
            <button data-edit="${u.id}" class="px-2 py-1 mr-2 bg-white/5 rounded">Editar</button>
            <button data-delete="${u.id}" class="px-2 py-1 bg-error/10 rounded">Eliminar</button>
          </td>
        </tr>
      `).join('');
    }catch(e){
      elTable.innerHTML = `<tr><td colspan="4" class="p-4">${e.message}</td></tr>`;
    }

      async function loadRolesList(){
        try{
          const res = await fetch('/api/roles', { headers });
          if (!res.ok) return;
          const list = await res.json();
          rolesSelect.innerHTML = list.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
        }catch(e){/* ignore */}
      }
  }

  function openModal(mode='new', user={}){
    editingId = mode === 'edit' ? user.id : null;
    modalTitle.textContent = mode === 'edit' ? 'Editar usuario' : 'Nuevo usuario';
    inpName.value = user.name || '';
    inpEmail.value = user.email || '';
    inpPassword.value = '';
    modal.classList.remove('hidden');
  }

  function closeModal(){ modal.classList.add('hidden'); }

  btnNew.addEventListener('click', () => openModal('new'));
  btnCancel.addEventListener('click', closeModal);

  btnSave.addEventListener('click', async () => {
    const name = inpName.value.trim();
    const email = inpEmail.value.trim();
    const password = inpPassword.value.trim();
    if (!name || !email) return showAlert('Nombre y email obligatorios', 'error');
    try{
      let res;
      const selectedRoles = Array.from(rolesSelect.selectedOptions).map(o => Number(o.value));
      if (editingId) {
        res = await fetch(`/api/users/${editingId}`, {
          method: 'PUT', headers, body: JSON.stringify({ name, email, ...(password?{password}:{} ), roles: selectedRoles })
        });
      } else {
        res = await fetch('/api/users', { method: 'POST', headers, body: JSON.stringify({ name, email, password, roles: selectedRoles }) });
      }
      const body = await res.json().catch(()=>({}));
      if (!res.ok) throw new Error(body.message || 'Operación fallida');
      showAlert(editingId ? 'Usuario actualizado' : 'Usuario creado');
      closeModal();
      await loadUsers();
    }catch(err){ showAlert(err.message || 'Error', 'error'); }
  });

  elTable.addEventListener('click', async (e) => {
    const edit = e.target.closest('[data-edit]');
    const del = e.target.closest('[data-delete]');
      if (edit) {
      const id = edit.getAttribute('data-edit');
      try{
        const res = await fetch(`/api/users/${id}`, { headers });
        if (!res.ok) throw new Error('No se pudo obtener usuario');
        const user = await res.json();
        openModal('edit', user);
        // set selected roles
        try{
          const rres = await fetch(`/api/users/${id}`, { headers });
          const u = await rres.json();
          const roleIds = (u.roles || []).map(r => r.id);
          Array.from(rolesSelect.options).forEach(o => { o.selected = roleIds.includes(Number(o.value)); });
        }catch(err){/* ignore */}
      }catch(err){ showAlert(err.message, 'error'); }
      return;
    }
    if (del) {
      const id = del.getAttribute('data-delete');
      if (!confirm('Eliminar usuario?')) return;
      try{
        const res = await fetch(`/api/users/${id}`, { method: 'DELETE', headers });
        if (!res.ok) throw new Error('Error al eliminar');
        showAlert('Usuario eliminado');
        await loadUsers();
      }catch(err){ showAlert(err.message, 'error'); }
    }
  });

  // initial load
  await loadRolesList();
  await loadUsers();
})();
</script>
@endpush
@endsection
