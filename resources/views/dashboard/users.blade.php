{{-- ══════════════════════════════════════════
   DASHBOARD — GESTION DES UTILISATEURS
   Route: GET /dashboard/users → DashboardController@users
══════════════════════════════════════════ --}}
@extends('layouts.dashboard')
@section('title','Utilisateurs')
@section('page_title','Gestion des utilisateurs')
@section('breadcrumb')
<span class="sep">/</span> <span>Utilisateurs</span>
@endsection

@section('topbar_actions')
<button onclick="openModal('userModal')" class="btn-d primary">
    <i class="fa-solid fa-user-plus"></i> Nouvel utilisateur
</button>
@endsection

@section('content')

{{-- ══ STAT CARDS ══ --}}
<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card s-vert">
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        <div class="stat-label">Total utilisateurs</div>
        <div class="stat-value" id="statTotal">—</div>
        <div class="stat-sub"><span class="up" id="statActifs">0 actifs</span></div>
    </div>
    <div class="stat-card s-bleu">
        <div class="stat-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="stat-label">Administrateurs</div>
        <div class="stat-value" id="statAdmins">—</div>
        <div class="stat-sub">Accès complet</div>
    </div>
    <div class="stat-card s-jaune">
        <div class="stat-icon"><i class="fa-solid fa-pen-nib"></i></div>
        <div class="stat-label">Contributeurs</div>
        <div class="stat-value" id="statContribs">—</div>
        <div class="stat-sub">Publient des articles</div>
    </div>
    <div class="stat-card s-rouge">
        <div class="stat-icon"><i class="fa-solid fa-user-slash"></i></div>
        <div class="stat-label">Bannis / Inactifs</div>
        <div class="stat-value" id="statBannis">—</div>
        <div class="stat-sub">Comptes suspendus</div>
    </div>
</div>

{{-- ══ FILTRES ══ --}}
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;align-items:center;">
    <div style="position:relative;">
        <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-d);font-size:.8rem;pointer-events:none;"></i>
        <input type="text" id="userSearch" class="d-input search"
               placeholder="Rechercher un utilisateur…"
               style="width:220px;padding-left:32px;"
               oninput="filterUsers()">
    </div>
    <select id="roleFilter" class="d-input d-select" onchange="filterUsers()">
        <option value="">Tous les rôles</option>
        <option value="admin">Admin</option>
        <option value="contributeur">Contributeur</option>
        <option value="visiteur">Visiteur</option>
    </select>
    <select id="statutFilter" class="d-input d-select" onchange="filterUsers()">
        <option value="">Tous les statuts</option>
        <option value="actif">Actif</option>
        <option value="inactif">Inactif</option>
        <option value="banni">Banni</option>
    </select>
    <div style="margin-left:auto;font-size:.78rem;color:var(--text-d);" id="userCount">— utilisateurs</div>
</div>

{{-- ══ TABLEAU ══ --}}
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Inscrit le</th>
                <th>Dernière connexion</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="usersBody">
            <tr><td colspan="6" style="text-align:center;padding:40px;">
                <i class="fa-solid fa-spinner fa-spin" style="color:var(--text-d);font-size:1.2rem;"></i>
            </td></tr>
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="pag" id="usersPag">
    <span id="usersPagInfo">-</span>
    <div class="pag-pages" id="usersPagPages"></div>
</div>

