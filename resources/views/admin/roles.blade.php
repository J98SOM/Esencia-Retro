@extends('layouts.admin')

@section('title', 'Roles')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Gestión de Roles</h2>
        <button id="btn-new" class="bg-primary px-4 py-2 rounded-lg text-on-primary-container font-bold">Nuevo Rol</button>
    </div>

    <div id="alerts"></div>

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
            <tbody></tbody>
        </table>
    </div>

    <!-- Modal (simple) -->
    <div id="modal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
        <div class="bg-surface-container-low p-6 rounded-xl w-full max-w-lg">
            <h3 id="modal-title" class="text-lg font-bold mb-4">Nuevo rol</h3>
            <div class="space-y-3">
                <input id="r-name" class="w-full p-3 bg-surface rounded" placeholder="Nombre">
                <input id="r-desc" class="w-full p-3 bg-surface rounded" placeholder="Descripción (opcional)">
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
  const headers = { 'Content-Type': 'application/json' };
  const token = localStorage.getItem('auth_token');
  if (token) headers['Authorization'] = 'Bearer ' + token;

  const elTable = document.querySelector('#roles-table tbody');
  const modal = document.getElementById('modal');
  const btnNew = document.getElementById('btn-new');
  const modalTitle = document.getElementById('modal-title');
  const inpName = document.getElementById('r-name');
  const inpDesc = document.getElementById('r-desc');
  const btnSave = document.getElementById('modal-save');
  const btnCancel = document.getElementById('modal-cancel');
  const alerts = document.getElementById('alerts');

  let editingId = null;

  function showAlert(msg, type='success'){
    alerts.innerHTML = `<div class="p-3 mb-3 rounded ${type==='error'? 'bg-error/20 text-error' : 'bg-emerald-700/10 text-emerald-300'}">${msg}</div>`;
    setTimeout(()=> alerts.innerHTML = '', 4000);
  }

  async function loadRoles(){
    elTable.innerHTML = '<tr><td colspan="4" class="p-4">Cargando...</td></tr>';
    try{
      const res = await fetch('/api/roles', { headers });
      if (!res.ok) throw new Error('Error cargando roles');
      const body = await res.json();
      const rows = body.data || body;
      elTable.innerHTML = rows.map(r => `
        <tr class="border-t border-white/5">
          <td class="px-3 py-2">${r.id}</td>
          <td class="px-3 py-2">${r.name}</td>
          <td class="px-3 py-2">${r.description || ''}</td>
          <td class="px-3 py-2">
            <button data-edit="${r.id}" class="px-2 py-1 mr-2 bg-white/5 rounded">Editar</button>
            <button data-delete="${r.id}" class="px-2 py-1 bg-error/10 rounded">Eliminar</button>
          </td>
        </tr>
      `).join('');
    }catch(e){
      elTable.innerHTML = `<tr><td colspan="4" class="p-4">${e.message}</td></tr>`;
    }
  }

  function openModal(mode='new', role={}){
    editingId = mode === 'edit' ? role.id : null;
    modalTitle.textContent = mode === 'edit' ? 'Editar rol' : 'Nuevo rol';
    inpName.value = role.name || '';
    inpDesc.value = role.description || '';
    modal.classList.remove('hidden');
  }

  function closeModal(){ modal.classList.add('hidden'); }

  btnNew.addEventListener('click', () => openModal('new'));
  btnCancel.addEventListener('click', closeModal);

  btnSave.addEventListener('click', async () => {
    const name = inpName.value.trim();
    const description = inpDesc.value.trim();
    if (!name) return showAlert('Nombre obligatorio', 'error');
    try{
      let res;
      if (editingId) {
        res = await fetch(`/api/roles/${editingId}`, {
          method: 'PUT', headers, body: JSON.stringify({ name, description })
        });
      } else {
        res = await fetch('/api/roles', { method: 'POST', headers, body: JSON.stringify({ name, description }) });
      }
      const body = await res.json().catch(()=>({}));
      if (!res.ok) throw new Error(body.message || 'Operación fallida');
      showAlert(editingId ? 'Rol actualizado' : 'Rol creado');
      closeModal();
      await loadRoles();
    }catch(err){ showAlert(err.message || 'Error', 'error'); }
  });

  elTable.addEventListener('click', async (e) => {
    const edit = e.target.closest('[data-edit]');
    const del = e.target.closest('[data-delete]');
    if (edit) {
      const id = edit.getAttribute('data-edit');
      try{
        const res = await fetch(`/api/roles/${id}`, { headers });
        if (!res.ok) throw new Error('No se pudo obtener rol');
        const role = await res.json();
        openModal('edit', role);
      }catch(err){ showAlert(err.message, 'error'); }
      return;
    }
    if (del) {
      const id = del.getAttribute('data-delete');
      if (!confirm('Eliminar rol?')) return;
      try{
        const res = await fetch(`/api/roles/${id}`, { method: 'DELETE', headers });
        if (!res.ok) throw new Error('Error al eliminar');
        showAlert('Rol eliminado');
        await loadRoles();
      }catch(err){ showAlert(err.message, 'error'); }
    }
  });

  await loadRoles();
})();
</script>
@endpush
@endsection
