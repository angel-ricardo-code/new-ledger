const ICONS = {
  utensils: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>',
  car: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>',
  zap: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
  heart: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>',
  film: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/><path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/></svg>',
  'shopping-bag': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
  home: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
  briefcase: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
  book: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>',
  gift: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>',
  coffee: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>',
  'credit-card': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>',
  smartphone: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>',
  plane: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>',
  dumbbell: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6.5 6.5 11 11"/><path d="m21 21-1-1"/><path d="m3 3 1 1"/><path d="m18 22 4-4"/><path d="m2 6 4-4"/><path d="m3 10 7-7"/><path d="m14 21 7-7"/></svg>',
  music: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
  'paw-print': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg>',
  wallet: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>',
  'graduation-cap': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>',
  circle: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>',
  laptop: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m16 0H4m16 0 1.28 2.55a1 1 0 0 1-.9 1.45H3.62a1 1 0 0 1-.9-1.45L4 16"/></svg>',
  'trending-up': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
};
const getCategoryIcon = (icon, color = '#8E8E93') => ICONS[icon] || ICONS.circle;

const api = {
  parseError(res, text) {
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
      console.error('API Error', res.status, res.url, text);
    }
    try {
      const json = JSON.parse(text);
      if (json.message) return json.message;
      if (json.error) return json.error;
      if (json.errors) {
        const firstError = Object.values(json.errors)[0];
        return Array.isArray(firstError) ? firstError[0] : firstError;
      }
      return text;
    } catch (e) { return `Error ${res.status}`; }
  },
  async get(url) {
    const res = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'include' });
    if (!res.ok) { const text = await res.text(); throw new Error(this.parseError(res, text)); }
    return res.json();
  },
  async post(url, data) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(data)
    });
    if (!res.ok) { const text = await res.text(); throw new Error(this.parseError(res, text)); }
    return res.json();
  },
  async put(url, data) {
    const res = await fetch(url, {
      method: 'PUT',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(data)
    });
    if (!res.ok) { const text = await res.text(); throw new Error(this.parseError(res, text)); }
    return res.json();
  },
  async patch(url, data) {
    const res = await fetch(url, {
      method: 'PATCH',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(data)
    });
    if (!res.ok) { const text = await res.text(); throw new Error(this.parseError(res, text)); }
    return res.json();
  },
  async del(url) {
    const res = await fetch(url, { method: 'DELETE', headers: { 'Accept': 'application/json' }, credentials: 'include' });
    if (!res.ok) { const text = await res.text(); throw new Error(this.parseError(res, text)); }
    const text = await res.text();
    if (text) { try { return JSON.parse(text); } catch { return true; } }
    return true;
  },
  async csrf() {
    const res = await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
    if (!res.ok) { const text = await res.text(); throw new Error(this.parseError(res, text)); }
  },
  async getSilent(url) {
    const res = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'include' });
    if (!res.ok) throw new Error('');
    return res.json();
  }
};

const CURRENCIES = { CUP: 'CUP', USD: 'USD', EUR: 'EUR', MXN: 'MXN' };
let currentCurrency = localStorage.getItem('ledger_currency') || 'CUP';
let currentRate = 1;

const DENOMINATIONS = {
  CUP: [1000, 500, 200, 100, 50, 20, 10, 5, 3, 1],
  USD: [100, 50, 20, 10, 5, 2, 1, 0.25, 0.10, 0.05, 0.01],
  EUR: [500, 200, 100, 50, 20, 10, 5, 2, 1, 0.50, 0.20, 0.10, 0.05, 0.02, 0.01],
  MXN: [1000, 500, 200, 100, 50, 20, 10, 5, 2, 1, 0.50, 0.20, 0.10, 0.05],
};

const ui = {
  _toastTimer: null,
  toast(msg, duration = 3000) {
    const el = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    el.classList.remove('error');
    el.classList.add('show');
    clearTimeout(this._toastTimer);
    if (duration > 0) { this._toastTimer = setTimeout(() => el.classList.remove('show'), duration); }
  },
  toastError(msg) {
    const el = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    el.classList.add('error', 'show');
    clearTimeout(this._toastTimer);
  },
  formatMoney(amount) {
    const converted = parseFloat(amount) * currentRate;
    return converted.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + currentCurrency;
  },
  formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
  },
  formatMonth(year, month) {
    return new Date(year, month).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
  },
  formatMonthLabel(monthStr) {
    const [y, m] = monthStr.split('-');
    return new Date(y, m - 1).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
  }
};

function escapeHtml(str) {
  if (str == null) return '';
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

document.getElementById('toast-close').addEventListener('click', () => {
  document.getElementById('toast').classList.remove('show');
});

function showLoading(id) {
  document.getElementById('loading-' + id)?.style.removeProperty('display');
  document.getElementById('content-' + id)?.style.setProperty('display', 'none');
}
function hideLoading(id) {
  document.getElementById('loading-' + id)?.style.setProperty('display', 'none');
  document.getElementById('content-' + id)?.style.removeProperty('display');
}

function showLoadMore() {
  document.getElementById('tx-load-more').style.removeProperty('display');
}
function hideLoadMore() {
  document.getElementById('tx-load-more').style.setProperty('display', 'none');
}

function showAnalyticsLoading() {
  document.getElementById('analytics-loading-overlay').style.removeProperty('display');
}
function hideAnalyticsLoading() {
  document.getElementById('analytics-loading-overlay').style.setProperty('display', 'none');
}

function checkPasswordStrength(pw) {
  const checks = {
    length: pw.length >= 8,
    upper: /[A-Z]/.test(pw),
    lower: /[a-z]/.test(pw),
    number: /[0-9]/.test(pw),
    special: /[^a-zA-Z0-9]/.test(pw),
  };
  const score = Object.values(checks).filter(Boolean).length;
  const colors = ['', '#FF453A', '#FF9F0A', '#FFD60A', '#30D158', '#30D158'];
  const labels = ['', 'Débil', 'Media', 'Fuerte', 'Muy fuerte', 'Excelente'];
  return { checks, score, color: colors[score], label: labels[score] };
}

function updateStrengthMeter() {
  const pw = document.getElementById('reg-password').value;
  const s = checkPasswordStrength(pw);
  document.getElementById('strength-fill').style.width = (s.score / 5 * 100) + '%';
  document.getElementById('strength-fill').style.background = s.color;
  document.getElementById('strength-label').textContent = pw ? s.label : '';
  ['length', 'upper', 'lower', 'number', 'special'].forEach(k => {
    const el = document.getElementById('check-' + k);
    el.textContent = s.checks[k] ? '✓' : '✕';
    el.className = 'check' + (s.checks[k] ? ' pass' : '');
  });
}

const auth = {
  async init() {
    const splash = document.getElementById('splash');
    try {
      const user = await api.getSilent('/api/user');
      this.user = user;
      splash.classList.add('hidden');
      this.showApp();
      app.init();
      return;
    } catch {
      /* Not authenticated */
    }
    splash.classList.add('hidden');
    this.showWelcome();
  },
  showWelcome() {
    document.querySelectorAll('.auth-page').forEach(p => p.classList.remove('active'));
    document.getElementById('page-welcome').classList.add('active');
    document.querySelector('.fab')?.style.setProperty('display', 'none');
    document.querySelector('.tab-bar')?.style.setProperty('display', 'none');
    document.querySelector('.content')?.style.setProperty('display', 'none');
  },
  showLogin() {
    document.querySelectorAll('.auth-page').forEach(p => p.classList.remove('active'));
    document.getElementById('page-login').classList.add('active');
    document.getElementById('login-error').classList.remove('visible');
    document.getElementById('login-username').focus();
    document.querySelector('.fab')?.style.setProperty('display', 'none');
    document.querySelector('.tab-bar')?.style.setProperty('display', 'none');
    document.querySelector('.content')?.style.setProperty('display', 'none');
  },
  showRegister() {
    document.querySelectorAll('.auth-page').forEach(p => p.classList.remove('active'));
    document.getElementById('page-register').classList.add('active');
    document.getElementById('register-error').classList.remove('visible');
    document.getElementById('reg-username').focus();
    document.querySelector('.fab')?.style.setProperty('display', 'none');
    document.querySelector('.tab-bar')?.style.setProperty('display', 'none');
    document.querySelector('.content')?.style.setProperty('display', 'none');
  },
  showApp() {
    document.querySelectorAll('.auth-page').forEach(p => p.classList.remove('active'));
    document.querySelector('.fab')?.style.removeProperty('display');
    document.querySelector('.tab-bar')?.style.removeProperty('display');
    document.querySelector('.content')?.style.removeProperty('display');
  },
  async login() {
    const username = document.getElementById('login-username').value.trim();
    const password = document.getElementById('login-password').value;
    const errEl = document.getElementById('login-error');
    if (!username || !password) { errEl.textContent = 'Completa todos los campos'; errEl.classList.add('visible'); return; }
    if (!/^[a-zA-Z0-9_]+$/.test(username)) { errEl.textContent = 'El usuario solo puede contener letras, números y guiones bajos'; errEl.classList.add('visible'); return; }
    try {
      document.getElementById('btn-login').disabled = true;
      const res = await api.post('/api/login', { username, password });
      this.user = res.user;
      this.showApp();
      app.init();
    } catch (e) {
      errEl.textContent = e.message || 'Error al iniciar sesión';
      errEl.classList.add('visible');
      document.getElementById('btn-login').disabled = false;
    }
  },
  async register() {
    const username = document.getElementById('reg-username').value.trim();
    const email = document.getElementById('reg-email').value.trim() || null;
    const password = document.getElementById('reg-password').value;
    const confirm = document.getElementById('reg-password-confirm').value;
    const errEl = document.getElementById('register-error');
    const s = checkPasswordStrength(password);
    if (!username || username.length < 3) { errEl.textContent = 'El usuario debe tener al menos 3 caracteres'; errEl.classList.add('visible'); return; }
    if (!/^[a-zA-Z0-9_]+$/.test(username)) { errEl.textContent = 'El usuario solo puede contener letras, números y guiones bajos'; errEl.classList.add('visible'); return; }
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { errEl.textContent = 'Ingresa un correo válido o déjalo vacío'; errEl.classList.add('visible'); return; }
    if (!password || s.score < 3) { errEl.textContent = 'La contraseña es muy débil'; errEl.classList.add('visible'); return; }
    if (password !== confirm) { errEl.textContent = 'Las contraseñas no coinciden'; errEl.classList.add('visible'); return; }
    try {
      document.getElementById('btn-register').disabled = true;
      const res = await api.post('/api/register', { username, email, password, password_confirmation: confirm });
      this.user = res.user;
      this.showApp();
      app.init();
    } catch (e) {
      errEl.textContent = e.message || 'Error al registrarse';
      errEl.classList.add('visible');
      document.getElementById('btn-register').disabled = false;
    }
  },
  async logout() {
    try {
      await api.post('/api/logout');
    } catch { /* ignore */ }
    this.user = null;
    this.showWelcome();
  },
};
const modals = {
  open(id) { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; },
  close(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; },
  closeAll() { document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('show')); document.body.style.overflow = ''; }
};

