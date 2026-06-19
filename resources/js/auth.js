const TOKEN_KEY = 'auth_token';
const USER_KEY = 'auth_user';
const ACTIVE_ROLE_KEY = 'active_role';

// Determine API base URL:
// Priority: global `window.VITE_API_URL` or `window.API_BASE` (injected from Blade) -> import.meta.env.VITE_API_URL (Vite) -> fallback to production backend URL
const _globalApi = (typeof window !== 'undefined' && (window.VITE_API_URL || window.API_BASE || window.__API_BASE)) ? (window.VITE_API_URL || window.API_BASE || window.__API_BASE) : null;
const API_BASE = _globalApi ? String(_globalApi).replace(/\/$/, '') : ((import.meta && import.meta.env && import.meta.env.VITE_API_URL) ? import.meta.env.VITE_API_URL.replace(/\/$/, '') : '/api');
try { console.debug('Auth API_BASE ->', API_BASE); } catch(e) {}

window.Auth = {
  user: null,
  token: null,
};

function saveToken(token) {
  window.Auth.token = token;
  localStorage.setItem(TOKEN_KEY, token);
}

function saveUser(user) {
  window.Auth.user = user;
  localStorage.setItem(USER_KEY, JSON.stringify(user));
}

function clearAuth() {
  window.Auth.user = null;
  window.Auth.token = null;
  localStorage.removeItem(TOKEN_KEY);
  localStorage.removeItem(USER_KEY);
}

function saveActiveRole(roleId) {
  if (roleId == null) {
    localStorage.removeItem(ACTIVE_ROLE_KEY);
    window.Auth.activeRole = null;
  } else {
    localStorage.setItem(ACTIVE_ROLE_KEY, String(roleId));
    window.Auth.activeRole = Number(roleId);
  }
}

function loadActiveRole() {
  const v = localStorage.getItem(ACTIVE_ROLE_KEY);
  if (v == null) return null;
  const n = Number(v);
  window.Auth.activeRole = Number.isNaN(n) ? null : n;
  return window.Auth.activeRole;
}

export async function login(email, password) {
  const res = await fetch(API_BASE + '/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password }),
  });

  if (!res.ok) {
    const body = await res.json().catch(() => ({}));
    throw new Error(body.message || 'Login failed');
  }

  const data = await res.json();
  if (data.token) saveToken(data.token);
  if (data.user) saveUser(data.user);
  // set default active role if none present
  const existing = localStorage.getItem(ACTIVE_ROLE_KEY);
  if (!existing && data.user && Array.isArray(data.user.roles) && data.user.roles.length) {
    saveActiveRole(data.user.roles[0].id);
  }
  return data;
}

export async function getMe() {
  const token = localStorage.getItem(TOKEN_KEY);
  if (!token) throw new Error('No token');
  const res = await fetch(API_BASE + '/me', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + token,
    },
  });
  if (!res.ok) {
    if (res.status === 401) clearAuth();
    throw new Error('Failed to fetch user');
  }
  const body = await res.json();
  if (body.user) saveUser(body.user);
  // ensure active role is present: if none stored, pick first role from server
  if (!localStorage.getItem(ACTIVE_ROLE_KEY) && body.user && Array.isArray(body.user.roles) && body.user.roles.length) {
    saveActiveRole(body.user.roles[0].id);
  }
  return body.user || body;
}

export async function logout() {
  const token = localStorage.getItem(TOKEN_KEY);
  if (token) {
    try {
      await fetch(API_BASE + '/logout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + token,
        },
      });
    } catch (e) {
      // ignore network errors but proceed to clear local state
    }
  }
  clearAuth();
  saveActiveRole(null);
  window.location.href = '/login';
}

export function isAuthenticated() {
  return !!localStorage.getItem(TOKEN_KEY);
}

export async function initAuth() {
  const token = localStorage.getItem(TOKEN_KEY);
  const user = localStorage.getItem(USER_KEY);
  if (token) {
    window.Auth.token = token;
    if (user) {
      try { window.Auth.user = JSON.parse(user); } catch (e) { window.Auth.user = null; }
    }
    loadActiveRole();
    try { await getMe(); } catch (e) { clearAuth(); }
  } else {
    if (!location.pathname.startsWith('/login')) {
      // redirect only if on protected UI
    }
  }
}

export function getActiveRole() { return window.Auth.activeRole || null; }
export function setActiveRole(roleId) { saveActiveRole(roleId); }

window.AuthService = { login, logout, getMe, initAuth, isAuthenticated, getActiveRole, setActiveRole };

export default window.AuthService;