{{-- ══ MODAL UTILISATEUR ══ --}}
<div class="modal-back" id="userModal" onclick="if(event.target===this)closeUserModal()">
    <div class="modal" style="max-width:560px;">
        <div class="modal-head">
            <div class="modal-title" id="userModalTitle">
                <i class="fa-solid fa-user-plus" style="color:var(--dy);margin-right:8px;"></i>Nouvel utilisateur
            </div>
            <button class="modal-x" onclick="closeUserModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="user-id">
            <div id="userModalError" style="display:none;padding:10px 14px;background:rgba(198,40,40,.15);border:1px solid rgba(198,40,40,.3);border-radius:8px;color:#fca5a5;font-size:.82rem;margin-bottom:16px;"></div>

            {{-- Avatar preview --}}
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding:16px;background:rgba(255,255,255,.04);border-radius:10px;border:1px solid var(--border);">
                <div id="avatarPreview" style="width:56px;height:56px;border-radius:50%;background:var(--dv);display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:700;color:#fff;flex-shrink:0;">
                    ?
                </div>
                <div>
                    <div style="font-size:.82rem;font-weight:600;color:var(--text-m);" id="avatarName">Nouveau compte</div>
                    <div style="font-size:.72rem;color:var(--text-d);" id="avatarEmail">—</div>
                </div>
            </div>

            <div class="f-row">
                <div class="f-group">
                    <label class="f-label">Nom complet *</label>
                    <input type="text" id="user-name" class="f-control" placeholder="Jean Dupont"
                           oninput="updateAvatarPreview()">
                </div>
                <div class="f-group">
                    <label class="f-label">Email *</label>
                    <input type="email" id="user-email" class="f-control" placeholder="jean@email.com"
                           oninput="updateAvatarPreview()">
                </div>
            </div>

            <div class="f-row">
                <div class="f-group">
                    <label class="f-label">
                        <i class="fa-solid fa-shield-halved" style="color:var(--dv-l);margin-right:4px;"></i>Rôle *
                    </label>
                    <select id="user-role" class="f-control" onchange="updateRoleHint()">
                        <option value="visiteur">Visiteur</option>
                        <option value="contributeur">Contributeur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                    <div class="f-hint" id="roleHint" style="color:var(--dv-l);"></div>
                </div>
                <div class="f-group">
                    <label class="f-label">
                        <i class="fa-solid fa-circle-dot" style="color:var(--dv-l);margin-right:4px;"></i>Statut
                    </label>
                    <select id="user-statut" class="f-control">
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                        <option value="banni">Banni</option>
                    </select>
                </div>
            </div>

            <div class="f-group">
                <label class="f-label">Bio (optionnel)</label>
                <textarea id="user-bio" class="f-control" rows="2" placeholder="Courte description…"></textarea>
            </div>

            <div id="passwordSection">
                <div class="f-row">
                    <div class="f-group">
                        <label class="f-label">
                            <i class="fa-solid fa-lock" style="color:var(--dv-l);margin-right:4px;"></i>Mot de passe <span id="pwdRequired">*</span>
                        </label>
                        <div style="position:relative;">
                            <input type="password" id="user-password" class="f-control" placeholder="Min. 8 caractères"
                                   style="padding-right:42px;">
                            <button type="button" onclick="togglePwd('user-password','eyeIcon1')"
                                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-d);cursor:pointer;">
                                <i class="fa-regular fa-eye" id="eyeIcon1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Confirmer le mot de passe <span id="pwdConfirmRequired">*</span></label>
                        <div style="position:relative;">
                            <input type="password" id="user-password-confirm" class="f-control" placeholder="Répéter…"
                                   style="padding-right:42px;">
                            <button type="button" onclick="togglePwd('user-password-confirm','eyeIcon2')"
                                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-d);cursor:pointer;">
                                <i class="fa-regular fa-eye" id="eyeIcon2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="pwdEditNote" style="display:none;font-size:.75rem;color:var(--text-d);margin-top:-8px;margin-bottom:12px;">
                    <i class="fa-solid fa-circle-info" style="margin-right:4px;"></i>
                    Laissez vide pour conserver le mot de passe actuel
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="closeUserModal()" class="btn-d outline">
                <i class="fa-solid fa-xmark"></i> Annuler
            </button>
            <button onclick="saveUser()" class="btn-d primary">
                <i class="fa-solid fa-floppy-disk"></i> Enregistrer
            </button>
        </div>
    </div>
</div>