document.querySelectorAll('[data-close]').forEach(btn => {
  btn.addEventListener('click', () => modals.closeAll());
});
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', (e) => { if (e.target === overlay) modals.closeAll(); });
});

document.getElementById('fab-btn').addEventListener('click', () => modals.open('modal-selector'));

// Icon selector
document.querySelectorAll('.icon-option').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.icon-option').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
  });
});

// ===== TABS =====
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.getElementById(btn.dataset.target).classList.add('active');
    if (btn.dataset.target === 'page-analytics') {
      if (!analyticsChartsReady) {
        requestAnimationFrame(() => initAnalyticsCharts());
      }
      requestAnimationFrame(() => {
        chartOverview?.resize();
        chartWeekday?.resize();
        chartDoughnut?.resize();
      });
    }
  });
});
document.querySelectorAll('.segment').forEach(seg => {
  seg.addEventListener('click', () => {
    document.querySelectorAll('.segment').forEach(s => s.classList.remove('active'));
    seg.classList.add('active');
    app.typeFilter = seg.dataset.filter;
    app.loadTransactions(true);
  });
});

// ===== CASH =====
const cash = {
  total: 0,
  _balanceCUP: 0,
  rates: null,
  init() {
    this.renderDenominations();
    document.getElementById('btn-reconcile').addEventListener('click', () => this.reconcile());
    document.getElementById('cash-reset').addEventListener('click', () => this.reset());
  },
  renderDenominations() {
    const grid = document.getElementById('denom-grid');
    const dens = DENOMINATIONS[currentCurrency] || DENOMINATIONS.CUP;
    grid.innerHTML = dens.map(d => `
      <div class="denom-item"><label>${d} ${currentCurrency}</label><input type="number" min="0" value="0" step="any" data-denom="${d}" class="denom-input"></div>
    `).join('');
    grid.querySelectorAll('.denom-input').forEach(inp => inp.addEventListener('input', () => this.calculate()));
  },
  calculate() {
    let totalDisplay = 0;
    document.querySelectorAll('.denom-input').forEach(inp => {
      totalDisplay += parseFloat(inp.value || 0) * parseFloat(inp.dataset.denom);
    });
    this.total = currentCurrency === 'CUP' ? totalDisplay : totalDisplay / currentRate;
    document.getElementById('cash-counted').textContent = ui.formatMoney(this.total);
    this.updateDiff();
  },
  updateDiff() {
    const diff = this._balanceCUP - this.total;
    const el = document.getElementById('cash-diff');
    el.textContent = (diff >= 0 ? '+' : '') + ui.formatMoney(Math.abs(diff));
    el.style.color = diff === 0 ? 'var(--green)' : Math.abs(diff) < 0.01 ? 'var(--green)' : 'var(--orange)';
  },
  setBalance(balance) {
    this._balanceCUP = balance;
    document.getElementById('cash-system-balance').textContent = ui.formatMoney(balance);
    this.updateDiff();
  },
  setLastRecon(recon) {
    const el = document.getElementById('cash-last-recon');
    if (recon) {
      const d = new Date(recon.date + 'T12:00:00');
      el.textContent = d.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
    } else {
      el.textContent = 'Nunca';
    }
  },
  setGlobalStats(balance, avg) {
    document.getElementById('cash-global-balance').textContent = ui.formatMoney(balance || 0);
    document.getElementById('cash-monthly-avg').textContent = ui.formatMoney(avg || 0);
  },
  async reconcile() {
    if (this.total <= 0) { ui.toast('Cuenta el efectivo primero'); return; }
    const note = document.getElementById('recon-note').value.trim();
    if (!confirm('¿Registrar reconciliación por ' + ui.formatMoney(this.total) + '?')) return;
    try {
      document.getElementById('btn-reconcile').disabled = true;
      document.getElementById('btn-reconcile').style.opacity = '0.5';
      await api.post('/api/reconciliation', { counted: this.total, note });
      ui.toast('Reconciliación registrada');
      document.getElementById('recon-note').value = '';
      invalidateMonth(app.month);
      await app.loadData();
      await this.loadHistory();
    } catch (e) { ui.toast('Error: ' + e.message); }
    finally {
      document.getElementById('btn-reconcile').disabled = false;
      document.getElementById('btn-reconcile').style.opacity = '1';
    }
  },
  async loadHistory() {
    try {
      const response = await api.get('/api/reconciliation');
      const container = document.getElementById('recon-history');
      const items = response.data || [];
      if (!items.length) {
        container.innerHTML = '<div class="empty-state">Sin reconciliaciones registradas</div>';
        return;
      }
      container.innerHTML = items.map(r => {
        const isSurplus = r.amount >= 0;
        const date = new Date(r.date + 'T12:00:00').toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
        return `
          <div class="recon-row">
            <div class="recon-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            <div class="recon-info">
              <div class="recon-date">${date}</div>
              <div class="recon-note">${escapeHtml(r.note ? r.note.replace('Reconciliación', '').replace(/^:?\s*/, '') : 'Sin detalle')}</div>
            </div>
            <div class="recon-amount ${isSurplus ? 'surplus' : 'shortage'}">${isSurplus ? '+' : ''}${ui.formatMoney(Math.abs(r.amount))}</div>
          </div>`;
      }).join('');
    } catch (e) { /* silently fail */ }
  },
  async loadRates() {
    try {
      const response = await api.get('/api/currency-rates');
      this.rates = response;
    } catch {
      this.rates = null;
    }
  },
  openRatesModal() {
    const usd = (this.rates?.USD?.rate_to_cup || 24);
    const eur = (this.rates?.EUR?.rate_to_cup || 26);
    const mxn = (this.rates?.MXN?.rate_to_cup || 1.2);
    document.getElementById('rate-usd').value = parseFloat(usd).toFixed(2);
    document.getElementById('rate-eur').value = parseFloat(eur).toFixed(2);
    document.getElementById('rate-mxn').value = parseFloat(mxn).toFixed(2);
    modals.open('modal-rates');
  },
  async saveRates() {
    const usd = parseFloat(parseFloat(document.getElementById('rate-usd').value).toFixed(2));
    const eur = parseFloat(parseFloat(document.getElementById('rate-eur').value).toFixed(2));
    const mxn = parseFloat(parseFloat(document.getElementById('rate-mxn').value).toFixed(2));
    if (!usd || !eur || !mxn) { ui.toast('Completa todas las tasas'); return; }
    try {
      const rates = [
        { currency: 'USD', rate_to_cup: usd },
        { currency: 'EUR', rate_to_cup: eur },
        { currency: 'MXN', rate_to_cup: mxn },
      ];
      this.rates = await api.put('/api/currency-rates', { rates });
      currentRate = currentCurrency === 'CUP' ? 1 : 1 / (this.rates[currentCurrency]?.rate_to_cup || 1);
      modals.closeAll();
      ui.toast('Tasas actualizadas');
      app.renderAll();
    } catch (e) { ui.toast('Error: ' + e.message); }
  },
  reset() {
    document.querySelectorAll('.denom-input').forEach(inp => inp.value = '0');
    document.getElementById('recon-note').value = '';
    this.calculate();
  }
};

// ===== CHARTS =====
let chartOverview, chartWeekday, chartDoughnut;
let analyticsChartsReady = false;
let overviewMonths = 6;
let topTxLimit = 100;
let heatmapYear = new Date().getFullYear();
let heatmapData = null;
const analyticsCache = { overview: null, top: null, weekday: null, dashboard: null };

const CACHE_TTL = 300000;
const pageCache = {};

function cacheSet(key, data) {
  pageCache[key] = { data, ts: Date.now() };
  const keys = Object.keys(pageCache);
  if (keys.length > 100) {
    const oldest = keys.reduce((a, b) => pageCache[a].ts < pageCache[b].ts ? a : b);
    delete pageCache[oldest];
  }
}

function cacheGet(key) {
  const entry = pageCache[key];
  if (entry && Date.now() - entry.ts < CACHE_TTL) return entry.data;
  return null;
}

function invalidateMonth(month) {
  const prefixes = ['dashboard:', 'top:', 'weekday:'];
  prefixes.forEach(p => delete pageCache[p + month]);
  Object.keys(pageCache).forEach(k => {
    if (k.startsWith('transactions:' + month)) delete pageCache[k];
  });
  delete pageCache['heatmap:' + month.split('-')[0]];
  delete pageCache['overview:' + overviewMonths];
  delete pageCache['forecast'];
}

function invalidateAll() {
  Object.keys(pageCache).forEach(k => delete pageCache[k]);
  analyticsCache = { overview: null, top: null, weekday: null, dashboard: null };
}

function initCharts() {
}

function initAnalyticsCharts() {
  if (typeof Chart === 'undefined') {
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
    s.onload = () => { createCharts(); applyCachedChartData(); };
    document.head.appendChild(s);
    return;
  }
  createCharts();
  applyCachedChartData();
}

function createCharts() {
  const chartOpts = (extra = {}) => ({
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { x: { grid: { display: false }, ticks: { color: '#8E8E93' } }, y: { display: false } },
    ...extra,
  });

  chartOverview = new Chart(document.getElementById('chartOverview'), {
    type: 'bar', data: { labels: [], datasets: [] },
    options: chartOpts(),
  });
  chartWeekday = new Chart(document.getElementById('chartWeekday'), {
    type: 'bar', data: { labels: [], datasets: [] },
    options: chartOpts({ scales: { x: { grid: { display: false }, ticks: { color: '#8E8E93' } }, y: { display: false, beginAtZero: true } } }),
  });
  chartDoughnut = new Chart(document.getElementById('chartDoughnut'), {
    type: 'doughnut', data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] },
    options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { color: '#FFFFFF', usePointStyle: true } } } }
  });

  analyticsChartsReady = true;
}

function applyCachedChartData() {
  if (analyticsCache.overview) updateOverviewChart(analyticsCache.overview);
  if (analyticsCache.weekday) updateWeekdayChart(analyticsCache.weekday);
  if (analyticsCache.dashboard) updateDoughnut(analyticsCache.dashboard);
}

function updateDoughnut(data) {
  if (!chartDoughnut) return;
  const cats = (data.category_series || []);
  if (!cats.length) { chartDoughnut.data.labels = []; chartDoughnut.data.datasets[0].data = []; chartDoughnut.data.datasets[0].backgroundColor = []; chartDoughnut.update(); return; }
  chartDoughnut.data.labels = cats.map(c => c.name);
  chartDoughnut.data.datasets[0].data = cats.map(c => c.total);
  chartDoughnut.data.datasets[0].backgroundColor = cats.map(c => c.color);
  chartDoughnut.update();
}

function updateOverviewChart(data) {
  if (!data || !data.length) return;
  chartOverview.data.labels = data.map(d => d.label);
  chartOverview.data.datasets = [
    { data: data.map(d => d.income), backgroundColor: '#30D158', borderRadius: 3, label: 'Ingresos' },
    { data: data.map(d => d.expense), backgroundColor: '#FF453A', borderRadius: 3, label: 'Gastos' },
  ];
  chartOverview.update();
}

function updateWeekdayChart(data) {
  if (!data || !data.length) return;
  chartWeekday.data.labels = data.map(d => d.label);
  chartWeekday.data.datasets = [{ data: data.map(d => d.total), backgroundColor: data.map(d => d.total > 0 ? '#FF4530' : 'rgba(255,69,48,0.2)'), borderRadius: 3 }];
  chartWeekday.update();
}

function renderKPIs(data) {
  const kpi = data.kpi;
  if (!kpi) return;
  const grid = document.getElementById('kpi-grid');
  grid.innerHTML = `
    <div class="kpi-card">
      <div class="kpi-label">Gasto diario promedio</div>
      <div class="kpi-val" style="color:var(--orange)">${ui.formatMoney(kpi.avg_daily_expense)}</div>
      <div class="kpi-sub">${kpi.days_without_expenses} días sin gastos</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Promedio diario histórico</div>
      <div class="kpi-val" style="color:var(--accent)">${ui.formatMoney(kpi.historical_avg_daily_expense)}</div>
      <div class="kpi-sub">Desde el primer registro</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Categoría más cara</div>
      <div class="kpi-val" style="color:${kpi.top_expense_category?.color || 'var(--secondary)'}">${kpi.top_expense_category ? ui.formatMoney(kpi.top_expense_category.amount) : 'N/A'}</div>
      <div class="kpi-sub">${escapeHtml(kpi.top_expense_category?.name || 'Sin datos')}</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Mayor gasto del día</div>
      <div class="kpi-val" style="color:var(--red)">${kpi.biggest_spending_day ? ui.formatMoney(kpi.biggest_spending_day.total) : 'N/A'}</div>
      <div class="kpi-sub">${kpi.biggest_spending_day ? new Date(kpi.biggest_spending_day.date + 'T12:00:00').toLocaleDateString('es-ES', { day: 'numeric', month: 'long' }) : 'Sin datos'}</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Transacción más grande</div>
      <div class="kpi-val" style="color:var(--red)">${kpi.biggest_transaction ? ui.formatMoney(kpi.biggest_transaction.amount) : 'N/A'}</div>
      <div class="kpi-sub">${escapeHtml(kpi.biggest_transaction?.note || kpi.biggest_transaction?.category?.name || '')}</div>
    </div>
  `;
}

async function loadHeatmap() {
  const cached = cacheGet('heatmap:' + heatmapYear);
  if (cached) { heatmapData = cached; renderHeatmap(); return; }
  try {
    heatmapData = await api.get(`/api/analytics/heatmap?year=${heatmapYear}`);
    cacheSet('heatmap:' + heatmapYear, heatmapData);
    renderHeatmap();
  } catch { /* ignore */ }
}

function renderHeatmap() {
  const grid = document.getElementById('heatmap-grid');
  if (!heatmapData) return;
  document.getElementById('heatmap-year').textContent = heatmapYear;
  const maxExpense = Math.max(...heatmapData.map(d => d.expense), 1);
  const firstDay = (new Date(heatmapYear, 0, 1).getDay() + 6) % 7;
  const totalCols = Math.ceil((firstDay + heatmapData.length) / 7);
  const days = ['lun', 'mar', 'mié', 'jue', 'vie', 'sáb', 'dom'];
  const monthNames = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];

  grid.style.gridTemplateColumns = `28px repeat(${totalCols},14px)`;
  grid.style.gridTemplateRows = 'auto repeat(7,14px)';

  const els = [];

  const seenCols = new Set();
  for (let m = 0; m < 12; m++) {
    const doy = Math.floor((new Date(heatmapYear, m, 1) - new Date(heatmapYear, 0, 1)) / 86400000);
    const col = Math.floor((doy + firstDay) / 7);
    if (seenCols.has(col)) continue;
    seenCols.add(col);
    const lastDOY = m < 11 ? Math.floor((new Date(heatmapYear, m + 1, 1) - new Date(heatmapYear, 0, 1)) / 86400000) - 1 : heatmapData.length - 1;
    const lastCol = Math.floor((lastDOY + firstDay) / 7);
    const span = lastCol - col + 1;
    els.push(`<div class="ml" style="grid-row:1;grid-column:${col + 2}/span ${span}">${monthNames[m]}</div>`);
  }

  for (let d = 0; d < 7; d++) {
    els.push(`<div class="dl" style="grid-row:${d + 2};grid-column:1">${days[d]}</div>`);
  }

  for (let i = 0; i < heatmapData.length; i++) {
    const d = heatmapData[i];
    const row = ((firstDay + i) % 7) + 2;
    const col = Math.floor((firstDay + i) / 7) + 2;
    const ratio = d.expense / maxExpense;
    const level = !ratio ? '' : ratio < 0.25 ? 'l1' : ratio < 0.5 ? 'l2' : ratio < 0.75 ? 'l3' : 'l4';
    const title = `${d.date}: $${ui.formatMoney(d.expense)} en gastos`;
    els.push(`<div class="cell ${level}" style="grid-row:${row};grid-column:${col}" title="${title}" data-index="${i}"></div>`);
  }

  grid.innerHTML = els.join('');
}