{{-- ══ MODAL CONFIRMATION RAPIDE RÔLE ══ --}}
<div class="modal-back" id="quickRoleModal" onclick="if(event.target===this)closeModal('quickRoleModal')">
    <div class="modal" style="max-width:400px;">
        <div class="modal-head">
            <div class="modal-title">
                <i class="fa-solid fa-shield-halved" style="color:var(--dy);margin-right:8px;"></i>Changer le rôle
            </div>
            <button class="modal-x" onclick="closeModal('quickRoleModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div style="font-size:.88rem;color:var(--text-m);margin-bottom:16px;" id="quickRoleDesc"></div>
            <input type="hidden" id="quickRoleUserId">
            <div class="f-group">
                <label class="f-label">Nouveau rôle</label>
                <select id="quickRoleValue" class="f-control">
                    <option value="visiteur">Visiteur</option>
                    <option value="contributeur">Contributeur</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>
            <div class="f-group">
                <label class="f-label">Statut</label>
                <select id="quickStatutValue" class="f-control">
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                    <option value="banni">Banni</option>
                </select>
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="closeModal('quickRoleModal')" class="btn-d outline">Annuler</button>
            <button onclick="applyQuickRole()" class="btn-d primary">
                <i class="fa-solid fa-check"></i> Appliquer
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let allUsers  = [];
let currentPage = 1;

// ── Charger les utilisateurs ──
async function loadUsers(page = 1) {
    currentPage = page;
    try {
        const r = await apiFetch(`/api/v1/users?page=${page}`, {
            headers: { Accept: 'application/json' }
        });
        const d = await r.json();
        allUsers = d.data || [];
        updateStats(allUsers);
        renderUsers(allUsers);
        renderPag(d);
    } catch (e) {
        showToast('Erreur de chargement', 'error');
    }
}

// ── Stats cards ──
function updateStats(users) {
    document.getElementById('statTotal').textContent    = users.length;
    document.getElementById('statActifs').textContent   = users.filter(u => u.statut === 'actif').length + ' actifs';
    document.getElementById('statAdmins').textContent   = users.filter(u => u.role === 'admin').length;
    document.getElementById('statContribs').textContent = users.filter(u => u.role === 'contributeur').length;
    document.getElementById('statBannis').textContent   = users.filter(u => ['inactif','banni'].includes(u.statut)).length;
    document.getElementById('userCount').textContent    = users.length + ' utilisateur' + (users.length !== 1 ? 's' : '');
}

// ── Filtres ──
let filterTimeout;
function filterUsers() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        const search = document.getElementById('userSearch').value.toLowerCase();
        const role   = document.getElementById('roleFilter').value;
        const statut = document.getElementById('statutFilter').value;

        const filtered = allUsers.filter(u => {
            const matchSearch = !search || u.name.toLowerCase().includes(search) || u.email.toLowerCase().includes(search);
            const matchRole   = !role   || u.role   === role;
            const matchStatut = !statut || u.statut === statut;
            return matchSearch && matchRole && matchStatut;
        });

        renderUsers(filtered);
        document.getElementById('userCount').textContent = filtered.length + ' utilisateur' + (filtered.length !== 1 ? 's' : '');
    }, 300);
}

// ── Rendu tableau ──
function renderUsers(users) {
    const roleColors = { admin: 'var(--dv-l)', contributeur: 'var(--dy)', visiteur: 'rgba(255,255,255,.25)' };
    const roleIcons  = { admin: 'fa-shield-halved', contributeur: 'fa-pen-nib', visiteur: 'fa-user' };
    const statutColors = { actif: '#4ade80', inactif: '#94a3b8', banni: '#f87171' };
    const statutIcons  = { actif: 'fa-circle-check', inactif: 'fa-circle-pause', banni: 'fa-ban' };

    const tbody = document.getElementById('usersBody');
    if (!users.length) {
        tbody.innerHTML = `<tr><td colspan="6">
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-users-slash"></i></div>
                <div class="empty-msg">Aucun utilisateur trouvé</div>
            </div>
        </td></tr>`;
        return;
    }

    tbody.innerHTML = users.map(u => {
        const initials = (u.name || '?').split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
        const joined   = u.created_at ? new Date(u.created_at).toLocaleDateString('fr') : '—';
        const lastCon  = u.date_derniere_connexion
            ? new Date(u.date_derniere_connexion).toLocaleDateString('fr', { day:'2-digit', month:'short', year:'numeric' })
            : 'Jamais';

        return `
        <tr>
            <td>
                <div class="td-main">
                    <div style="width:38px;height:38px;border-radius:50%;background:var(--dv);display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;color:#fff;flex-shrink:0;border:2px solid ${roleColors[u.role]||'transparent'};">
                        ${initials}
                    </div>
                    <div>
                        <div class="td-name">${u.name}</div>
                        <div class="td-sub" style="font-family:var(--fm);">${u.email}</div>
                    </div>
                </div>
            </td>
            <td>
                <span class="bx" style="display:inline-flex;align-items:center;gap:5px;background:${roleColors[u.role]||'rgba(255,255,255,.1)'};color:${u.role==='visiteur'?'var(--text-m)':'#fff'};font-size:.72rem;padding:3px 10px;border-radius:5px;font-weight:700;">
                    <i class="fa-solid ${roleIcons[u.role]||'fa-user'}"></i> ${u.role}
                </span>
            </td>
            <td>
                <span style="display:inline-flex;align-items:center;gap:5px;font-size:.75rem;font-weight:600;color:${statutColors[u.statut]||'var(--text-d)'};">
                    <i class="fa-solid ${statutIcons[u.statut]||'fa-circle'}"></i> ${u.statut || 'actif'}
                </span>
            </td>
            <td style="font-family:var(--fm);font-size:.75rem;color:var(--text-d);">${joined}</td>
            <td style="font-family:var(--fm);font-size:.75rem;color:var(--text-d);">${lastCon}</td>
            <td>
                <div class="td-actions">
                    <button class="ab ab-edit" onclick="openQuickRole(${u.id},'${u.name.replace(/'/g,"\\'")}','${u.role}','${u.statut||'actif'}')" title="Changer rôle/statut">
                        <i class="fa-solid fa-shield-halved"></i>
                    </button>
                    <button class="ab ab-edit" onclick="editUser(${JSON.stringify(u).replace(/"/g,'&quot;')})" title="Modifier">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="ab ab-del" onclick="confirmDelete('/api/v1/users/${u.id}','${u.name.replace(/'/g,"\\'")}')" title="Supprimer">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ── Pagination ──
function renderPag(d) {
    const last = Math.ceil((d.total || 0) / (d.per_page || 20));
    const page = d.current_page || 1;
    document.getElementById('usersPagInfo').textContent = `Page ${page}/${last || 1} · ${d.total || 0} utilisateurs`;
    const pages = document.getElementById('usersPagPages');
    pages.innerHTML = '';
    for (let i = 1; i <= Math.min(last, 10); i++) {
        const b = document.createElement('button');
        b.className = 'pg' + (i === page ? ' active' : '');
        b.textContent = i;
        b.onclick = () => loadUsers(i);
        pages.appendChild(b);
    }
}

// ── Quick role modal ──
function openQuickRole(id, name, role, statut) {
    document.getElementById('quickRoleUserId').value     = id;
    document.getElementById('quickRoleDesc').textContent = `Modifier le rôle et le statut de « ${name} »`;
    document.getElementById('quickRoleValue').value      = role;
    document.getElementById('quickStatutValue').value    = statut;
    openModal('quickRoleModal');
}

async function applyQuickRole() {
    const id     = document.getElementById('quickRoleUserId').value;
    const role   = document.getElementById('quickRoleValue').value;
    const statut = document.getElementById('quickStatutValue').value;

    const r = await apiFetch(`/api/v1/users/${id}`, {
        method: 'PUT',
        body:   JSON.stringify({ role, statut })
    });
    const d = await r.json();

    if (r.ok) {
        showToast(`Rôle mis à jour → ${role} ✓`);
        closeModal('quickRoleModal');
        loadUsers(currentPage);
    } else {
        showToast(d.message || 'Erreur', 'error');
    }
}

// ── Modal utilisateur ──
function updateAvatarPreview() {
    const name  = document.getElementById('user-name').value  || 'N';
    const email = document.getElementById('user-email').value || '—';
    const initials = name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || '?';
    document.getElementById('avatarPreview').textContent = initials;
    document.getElementById('avatarName').textContent   = name || 'Nouveau compte';
    document.getElementById('avatarEmail').textContent  = email;
}

function updateRoleHint() {
    const hints = {
        visiteur:     'Peut lire les articles et commenter.',
        contributeur: 'Peut créer et gérer ses propres articles.',
        admin:        'Accès complet au dashboard et à toutes les fonctionnalités.',
    };
    document.getElementById('roleHint').textContent = hints[document.getElementById('user-role').value] || '';
}

function togglePwd(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const ico = document.getElementById(iconId);
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'fa-regular fa-eye-slash';
    } else {
        inp.type = 'password';
        ico.className = 'fa-regular fa-eye';
    }
}

function editUser(u) {
    document.getElementById('user-id').value       = u.id;
    document.getElementById('user-name').value     = u.name;
    document.getElementById('user-email').value    = u.email;
    document.getElementById('user-role').value     = u.role;
    document.getElementById('user-statut').value   = u.statut || 'actif';
    document.getElementById('user-bio').value      = u.bio || '';
    document.getElementById('user-password').value = '';
    document.getElementById('user-password-confirm').value = '';

    document.getElementById('pwdRequired').style.display        = 'none';
    document.getElementById('pwdConfirmRequired').style.display = 'none';
    document.getElementById('pwdEditNote').style.display        = 'block';

    document.getElementById('userModalTitle').innerHTML =
        `<i class="fa-solid fa-user-pen" style="color:var(--dy);margin-right:8px;"></i>Modifier : ${u.name}`;

    updateAvatarPreview();
    updateRoleHint();
    openModal('userModal');
}

async function saveUser() {
    const errEl  = document.getElementById('userModalError');
    errEl.style.display = 'none';

    const id       = document.getElementById('user-id').value;
    const name     = document.getElementById('user-name').value.trim();
    const email    = document.getElementById('user-email').value.trim();
    const role     = document.getElementById('user-role').value;
    const statut   = document.getElementById('user-statut').value;
    const bio      = document.getElementById('user-bio').value.trim();
    const password = document.getElementById('user-password').value;
    const confirm  = document.getElementById('user-password-confirm').value;

    if (!name)  { showErr(errEl, 'Le nom est obligatoire.'); return; }
    if (!email) { showErr(errEl, 'L\'email est obligatoire.'); return; }
    if (!id && !password) { showErr(errEl, 'Le mot de passe est obligatoire pour un nouvel utilisateur.'); return; }
    if (password && password.length < 8) { showErr(errEl, 'Le mot de passe doit contenir au moins 8 caractères.'); return; }
    if (password && password !== confirm) { showErr(errEl, 'Les mots de passe ne correspondent pas.'); return; }

    const payload = { name, email, role, statut, bio: bio || null };
    if (password) {
        payload.password              = password;
        payload.password_confirmation = confirm;
    }

    const method = id ? 'PUT' : 'POST';
    const url    = id ? `/api/v1/users/${id}` : '/api/v1/users';

    try {
        const r = await apiFetch(url, {
            method,
            body: JSON.stringify(payload)
        });
        const d = await r.json();

        if (r.ok) {
            showToast(id ? 'Utilisateur modifié ✓' : 'Utilisateur créé ✓');
            closeUserModal();
            loadUsers(currentPage);
        } else {
            const msg = d.errors ? Object.values(d.errors).flat().join(' | ') : d.message;
            showErr(errEl, msg);
        }
    } catch {
        showErr(errEl, 'Erreur réseau.');
    }
}

function showErr(el, msg) {
    el.style.display = 'block';
    el.innerHTML = '<i class="fa-solid fa-circle-xmark" style="margin-right:6px;"></i>' + msg;
}

function closeUserModal() {
    document.getElementById('userModal').classList.remove('open');
    document.getElementById('user-id').value = '';
    document.getElementById('userModalTitle').innerHTML =
        '<i class="fa-solid fa-user-plus" style="color:var(--dy);margin-right:8px;"></i>Nouvel utilisateur';
    document.getElementById('pwdRequired').style.display        = 'inline';
    document.getElementById('pwdConfirmRequired').style.display = 'inline';
    document.getElementById('pwdEditNote').style.display        = 'none';
    document.getElementById('userModalError').style.display     = 'none';
    ['user-name','user-email','user-bio','user-password','user-password-confirm'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.getElementById('user-role').value   = 'visiteur';
    document.getElementById('user-statut').value = 'actif';
    updateAvatarPreview();
}

function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

// Init
loadUsers(1);
updateRoleHint();
</script>
@endpush