function updateHeatmapCell(dateStr, deltaExpense) {
  if (!heatmapData || !heatmapData.length) return;
  const idx = heatmapData.findIndex(d => d.date === dateStr);
  if (idx === -1) return;
  const oldExpense = heatmapData[idx].expense;
  const newExpense = Math.max(0, oldExpense + deltaExpense);
  heatmapData[idx].expense = newExpense;
  const grid = document.getElementById('heatmap-grid');
  if (!grid) return renderHeatmap();
  const cell = grid.querySelector(`.cell[data-index="${idx}"]`);
  if (!cell) return renderHeatmap();
  const maxExpense = Math.max(...heatmapData.map(d => d.expense), 1);
  const ratio = newExpense / maxExpense;
  const level = !ratio ? '' : ratio < 0.25 ? 'l1' : ratio < 0.5 ? 'l2' : ratio < 0.75 ? 'l3' : 'l4';
  cell.className = `cell ${level}`;
  cell.title = `${dateStr}: $${ui.formatMoney(newExpense)} en gastos`;
}

function renderTopTransactions(data) {
  const container = document.getElementById('top-transactions-container');
  if (!data) { container.innerHTML = ''; return; }

  const noData = !data.top_expense?.length && !data.top_income?.length;
  if (noData) { container.innerHTML = '<div class="empty-state" style="margin-top:0">Sin transacciones este mes</div>'; return; }

  const renderStack = (items, isExpense) => {
    if (!items || !items.length) return '';
    const sorted = [...items].sort((a, b) => Math.abs(b.amount) - Math.abs(a.amount));
    const visibleCount = Math.min(3, sorted.length);
    const hiddenCount = sorted.length - visibleCount;

    const cards = sorted.map((t, i) => {
      const color = t.category?.color_hex || (isExpense ? 'var(--red)' : 'var(--green)');
      const icon = t.category?.icon || 'circle';
      const date = new Date(t.date + 'T12:00:00').toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
      const desc = escapeHtml(t.note || t.category?.name || 'Sin descripción');
      const catName = t.category ? escapeHtml(t.category.name) : '';
      const sign = isExpense ? '-' : '+';
      const amt = ui.formatMoney(t.amount);
      const isHidden = i >= visibleCount;
      const catHtml = catName ? `<div class="tt-cat">${catName}</div>` : '';
      return `<div class="card-item${isHidden ? ' hidden-card' : ''}" tabindex="0" data-id="${t.id}">
        <span class="card-icon" style="color:${color};background:${color}22">${getCategoryIcon(icon, color)}</span>
        <span class="card-date">${date}</span>
        <div class="card-tooltip">${catHtml}<div class="tt-date">${date}</div><div class="tt-desc">${desc}</div><div class="tt-amount" style="color:${isExpense ? 'var(--red)' : 'var(--green)'}">${sign}${amt}</div></div>
      </div>`;
    }).join('');

    const btn = hiddenCount > 0 ? `<button class="expand-btn">+${hiddenCount}</button>` : '';
    return `<div class="ledger-stack"><div class="ledger-stack__label">${isExpense ? 'Gastos' : 'Ingresos'}</div><div class="ledger-stack__cards">${cards}${btn}</div></div>`;
  };

  container.innerHTML = `<div class="tx-split">
    ${renderStack(data.top_expense, true)}
    ${renderStack(data.top_income, false)}
  </div>`;
}

function renderForecast(data) {
  const section = document.getElementById('forecast-section');
  const container = document.getElementById('forecast-container');
  if (!data || data.method === 'insufficient_data' || !data.predictions?.length) {
    if (section) section.style.display = '';
    container.innerHTML = '<div class="apple-card" style="padding:20px;text-align:center;color:var(--secondary);font-weight:700;animation:blink 2s ease-in-out infinite">Datos insuficientes — necesitas al menos 6 meses de gastos para generar una predicción.</div>';
    return;
  }
  section.style.display = '';

  const methodLabels = { 'holt-winters': 'Holt-Winters', 'holt-linear': 'Tendencia lineal', 'moving-average': 'Media móvil' };
  const methodTitles = { 'holt-winters': 'Holt-Winters con estacionalidad', 'holt-linear': 'Holt lineal sin estacionalidad', 'moving-average': 'Media móvil' };
  const methodShort = { 'holt-winters': 'HW', 'holt-linear': 'HL', 'moving-average': 'MM' };
  const methodLabel = methodLabels[data.method] || data.method;
  const methodTitle = methodTitles[data.method] || data.method;
  const methodShortLabel = methodShort[data.method] || data.method;
  const next = data.predictions[0];
  const maxPred = Math.max(...data.predictions.map(p => p.predicted));
  const trendPct = data.predictions.length >= 2
    ? ((data.predictions[data.predictions.length - 1].predicted - data.predictions[0].predicted) / data.predictions[0].predicted * 100)
    : 0;
  const trendSign = trendPct >= 0 ? '+' : '';
  const markerLeft = next.upper > next.lower
    ? ((next.predicted - next.lower) / (next.upper - next.lower)) * 100
    : 50;

  const svgSparkle = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l1.5 5h5l-4 3 1.5 5-4-3-4 3 1.5-5-4-3h5z"/></svg>';
  const svgTrend = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,20 6,14 10,18 14,8 18,12 22,4"/></svg>';
  const svgBrain = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v4m0 12v4m-6-8H2m20 0h-4"/></svg>';
  const svgBulb = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 21h4"/><path d="M12 2a7 7 0 0 0-3.5 13.1A4 4 0 0 1 10 18h4a4 4 0 0 1 1.5-2.9A7 7 0 0 0 12 2z"/></svg>';

  container.innerHTML = `
    <div style="position:relative;z-index:10">
      <div class="forecast-animate-slide" style="margin-bottom:1.5rem">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem">
          <h2 style="font-size:1.125rem;font-weight:700;color:#ffffff;letter-spacing:-0.01em">Pron\u00f3stico de Gastos</h2>
          <div class="forecast-badge-ai" title="${methodTitle}">
            ${svgBrain}
            <span>Método ${methodShortLabel}</span>
          </div>
        </div>
        <p style="font-size:12px;color:var(--secondary)">Basado en ${data.total_months} meses de datos hist\u00f3ricos</p>
      </div>

      <div class="forecast-glass-card forecast-prediction-main forecast-shine forecast-animate-slide forecast-delay-1">
        <div class="forecast-glow-bg"></div>
        <div style="position:relative;z-index:2">
          <div class="forecast-header-row">
            <div style="display:flex;align-items:center;gap:0.625rem">
              <div class="forecast-icon-box fc-orange">${svgSparkle}</div>
              <span style="font-size:12px;font-weight:500;color:var(--secondary)">Estimado pr\u00f3ximo mes</span>
            </div>
            <div class="forecast-badge-ai">
              ${svgTrend}
              <span>${methodLabel}</span>
            </div>
          </div>
          <div class="forecast-amount-row forecast-animate-count forecast-delay-2">
            <span class="forecast-amount">${ui.formatMoney(next.predicted)}</span>
            <span class="forecast-currency">${currentCurrency}</span>
          </div>
          <div class="forecast-confidence-section">
            <div class="forecast-confidence-labels">
              <span class="fc-label">Rango de confianza (95%)</span>
              <span class="fc-value">\u00b1${ui.formatMoney(data.mae)}</span>
            </div>
            <div class="forecast-bar-container">
              <div class="forecast-bar-segment forecast-bar-low"></div>
              <div class="forecast-bar-segment forecast-bar-mid"></div>
              <div class="forecast-bar-segment forecast-bar-high"></div>
              <div class="forecast-bar-marker" style="left:${markerLeft}%"></div>
            </div>
            <div class="forecast-confidence-legend">
              <span class="fc-min">${ui.formatMoney(next.lower)}</span>
              <span class="fc-est">${ui.formatMoney(next.predicted)}</span>
              <span class="fc-max">${ui.formatMoney(next.upper)}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="forecast-month-grid forecast-animate-slide forecast-delay-3">
        ${data.predictions.slice(0, 3).map((p, i) => {
          const barWidth = maxPred > 0 ? (p.predicted / maxPred) * 100 : 0;
          const delay = 'forecast-delay-' + (i + 2);
          const active = i === 0 ? 'fc-active' : '';
          return '<div class="forecast-glass-card forecast-month-card ' + active + ' forecast-shine forecast-animate-count ' + delay + '">'
            + '<div class="fc-m-label">' + escapeHtml(p.label) + '</div>'
            + '<div class="fc-m-amount">' + ui.formatMoney(p.predicted) + '</div>'
            + '<div class="fc-m-variance">\u00b1' + ui.formatMoney(data.mae) + '</div>'
            + '<div class="fc-mini-bar">'
            + '<div class="fc-mini-bar-fill fc-animated" style="width:' + barWidth + '%"></div>'
            + '</div></div>';
        }).join('')}
      </div>

      <div class="forecast-glass-card forecast-insight-card forecast-animate-slide forecast-delay-4">
        <div class="forecast-insight-icon">${svgBulb}</div>
        <div>
          <div class="forecast-insight-title">Tendencia detectada</div>
          <p class="forecast-insight-text">
            Se observa ${trendPct >= 0 ? 'un incremento' : 'una disminuci\u00f3n'} gradual de <span class="forecast-insight-highlight">${trendSign}${trendPct.toFixed(2)}%</span> mensual.
            ${data.total_months < 24 ? 'Proyecci\u00f3n basada en ' + data.total_months + ' meses de datos.' : ''}
          </p>
        </div>
      </div>
    </div>`;
}

// ===== APP =====
const app = {
  transactions: [], categories: [], month: '', typeFilter: 'all',
  currentPage: 1, lastPage: 1, loadingMore: false,
  editingTransactionId: null, editingCategoryId: null,
  searchQuery: '', _monthTimer: null,
  renderAll() {
    const dashboard = analyticsCache.dashboard;
    if (dashboard) {
      this.renderSummary(dashboard);
      this.renderCategoryList(dashboard);
      cash.setBalance(dashboard.balance);
      cash.setGlobalStats(dashboard.global_balance, dashboard.monthly_avg);
      renderKPIs(dashboard);
    }
    this.renderTimeline();
    if (analyticsCache.top) renderTopTransactions(analyticsCache.top);
  },
  async init() {
    this.month = new Date().toISOString().slice(0, 7);
    cash.init(); initCharts();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tx-date').value = today;
    document.getElementById('tx-date').max = today;
    this.updateMonthLabels();
    document.getElementById('currency-select').value = currentCurrency;
    await cash.loadRates();
    currentRate = currentCurrency === 'CUP' ? 1 : 1 / ((cash.rates?.[currentCurrency]?.rate_to_cup) || 1);
    await this.loadData();
    loadHeatmap();
    await cash.loadHistory();
    hideLoading('home');
    hideLoading('analytics');
    hideLoading('cash');
  },
  async loadData(backgroundAnalytics = false) {
    showLoading('home');
    try {
      let categories = cacheGet('categories');
      let dashboard = cacheGet('dashboard:' + this.month);
      if (!categories || !dashboard) {
        const [freshCategories, freshDashboard] = await Promise.all([
          categories ? null : api.get('/api/categories'),
          dashboard ? null : api.get(`/api/dashboard?month=${this.month}`)
        ]);
        if (freshCategories) { categories = freshCategories; cacheSet('categories', categories); }
        if (freshDashboard) { dashboard = freshDashboard; cacheSet('dashboard:' + this.month, dashboard); }
      }
      this.categories = categories;
      this.budgets = dashboard.budgets || [];
      cash.setBalance(dashboard.balance);
      cash.setLastRecon(dashboard.last_reconciliation);
      cash.setGlobalStats(dashboard.global_balance, dashboard.monthly_avg);
      this.renderCategoriesSelect();
      this.renderSummary(dashboard);
      this.renderCategoryList(dashboard);
      analyticsCache.dashboard = dashboard;
      if (analyticsChartsReady) updateDoughnut(dashboard);
      renderKPIs(dashboard);
      await this.loadTransactions(true);
      if (backgroundAnalytics) {
        this.loadAnalyticsData(true);
      } else {
        await this.loadAnalyticsData();
      }
    } catch (e) { ui.toastError('Error al cargar: ' + e.message); }
    finally { hideLoading('home'); }
  },
  async loadAnalyticsData(showOverlay = false) {
    if (showOverlay) showAnalyticsLoading();
    try {
      let overview = cacheGet('overview:' + overviewMonths);
      let topTransactions = cacheGet('top:' + this.month);
      let weekday = cacheGet('weekday:' + this.month);
      let forecast = cacheGet('forecast');
      if (!overview || !topTransactions || !weekday || !forecast) {
        const results = await Promise.allSettled([
          overview ? null : api.get(`/api/analytics/overview?months=${overviewMonths}`),
          topTransactions ? null : api.get(`/api/analytics/top-transactions?month=${this.month}&limit=${topTxLimit}`),
          weekday ? null : api.get(`/api/analytics/weekday?month=${this.month}`),
          forecast ? null : api.get('/api/analytics/forecast?horizon=3'),
        ]);
        if (results[0].status === 'fulfilled' && results[0].value) {
          overview = results[0].value;
          cacheSet('overview:' + overviewMonths, overview);
        }
        if (results[1].status === 'fulfilled' && results[1].value) {
          topTransactions = results[1].value;
          cacheSet('top:' + this.month, topTransactions);
        }
        if (results[2].status === 'fulfilled' && results[2].value) {
          weekday = results[2].value;
          cacheSet('weekday:' + this.month, weekday);
        }
        if (results[3].status === 'fulfilled' && results[3].value) {
          forecast = results[3].value;
          cacheSet('forecast', forecast);
        }
      }
      overview = overview || [];
      analyticsCache.overview = overview;
      analyticsCache.top = topTransactions;
      analyticsCache.weekday = weekday;
      if (topTransactions) renderTopTransactions(topTransactions);
      if (forecast) renderForecast(forecast);
      if (analyticsChartsReady) {
        updateOverviewChart(overview);
        updateWeekdayChart(weekday || []);
      }
      if (overview.length) {
        const avgBalance = overview.reduce((sum, m) => sum + m.balance, 0) / overview.length;
        const formatted = ui.formatMoney(avgBalance);
        document.getElementById('avg-balance').textContent = formatted;
        document.getElementById('cash-monthly-avg').textContent = formatted;
      }
    } catch (e) { ui.toastError('Error al cargar análisis: ' + e.message); }
    finally { if (showOverlay) hideAnalyticsLoading(); }
  },
  async loadTransactions(reset = false) {
    if (this.loadingMore) return;
    const cacheKey = reset ? 'transactions:' + this.month + ':' + this.typeFilter + (this.searchQuery ? ':q=' + this.searchQuery : '') : null;
    if (reset) {
      showLoading('tx');
      const cached = cacheGet(cacheKey);
      if (cached) {
        this.transactions = cached.transactions;
        this.currentPage = cached.currentPage;
        this.lastPage = cached.lastPage;
        this.renderTimeline();
        hideLoading('tx');
        return;
      }
      this.transactions = [];
      this.currentPage = 1;
      this.lastPage = 1;
    } else {
      showLoadMore();
    }
    if (this.currentPage > this.lastPage) {
      if (!reset) hideLoadMore();
      return;
    }
    this.loadingMore = true;
    try {
      const perPage = reset ? 500 : 20;
      const response = await api.get(`/api/transactions?month=${this.month}&type=${this.typeFilter}&page=${this.currentPage}&per_page=${perPage}${this.searchQuery ? '&q=' + encodeURIComponent(this.searchQuery) : ''}`);
      this.transactions = this.transactions.concat(response.data);
      this.lastPage = response.last_page;
      this.currentPage = response.current_page + 1;
      if (reset) {
        cacheSet(cacheKey, {
          transactions: this.transactions,
          currentPage: this.currentPage,
          lastPage: this.lastPage
        });
      }
      this.renderTimeline();
    } catch (e) { ui.toastError('Error al cargar transacciones: ' + e.message); }
    finally {
      this.loadingMore = false;
      if (reset) hideLoading('tx');
      else hideLoadMore();
    }
  },
  async loadMoreTransactions() {
    if (this.currentPage > this.lastPage || this.loadingMore) return;
    await this.loadTransactions(false);
  },
  updateMonthLabels() {
    const label = ui.formatMonthLabel(this.month);
    document.querySelectorAll('[id^="current-month"]').forEach(el => el.textContent = label);
    const isCurrentMonth = this.month === new Date().toISOString().slice(0, 7);
    document.querySelectorAll('[id^="next-month"]').forEach(btn => {
      btn.style.display = isCurrentMonth ? 'none' : '';
    });
  },
  changeMonth(delta) {
    const [y, m] = this.month.split('-').map(Number);
    const newDate = new Date(y, m - 1 + delta);
    const now = new Date();
    const currentMonth = new Date(now.getFullYear(), now.getMonth(), 1);
    if (newDate > currentMonth) return;
    this.month = newDate.toISOString().slice(0, 7);
    this.updateMonthLabels();
    this._monthVersion = (this._monthVersion || 0) + 1;
    const version = this._monthVersion;
    clearTimeout(this._monthTimer);
    this._monthTimer = setTimeout(async () => {
      if (version !== this._monthVersion) return;
      await this.loadData();
      if (version !== this._monthVersion) return;
      await this.loadAnalyticsData();
    }, 150);
  },
  async deleteTransaction(id) {
    try {
      await api.del(`/api/transactions/${id}`);
      ui.toast('Transacción eliminada');
      invalidateMonth(this.month);
      await this.loadData();
    } catch (e) { ui.toast('Error: ' + e.message); }
  },
  async deleteCategory(id) {
    if (!confirm('¿Eliminar esta categoría? Las transacciones asociadas quedarán sin categoría.')) return;
    try {
      await api.del(`/api/categories/${id}`);
      ui.toast('Categoría eliminada');
      invalidateAll();
      await this.loadData();
    } catch (e) { ui.toast('Error: ' + e.message); }
  },
  renderCategoriesSelect() {
    const select = document.getElementById('tx-category');
    const type = document.getElementById('tx-type').value;
    const filtered = this.categories.filter(c => c.type === type);
    select.innerHTML = '<option value="">Seleccionar...</option>' + filtered.map(c => `<option value="${c.id}">${escapeHtml(c.name)}</option>`).join('');
  },
  renderSummary(data) {
    document.getElementById('balance-total').textContent = ui.formatMoney(data.balance || 0);
    document.getElementById('income-display').textContent = ui.formatMoney(data.income_total || 0);
    document.getElementById('expense-display').textContent = ui.formatMoney(data.expense_total || 0);
    const vs = data.vs_previous || 0;
    const vsEl = document.getElementById('vs-display');
    vsEl.textContent = (vs >= 0 ? '+' : '') + ui.formatMoney(vs);
    vsEl.style.color = vs >= 0 ? 'var(--green)' : 'var(--red)';
  },
  renderCategoryList(data) {
    const container = document.getElementById('category-list');
    const cats = (data.category_series || []).slice(0, 5);
    if (!cats.length) { container.innerHTML = '<div class="empty-state">Sin categorías este mes</div>'; return; }
    const maxVal = Math.max(...cats.map(c => c.total), 1);
    const budgets = data.budgets || [];
    container.innerHTML = cats.map(c => {
      const catOwner = app.categories.find(a => a.id == c.category_id);
      const isOwn = catOwner && catOwner.user_id;
      const budget = budgets.find(b => b.category_id == c.category_id);
      let budgetHtml = '';
      if (budget) {
        const pct = budget.percentage;
        const barColor = pct >= 100 ? 'var(--red)' : pct >= 70 ? 'var(--orange)' : 'var(--green)';
        const pctDisplay = pct >= 100 ? Math.round(pct) : pct;
        budgetHtml = `
        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--secondary);margin-top:6px">
          <span>${ui.formatMoney(budget.spent)} / ${ui.formatMoney(budget.limit)}</span>
          <span style="color:${barColor};font-weight:600">${pctDisplay}%</span>
        </div>
        <div class="cat-bar" style="margin-top:2px">
          <div class="cat-bar-fill" style="width:${Math.min(pct, 100)}%;background:${barColor}"></div>
        </div>`;
      }
      const budgetBtn = isOwn ? `
        <button class="budget-btn" data-category-id="${c.category_id}" style="background:none;border:none;color:var(--secondary);cursor:pointer;opacity:0.6;font-size:16px;font-weight:600;padding:10px 8px" title="Presupuesto">$</button>` : '';
      return `
      <div class="cat-card">
        <div class="cat-icon" style="background:${c.color}33;color:${c.color}">${getCategoryIcon(c.icon, c.color)}</div>
        <div class="cat-info">
          <div class="cat-name">${escapeHtml(c.name)}</div>
          <div class="cat-bar"><div class="cat-bar-fill" style="width:${(c.total/maxVal*100)}%;background:${c.color}"></div></div>
          ${budgetHtml}
        </div>
        <div class="cat-amount" style="color:${c.color}">${ui.formatMoney(c.total)}</div>
        ${budgetBtn}
        ${isOwn ? `
        <button class="cat-edit" data-id="${c.category_id}" style="background:none;border:none;color:var(--secondary);cursor:pointer;opacity:0.6">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
        </button>
        <button class="cat-delete" data-id="${c.category_id}" style="background:none;border:none;color:var(--red);cursor:pointer;opacity:0.6">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        </button>` : ''}
      </div>`;
    }).join('');
    container.querySelectorAll('.budget-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        app.openBudgetModal(btn.dataset.categoryId);
      });
    });
  },
  openBudgetModal(categoryId) {
    const select = document.getElementById('budget-category');
    const limitInput = document.getElementById('budget-limit');
    const deleteBtn = document.getElementById('btn-delete-budget');
    const expenseCats = this.categories.filter(c => c.type === 'expense');
    select.innerHTML = expenseCats.map(c =>
      `<option value="${c.id}" ${c.id == categoryId ? 'selected' : ''}>${escapeHtml(c.name)}</option>`
    ).join('');
    const existing = (this.budgets || []).find(b => b.category_id == categoryId);
    limitInput.value = existing ? existing.limit : '';
    limitInput.dataset.editId = existing ? existing.id : '';
    deleteBtn.style.display = existing ? '' : 'none';
    modals.open('modal-budget');
  },
  async saveBudget() {
    const categoryId = document.getElementById('budget-category').value;
    const limit = document.getElementById('budget-limit').value;
    if (!categoryId) { ui.toast('Selecciona una categoría'); return; }
    if (!limit || parseFloat(limit) <= 0) { ui.toast('Ingresa un límite válido'); return; }
    try {
      await api.post('/api/budgets', { category_id: categoryId, limit: parseFloat(limit) });
      ui.toast('Presupuesto guardado');
      modals.closeAll();
      invalidateMonth(this.month);
      await this.loadData();
    } catch (e) { ui.toast('Error: ' + e.message); }
  },
  renderTimeline() {
    const container = document.getElementById('timeline-container');
    let txs = this.transactions;
    if (this.typeFilter !== 'all') txs = txs.filter(t => t.type === this.typeFilter);
    if (!txs.length) { container.innerHTML = '<div class="empty-state">' + (this.searchQuery ? 'Sin resultados para <strong>"' + escapeHtml(this.searchQuery) + '"</strong>' : 'Sin transacciones este mes') + '</div>'; return; }

    const grouped = txs.reduce((acc, tx) => {
      const day = tx.date.split('T')[0];
      if (!acc[day]) acc[day] = { date: day, transactions: [], categories: {}, totalIncome: 0, totalExpense: 0 };
      acc[day].transactions.push(tx);
      const cid = tx.category?.id || 'none';
      if (!acc[day].categories[cid]) acc[day].categories[cid] = {
        name: tx.category?.name || 'Sin cat.',
        color: tx.category?.color_hex || '#8E8E93',
        icon: tx.category?.icon || 'circle',
        total: 0
      };
      acc[day].categories[cid].total += parseFloat(tx.amount);
      if (tx.type === 'income' || (tx.type === 'reconciliation' && parseFloat(tx.amount) >= 0)) {
        acc[day].totalIncome += parseFloat(tx.amount);
      } else {
        acc[day].totalExpense += Math.abs(parseFloat(tx.amount));
      }
      return acc;
    }, {});
    const days = Object.values(grouped).sort((a, b) => b.date.localeCompare(a.date));

    const dayClass = d => d.totalIncome > 0 && d.totalExpense > 0 ? 'mixed' : d.totalIncome > 0 ? 'has-income' : 'has-expense';
    const dayTotal = d => {
      const net = d.totalIncome - d.totalExpense;
      return (net >= 0 ? '+' : '') + ui.formatMoney(net);
    };
    const dayTotalClass = d => (d.totalIncome - d.totalExpense) >= 0 ? 'income' : 'expense';

    container.innerHTML = days.map(d => {
      const cats = Object.values(d.categories);
      const dateObj = new Date(d.date + 'T12:00:00');
      const isToday = d.date === new Date().toISOString().split('T')[0];
      const isYesterday = d.date === new Date(Date.now() - 86400000).toISOString().split('T')[0];
      const dayLabel = isToday ? 'Hoy' : isYesterday ? 'Ayer' : dateObj.toLocaleDateString('es-ES', { day: 'numeric', month: 'long' });

      return `
      <div class="day-card ${dayClass(d)}">
        <div class="day-header">
          <div class="day-name">${dayLabel}</div>
          <div class="day-total ${dayTotalClass(d)}">${dayTotal(d)}</div>
        </div>
        <div class="day-cats">${cats.map(c => `<div class="day-cat"><div class="cat-dot" style="background:${c.color}"></div><div class="cat-lbl">${escapeHtml(c.name)}</div><div class="cat-val">${c.total >= 0 ? '+' : ''}${ui.formatMoney(c.total)}</div></div>`).join('')}</div>
        <div class="day-tx">
          ${d.transactions.map(t => {
            const isRecon = t.type === 'reconciliation';
            if (isRecon) {
              const reconSurplus = t.amount >= 0;
              return `
            <div class="tx-row" data-id="${t.id}" style="display:flex;align-items:center">
              <div class="tx-icon" style="color:var(--accent);background:rgba(10,132,255,0.15)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
              </div>
              <div class="tx-info"><div class="tx-name">${reconSurplus ? 'Ajuste por sobrante' : 'Ajuste por faltante'}</div><div class="tx-time">Reconciliación • ${new Date(t.date).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })}</div></div>
              <div class="tx-amount ${reconSurplus ? 'income' : 'expense'}" style="margin-left:8px">${reconSurplus ? '+' : '-'}${ui.formatMoney(Math.abs(t.amount))}</div>
              <button class="tx-delete" data-id="${t.id}" style="background:none;border:none;color:var(--red);cursor:pointer;opacity:0.6">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </div>`;
            }
            return `
            <div class="tx-row" data-id="${t.id}" style="display:flex;align-items:center">
              <div class="tx-icon" style="color:${t.category?.color_hex || 'var(--secondary)'}">${getCategoryIcon(t.category?.icon || 'circle', t.category?.color_hex || 'var(--secondary)')}</div>
              <div class="tx-info"><div class="tx-name">${escapeHtml(t.note || 'Sin descripción')}</div><div class="tx-time">${escapeHtml(t.category?.name || 'Sin categoría')} • ${new Date(t.date).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })}</div></div>
              <div class="tx-amount ${t.type}" style="margin-left:8px">${t.type === 'expense' ? '-' : '+'}${ui.formatMoney(t.amount)}</div>
              <button class="tx-edit" data-id="${t.id}" style="background:none;border:none;color:var(--secondary);cursor:pointer;opacity:0.6">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
              </button>
              <button class="tx-delete" data-id="${t.id}" style="background:none;border:none;color:var(--red);cursor:pointer;opacity:0.6">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </div>`;
          }).join('')}
        </div>
        ${Object.keys(d.categories).length > 1 ? `
        <div class="day-cat-breakdown">
          ${Object.values(d.categories).map(c => `
            <div class="day-cat-row">
              <span class="day-cat-dot" style="background:${c.color}"></span>
              <span class="day-cat-name">${escapeHtml(c.name)}</span>
              <span class="day-cat-total" style="color:${c.color}">${ui.formatMoney(c.total)}</span>
            </div>`).join('')}
        </div>` : ''}
      </div>`;
    }).join('');

    const sentinel = document.getElementById('scroll-sentinel') || document.createElement('div');
    sentinel.id = 'scroll-sentinel';
    container.after(sentinel);
    setupScrollObserver();
  }
};

// ===== FORM HANDLERS =====
document.getElementById('tx-type').addEventListener('change', () => app.renderCategoriesSelect());

// Month navigation
document.getElementById('prev-month').addEventListener('click', () => app.changeMonth(-1));
document.getElementById('next-month').addEventListener('click', () => app.changeMonth(1));
document.getElementById('prev-month-tx').addEventListener('click', () => app.changeMonth(-1));
document.getElementById('next-month-tx').addEventListener('click', () => app.changeMonth(1));
document.getElementById('prev-month-analytics').addEventListener('click', () => app.changeMonth(-1));
document.getElementById('next-month-analytics').addEventListener('click', () => app.changeMonth(1));

// Delete/Edit transaction (delegation)
document.getElementById('timeline-container').addEventListener('click', async (e) => {
  const delBtn = e.target.closest('.tx-delete');
  if (delBtn) {
    const id = delBtn.dataset.id;
    if (confirm('¿Eliminar esta transacción?')) {
      await app.deleteTransaction(id);
    }
    return;
  }
  const editBtn = e.target.closest('.tx-edit');
  if (editBtn) {
    const id = editBtn.dataset.id;
    const tx = app.transactions.find(t => t.id == id);
    if (!tx) return;
    app.editingTransactionId = id;
    app.editingTransactionDate = tx.date.split('T')[0];
    document.getElementById('modal-transaction').querySelector('.modal-title').textContent = 'Editar Transacción';
    document.getElementById('btn-add-transaction').textContent = 'Guardar';
    document.getElementById('tx-date').value = tx.date.split('T')[0];
    document.getElementById('tx-type').value = tx.type;
    document.getElementById('tx-amount').value = tx.amount;
    document.getElementById('tx-note').value = tx.note || '';
    app.renderCategoriesSelect();
    document.getElementById('tx-category').value = tx.category_id || '';
    modals.closeAll();
    modals.open('modal-transaction');
  }
});

// Edit/Delete category (delegation)
document.getElementById('category-list').addEventListener('click', async (e) => {
  const editBtn = e.target.closest('.cat-edit');
  if (editBtn) {
    const id = editBtn.dataset.id;
    const cat = app.categories.find(c => c.id == id);
    if (!cat) return;
    app.editingCategoryId = id;
    document.getElementById('modal-category').querySelector('.modal-title').textContent = 'Editar Categoría';
    document.getElementById('btn-add-category').textContent = 'Guardar';
    document.getElementById('cat-name').value = cat.name;
    document.getElementById('cat-color').value = cat.color_hex;
    document.getElementById('cat-type').value = cat.type;
    document.querySelectorAll('.icon-option').forEach(b => {
      b.classList.toggle('selected', b.dataset.icon === (cat.icon || 'circle'));
    });
    const budget = (app.budgets || []).find(b => b.category_id == id);
    document.getElementById('cat-budget-limit').value = budget ? budget.limit : '';
    app.editingBudgetId = budget ? budget.id : null;
    document.getElementById('budget-limit-group').style.display = cat.type === 'expense' ? '' : 'none';
    modals.closeAll();
    modals.open('modal-category');
    return;
  }
  const delBtn = e.target.closest('.cat-delete');
  if (delBtn) {
    await app.deleteCategory(delBtn.dataset.id);
  }
});

// Reset modal title when opening selector
document.getElementById('sel-transaction').addEventListener('click', () => {
  modals.closeAll();
  app.editingTransactionId = null;
  app.editingTransactionDate = null;
  document.getElementById('modal-transaction').querySelector('.modal-title').textContent = 'Nueva Transacción';
  document.getElementById('btn-add-transaction').textContent = 'Agregar';
  document.getElementById('tx-amount').value = '';
  document.getElementById('tx-note').value = '';
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('tx-date').value = today;
  document.getElementById('tx-type').value = 'expense';
  modals.open('modal-transaction');
});
document.getElementById('sel-category').addEventListener('click', () => {
  modals.closeAll();
  app.editingCategoryId = null;
  app.editingBudgetId = null;
  document.getElementById('modal-category').querySelector('.modal-title').textContent = 'Nueva Categoría';
  document.getElementById('btn-add-category').textContent = 'Agregar';
  document.getElementById('cat-name').value = '';
  document.getElementById('cat-color').value = '#0A84FF';
  document.getElementById('cat-type').value = 'expense';
  document.getElementById('cat-budget-limit').value = '';
  document.getElementById('budget-limit-group').style.display = '';
  document.querySelectorAll('.icon-option').forEach(b => b.classList.remove('selected'));
  document.querySelector('[data-icon="utensils"]').classList.add('selected');
  modals.open('modal-category');
});

document.getElementById('btn-add-transaction').addEventListener('click', async () => {
  const data = {
    date: document.getElementById('tx-date').value,
    amount: parseFloat(document.getElementById('tx-amount').value),
    type: document.getElementById('tx-type').value,
    category_id: document.getElementById('tx-category').value || null,
    note: document.getElementById('tx-note').value
  };
  if (!data.date || !data.amount || data.amount <= 0) { ui.toast('Completa los campos requeridos'); return; }
  if (data.note.length > 255) { ui.toast('La nota no puede exceder 255 caracteres'); return; }
  if (data.date > new Date().toISOString().split('T')[0]) { ui.toast('La fecha no puede ser futura'); return; }
  try {
    const wasEditing = !!app.editingTransactionId;
    const origMonth = app.editingTransactionDate?.slice(0, 7);
    if (wasEditing) {
      await api.patch(`/api/transactions/${app.editingTransactionId}`, data);
      app.editingTransactionId = null;
      app.editingTransactionDate = null;
    } else {
      await api.post('/api/transactions', data);
    }
    document.getElementById('tx-amount').value = '';
    document.getElementById('tx-note').value = '';
    modals.closeAll();
    ui.toast(wasEditing ? 'Transacción actualizada' : 'Transacción agregada');
    invalidateMonth(app.month);
    if (origMonth && origMonth !== app.month) invalidateMonth(origMonth);
    await app.loadData(true);
    updateHeatmapCell(data.date, data.type === 'expense' ? data.amount : 0);
  } catch (e) { ui.toast('Error: ' + e.message); }
});

document.getElementById('btn-add-category').addEventListener('click', async () => {
  const selectedIcon = document.querySelector('.icon-option.selected');
  const data = {
    name: document.getElementById('cat-name').value.trim(),
    color_hex: document.getElementById('cat-color').value,
    type: document.getElementById('cat-type').value,
    icon: selectedIcon ? selectedIcon.dataset.icon : 'circle'
  };
  if (!data.name) { ui.toast('Ingresa un nombre'); return; }
  if (data.name.length > 50) { ui.toast('El nombre no puede exceder 50 caracteres'); return; }
  try {
    let catId = app.editingCategoryId;
    if (catId) {
      await api.patch(`/api/categories/${catId}`, data);
      app.editingCategoryId = null;
      ui.toast('Categoría actualizada');
    } else {
      const created = await api.post('/api/categories', data);
      catId = created.id;
      ui.toast('Categoría agregada');
    }
    const budgetLimit = document.getElementById('cat-budget-limit').value.trim();
    if (budgetLimit) {
      await api.post('/api/budgets', { category_id: catId, limit: parseFloat(budgetLimit) });
    } else if (app.editingBudgetId) {
      await api.del(`/api/budgets/${app.editingBudgetId}`);
    }
    app.editingBudgetId = null;
    document.getElementById('cat-name').value = '';
    document.getElementById('cat-budget-limit').value = '';
    modals.closeAll();
    invalidateAll();
    await app.loadData();
  } catch (e) { ui.toast('Error: ' + e.message); }
});

document.getElementById('btn-save-budget').addEventListener('click', () => app.saveBudget());

document.getElementById('btn-delete-budget').addEventListener('click', async () => {
  const id = document.getElementById('budget-limit').dataset.editId;
  if (!id) return;
  try {
    await api.del(`/api/budgets/${id}`);
    ui.toast('Presupuesto eliminado');
    modals.closeAll();
    invalidateMonth(app.month);
    await app.loadData();
  } catch (e) { ui.toast('Error: ' + e.message); }
});

document.getElementById('cat-type').addEventListener('change', (e) => {
  document.getElementById('budget-limit-group').style.display = e.target.value === 'expense' ? '' : 'none';
});

// Export CSV / HTML
document.querySelectorAll('.export-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const format = btn.dataset.format;
    const theme = document.getElementById('export-theme')?.value || 'dark';
    const url = `/api/export?month=${app.month}&format=${format}&theme=${theme}`;
    if (format === 'html') {
      window.open(url, '_blank');
    } else {
      window.location.href = url;
    }
  });
});

// Infinite scroll for transactions (IntersectionObserver)
let scrollObserver;
function setupScrollObserver() {
  if (scrollObserver) scrollObserver.disconnect();
  scrollObserver = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) app.loadMoreTransactions();
  }, { root: document.querySelector('.content'), rootMargin: '0px 0px 200px 0px' });
  const sentinel = document.getElementById('scroll-sentinel');
  if (sentinel) scrollObserver.observe(sentinel);
}

// Search
let searchTimeout;
document.getElementById('search-tx').addEventListener('input', (e) => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    app.searchQuery = e.target.value;
    document.getElementById('search-clear').classList.toggle('visible', !!e.target.value);
    app.loadTransactions(true);
  }, 300);
});
document.getElementById('search-clear').addEventListener('click', () => {
  document.getElementById('search-tx').value = '';
  document.getElementById('search-clear').classList.remove('visible');
  app.searchQuery = '';
  app.loadTransactions(true);
  document.getElementById('search-tx').focus();
});

// Overview months selector
document.getElementById('overview-months').addEventListener('click', (e) => {
  const seg = e.target.closest('.segment');
  if (!seg) return;
  document.querySelectorAll('#overview-months .segment').forEach(s => s.classList.remove('active'));
  seg.classList.add('active');
  overviewMonths = parseInt(seg.dataset.months);
  app.loadAnalyticsData();
});

// Top transactions card fan expand/collapse
document.getElementById('top-transactions-container').addEventListener('click', (e) => {
  const btn = e.target.closest('.expand-btn');
  if (!btn) return;
  const stack = btn.closest('.ledger-stack');
  if (!stack) return;
  const isExpanded = stack.classList.toggle('expanded');
  const hiddenCount = stack.querySelectorAll('.hidden-card').length;
  btn.textContent = isExpanded ? '✕' : '+' + hiddenCount;
});

// Open transaction detail modal on card click
document.getElementById('top-transactions-container').addEventListener('click', (e) => {
  const card = e.target.closest('.card-item');
  if (!card) return;
  const id = parseInt(card.dataset.id);
  const allTxs = [];
  if (analyticsCache.top) {
    allTxs.push(...(analyticsCache.top.top_expense || []), ...(analyticsCache.top.top_income || []));
  }
  const tx = allTxs.find(t => t.id === id);
  if (!tx) return;

  const color = tx.category?.color_hex || (tx.type === 'expense' ? '#FF453A' : '#30D158');
  const icon = tx.category?.icon || 'circle';
  const catName = tx.category?.name || 'Sin categor\u00eda';
  const dateStr = new Date(tx.date + 'T12:00:00').toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
  const note = tx.note || 'Sin descripci\u00f3n';
  const sign = tx.type === 'expense' ? '-' : '+';
  const amt = ui.formatMoney(tx.amount);
  const typeLabel = tx.type === 'expense' ? 'Gasto' : 'Ingreso';

  const iconEl = document.getElementById('detail-icon');
  iconEl.innerHTML = getCategoryIcon(icon, color);
  iconEl.style.color = color;
  iconEl.style.background = color + '22';
  document.getElementById('detail-category').textContent = catName;
  document.getElementById('detail-category').style.color = color;
  document.getElementById('detail-badge').textContent = typeLabel;
  document.getElementById('detail-badge').style.color = tx.type === 'expense' ? 'var(--red)' : 'var(--green)';
  document.getElementById('detail-badge').style.borderColor = tx.type === 'expense' ? 'var(--red)' : 'var(--green)';
  document.getElementById('detail-amount').textContent = sign + amt;
  document.getElementById('detail-amount').style.color = tx.type === 'expense' ? 'var(--red)' : 'var(--green)';
  document.getElementById('detail-date').textContent = dateStr;
  document.getElementById('detail-note').textContent = note;

  modals.open('modal-transaction-detail');
});

// Heatmap year navigation
document.getElementById('heatmap-prev').addEventListener('click', () => {
  heatmapYear--;
  heatmapData = null;
  loadHeatmap();
});
document.getElementById('heatmap-next').addEventListener('click', () => {
  if (heatmapYear >= new Date().getFullYear()) return;
  heatmapYear++;
  heatmapData = null;
  loadHeatmap();
});

// Rates
document.getElementById('btn-rates')?.addEventListener('click', () => cash.openRatesModal());
document.getElementById('btn-save-rates')?.addEventListener('click', () => cash.saveRates());

// Currency
document.getElementById('currency-select').addEventListener('change', async (e) => {
  currentCurrency = e.target.value;
  localStorage.setItem('ledger_currency', currentCurrency);
  if (currentCurrency === 'CUP') {
    currentRate = 1;
  } else {
    const rate = cash.rates?.[currentCurrency]?.rate_to_cup;
    currentRate = rate ? 1 / rate : 1;
  }
  cash.renderDenominations();
  cash.reset();
  app.renderAll();
  // Update heatmap cell titles with current currency
  if (heatmapData && heatmapData.length) {
    document.querySelectorAll('#heatmap-grid .cell[data-index]').forEach(el => {
      const idx = parseInt(el.dataset.index);
      const d = heatmapData[idx];
      if (d) el.title = d.date + ': $' + ui.formatMoney(d.expense) + ' en gastos';
    });
  }
});

// Swipe to edit on mobile
let swipeStartX = 0, swipeStartY = 0, swipeTarget = null;
document.querySelector('.content').addEventListener('touchstart', (e) => {
  const row = e.target.closest('.tx-row');
  if (!row) return;
  swipeStartX = e.touches[0].clientX;
  swipeStartY = e.touches[0].clientY;
  swipeTarget = row;
}, { passive: true });
document.querySelector('.content').addEventListener('touchend', (e) => {
  if (!swipeTarget) return;
  const dx = e.changedTouches[0].clientX - swipeStartX;
  const dy = e.changedTouches[0].clientY - swipeStartY;
  if (Math.abs(dx) > 50 && Math.abs(dy) < 40) {
    const editBtn = swipeTarget.querySelector('.tx-edit');
    if (editBtn) editBtn.click();
  }
  swipeTarget = null;
}, { passive: true });

document.addEventListener('DOMContentLoaded', () => auth.init());

// Auth events
document.getElementById('btn-logout').addEventListener('click', () => auth.logout());
document.getElementById('reg-password').addEventListener('input', updateStrengthMeter);

// Auth navigation buttons (replaced inline onclick)
document.getElementById('btn-show-login')?.addEventListener('click', () => auth.showLogin());
document.getElementById('btn-show-register')?.addEventListener('click', () => auth.showRegister());
document.getElementById('link-show-register')?.addEventListener('click', () => auth.showRegister());
document.getElementById('link-show-login')?.addEventListener('click', () => auth.showLogin());

// Auth submit buttons (replaced inline onclick)
document.getElementById('btn-login').addEventListener('click', () => auth.login());
document.getElementById('btn-register').addEventListener('click', () => auth.register());

// Remember checkbox toggle

document.getElementById('check-remember').addEventListener('click', () => {
  const cb = document.getElementById('login-remember');
  cb.checked = !cb.checked;
  cb.dispatchEvent(new Event('change'));
});

['login-username','login-password'].forEach(id => {
  document.getElementById(id).addEventListener('keydown', (e) => { if (e.key === 'Enter') auth.login(); });
});
['reg-username','reg-email','reg-password','reg-password-confirm'].forEach(id => {
  document.getElementById(id).addEventListener('keydown', (e) => { if (e.key === 'Enter') auth.register(); });
});

// Scroll progress bar
(function() {
  var content = document.querySelector('.content');
  var progress = document.querySelector('.scroll-progress');
  if (!content || !progress) return;
  function update() {
    var scrollTop = content.scrollTop;
    var scrollHeight = content.scrollHeight - content.clientHeight;
    var pct = scrollHeight > 0 ? scrollTop / scrollHeight : 0;
    progress.style.setProperty('--scroll', pct.toFixed(4));
  }
  content.addEventListener('scroll', update, { passive: true });
  window.addEventListener('resize', update, { passive: true });
  update();
})();

// Theme toggle
(function() {
  const toggleBtn = document.getElementById('theme-toggle');
  if (!toggleBtn) return;
  const iconGlassy = document.getElementById('theme-icon-glassy');
  const iconLite = document.getElementById('theme-icon-lite');
  function updateIcons() {
    const isLite = document.documentElement.getAttribute('data-theme') === 'lite';
    if (iconGlassy) iconGlassy.style.display = isLite ? 'none' : '';
    if (iconLite) iconLite.style.display = isLite ? '' : 'none';
  }
  updateIcons();
  toggleBtn.addEventListener('click', function() {
    const isLite = document.documentElement.getAttribute('data-theme') === 'lite';
    const newTheme = isLite ? 'glassy' : 'lite';
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('ledger_theme', newTheme);
    updateIcons();
    if (typeof ui !== 'undefined' && ui.toast) {
      ui.toast(newTheme === 'lite' ? 'Modo Lite activado' : 'Modo Glassy activado', 2000);
    }
  });
})();
