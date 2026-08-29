{{-- ══════════════════════════════════════════
   DASHBOARD — CATÉGORIES & TAGS
   Route: GET /dashboard/categories → DashboardController@categories
══════════════════════════════════════════ --}}
@extends('layouts.dashboard')
@section('title','Catégories')
@section('page_title','Catégories & Tags')
@section('breadcrumb')
<span class="sep">/</span> <span>Catégories</span>
@endsection

@section('topbar_actions')
<button onclick="openModal('catModal')" class="btn-d primary">
    <i class="fa-solid fa-plus"></i> Nouvelle catégorie
</button>
@endsection

@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    {{-- ══ CATÉGORIES ══ --}}
    <div class="table-wrap">
        <div class="table-top">
            <div>
                <div class="sec-title">
                    <i class="fa-solid fa-tags" style="color:var(--dv-l);margin-right:6px;"></i>Catégories
                </div>
                <div class="sec-sub" id="catCount">— catégories</div>
            </div>
            <button onclick="openModal('catModal')" class="btn-d primary sm">
                <i class="fa-solid fa-plus"></i> Ajouter
            </button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Couleur</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="catsBody">
                <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--text-d);">
                    <i class="fa-solid fa-spinner fa-spin"></i> Chargement…
                </td></tr>
            </tbody>
        </table>
    </div>

    {{-- ══ TAGS ══ --}}
    <div class="table-wrap">
        <div class="table-top">
            <div>
                <div class="sec-title">
                    <i class="fa-solid fa-hashtag" style="color:var(--dy);margin-right:6px;"></i>Tags
                </div>
                <div class="sec-sub" id="tagCount">— tags</div>
            </div>
            <button onclick="openModal('tagModal')" class="btn-d outline sm">
                <i class="fa-solid fa-plus"></i> Ajouter tag
            </button>
        </div>
        <div style="padding:16px;">
            <div id="tagsCloud" style="display:flex;flex-wrap:wrap;gap:8px;min-height:60px;">
                <div style="color:var(--text-d);font-size:.8rem;">
                    <i class="fa-solid fa-spinner fa-spin"></i> Chargement…
                </div>
            </div>
        </div>
        <div style="border-top:1px solid var(--border);">
            <table>
                <thead>
                    <tr><th>Nom</th><th>Utilisations</th><th>Actions</th></tr>
                </thead>
                <tbody id="tagsBody">
                    <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--text-d);">
                        <i class="fa-solid fa-spinner fa-spin"></i> Chargement…
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══ MODAL CATÉGORIE ══ --}}
<div class="modal-back" id="catModal" onclick="if(event.target===this)closeModal('catModal')">
    <div class="modal" style="max-width:500px;">
        <div class="modal-head">
            <div class="modal-title" id="catModalTitle">
                <i class="fa-solid fa-tag" style="color:var(--dy);margin-right:8px;"></i>Nouvelle catégorie
            </div>
            <button class="modal-x" onclick="closeModal('catModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="cat-id">

            <div class="f-group">
                <label class="f-label">Nom *</label>
                <input type="text" id="cat-nom" class="f-control"
                       placeholder="Ex: Culture, Traditions, Patrimoine…">
            </div>

            <div class="f-row">
                <div class="f-group">
                    <label class="f-label">
                        <i class="fa-solid fa-palette" style="margin-right:4px;color:var(--dv-l);"></i>Couleur (hex)
                    </label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input type="color" id="cat-color-picker" value="#1B5E20"
                               oninput="document.getElementById('cat-couleur').value=this.value"
                               style="width:40px;height:38px;border:1px solid var(--border);border-radius:6px;background:var(--bg-c2);cursor:pointer;padding:2px;">
                        <input type="text" id="cat-couleur" class="f-control"
                               placeholder="#1B5E20" value="#1B5E20"
                               oninput="document.getElementById('cat-color-picker').value=this.value"
                               style="font-family:var(--fm);">
                    </div>
                </div>
                <div class="f-group">
                    <label class="f-label">Icône (emoji ou texte)</label>
                    <input type="text" id="cat-icone" class="f-control"
                           placeholder="Icône">
                </div>
            </div>

            <div class="f-group">
                <label class="f-label">Description</label>
                <textarea id="cat-desc" class="f-control" rows="2"
                          placeholder="Description courte…"></textarea>
            </div>

            <div class="f-group">
                <label class="f-label">Catégorie parente (optionnel)</label>
                <select id="cat-parent" class="f-control">
                    <option value="">Aucune (catégorie racine)</option>
                </select>
            </div>

            {{-- Prévisualisation badge --}}
            <div style="margin-top:8px;">
                <label class="f-label">
                    <i class="fa-solid fa-eye" style="margin-right:4px;color:var(--dv-l);"></i>Aperçu
                </label>
                <div id="catPreview" style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:6px;font-size:.8rem;font-weight:700;background:#1B5E20;color:#fff;">
                    <i class="fa-solid fa-masks-theater"></i> Ma catégorie
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="closeModal('catModal')" class="btn-d outline">
                <i class="fa-solid fa-xmark"></i> Annuler
            </button>
            <button onclick="saveCategory()" class="btn-d primary">
                <i class="fa-solid fa-floppy-disk"></i> Enregistrer
            </button>
        </div>
    </div>
</div>

{{-- ══ MODAL TAG ══ --}}
<div class="modal-back" id="tagModal" onclick="if(event.target===this)closeModal('tagModal')">
    <div class="modal" style="max-width:420px;">
        <div class="modal-head">
            <div class="modal-title" id="tagModalTitle">
                <i class="fa-solid fa-hashtag" style="color:var(--dy);margin-right:8px;"></i>Nouveau tag
            </div>
            <button class="modal-x" onclick="closeModal('tagModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="tag-id">
            <div class="f-group">
                <label class="f-label">Nom du tag *</label>
                <input type="text" id="tag-nom" class="f-control"
                       placeholder="Ex: vodoun, abomey, traditions…">
                <div class="f-hint">
                    <i class="fa-solid fa-circle-info" style="margin-right:4px;"></i>
                    Automatiquement converti en minuscules
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="closeModal('tagModal')" class="btn-d outline">
                <i class="fa-solid fa-xmark"></i> Annuler
            </button>
            <button onclick="saveTag()" class="btn-d primary">
                <i class="fa-solid fa-plus"></i> Créer le tag
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Charger catégories ──
async function loadCategories() {
    const r = await apiFetch('/api/v1/categories',{headers:{Accept:'application/json'}});
    const d = await r.json();
    const cats = d.data || d || [];
    document.getElementById('catCount').textContent = cats.length + ' catégorie' + (cats.length !== 1 ? 's' : '');

    // Remplir select parent
    const parentSel = document.getElementById('cat-parent');
    parentSel.innerHTML = '<option value="">Aucune (catégorie racine)</option>';
    cats.forEach(c => {
        parentSel.innerHTML += `<option value="${c.id}">${c.icone||''} ${c.nom}</option>`;
    });

    // Tableau
    const tbody = document.getElementById('catsBody');
    tbody.innerHTML = cats.length ? cats.map(c => `
        <tr>
            <td>
                <div class="td-main">
                    <div style="width:8px;height:8px;border-radius:50%;background:${c.couleur||'#1B5E20'};flex-shrink:0;"></div>
                    <div>
                        <div class="td-name">${c.icone ? c.icone+' ' : ''}${c.nom}</div>
                        <div class="td-sub">${c.description||'Aucune description'}</div>
                    </div>
                </div>
            </td>
            <td>
                <div style="display:inline-flex;align-items:center;gap:6px;font-size:.72rem;font-family:var(--fm);">
                    <div style="width:16px;height:16px;border-radius:3px;background:${c.couleur||'#1B5E20'};"></div>
                    ${c.couleur||'#1B5E20'}
                </div>
            </td>
            <td style="font-family:var(--fm);font-size:.8rem;">${c.articles_count||0}</td>
            <td>
                <div class="td-actions">
                    <button class="ab ab-edit"
                            onclick="editCategory(${JSON.stringify(c).replace(/"/g,'&quot;')})"
                            title="Modifier">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="ab ab-del"
                            onclick="confirmDelete('/api/v1/categories/${c.id}','${c.nom.replace(/'/g,"\\'")}')"
                            title="Supprimer">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('')
    : `<tr><td colspan="4">
        <div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-tags"></i></div>
            <div class="empty-msg">Aucune catégorie</div>
        </div>
       </td></tr>`;
}

// ── Charger tags ──
async function loadTags() {
    const r = await apiFetch('/api/v1/tags',{headers:{Accept:'application/json'}});
    const d = await r.json();
    const tags = d.data || d || [];
    document.getElementById('tagCount').textContent = tags.length + ' tag' + (tags.length !== 1 ? 's' : '');

    // Nuage de tags
    document.getElementById('tagsCloud').innerHTML = tags.map(t => `
        <span style="padding:5px 12px;border-radius:20px;background:rgba(255,215,0,.1);border:1px solid rgba(255,215,0,.2);color:var(--dy);font-size:${Math.max(0.72,Math.min(1.1,0.72+(t.nb_utilisation||t.articles_count||0)/20))}rem;cursor:pointer;"
              onclick="editTag(${JSON.stringify(t).replace(/"/g,'&quot;')})">
            <i class="fa-solid fa-hashtag" style="font-size:.65em;opacity:.7;"></i>${t.nom}
            <span style="font-size:.62rem;opacity:.6;">${t.nb_utilisation||t.articles_count||0}</span>
        </span>
    `).join('') || '<span style="color:var(--text-d);font-size:.8rem;">Aucun tag</span>';

    // Tableau
    const tbody = document.getElementById('tagsBody');
    tbody.innerHTML = tags.length
        ? tags.sort((a,b) => (b.nb_utilisation||b.articles_count||0) - (a.nb_utilisation||a.articles_count||0))
              .slice(0,15).map(t => `
            <tr>
                <td>
                    <span class="td-name">
                        <i class="fa-solid fa-hashtag" style="color:var(--text-d);font-size:.75em;margin-right:2px;"></i>${t.nom}
                    </span>
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="prog-bar" style="width:80px;">
                            <div class="prog-fill" style="width:${Math.min(100,(t.nb_utilisation||t.articles_count||0)/5*100)}%;background:var(--dy-d);"></div>
                        </div>
                        <span style="font-family:var(--fm);font-size:.75rem;">${t.nb_utilisation||t.articles_count||0}</span>
                    </div>
                </td>
                <td>
                    <div class="td-actions">
                        <button class="ab ab-edit"
                                onclick="editTag(${JSON.stringify(t).replace(/"/g,'&quot;')})"
                                title="Modifier">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="ab ab-del"
                                onclick="confirmDelete('/api/v1/tags/${t.id}','#${t.nom}')"
                                title="Supprimer">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('')
        : `<tr><td colspan="3">
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-hashtag"></i></div>
                <div class="empty-msg">Aucun tag</div>
            </div>
           </td></tr>`;
}

// ── Preview badge catégorie ──
function updateCatPreview() {
    const nom    = document.getElementById('cat-nom').value || 'Ma catégorie';
    const couleur= document.getElementById('cat-couleur').value || '#1B5E20';
    const icone  = document.getElementById('cat-icone').value || '';
    const prev   = document.getElementById('catPreview');
    prev.textContent = (icone ? icone + ' ' : '') + nom;
    prev.style.background = couleur;
}
['cat-nom','cat-couleur','cat-icone'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', updateCatPreview);
});

// ── Sauvegarder catégorie ──
async function saveCategory() {
    const id  = document.getElementById('cat-id').value;
    const nom = document.getElementById('cat-nom').value.trim();
    if (!nom) { showToast('Le nom est obligatoire', 'error'); return; }

    const payload = {
        nom,
        couleur:     document.getElementById('cat-couleur').value || '#1B5E20',
        icone:       document.getElementById('cat-icone').value   || null,
        description: document.getElementById('cat-desc').value    || null,
        parent_id:   document.getElementById('cat-parent').value  || null,
    };

    const method = id ? 'PUT' : 'POST';
    const url    = id ? `/api/v1/categories/${id}` : '/api/v1/categories';
    const r = await apiFetch(url, {
        method,
        // headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},
        body: JSON.stringify(payload)
    });
    const d = await r.json();

    if (r.ok) {
        showToast(id ? 'Catégorie modifiée ✓' : 'Catégorie créée ✓');
        closeModal('catModal');
        loadCategories();
    } else {
        showToast(d.message || 'Erreur', 'error');
    }
}

// ── Éditer catégorie ──
function editCategory(cat) {
    document.getElementById('cat-id').value                = cat.id;
    document.getElementById('cat-nom').value               = cat.nom;
    document.getElementById('cat-couleur').value           = cat.couleur || '#1B5E20';
    document.getElementById('cat-color-picker').value      = cat.couleur || '#1B5E20';
    document.getElementById('cat-icone').value             = cat.icone  || '';
    document.getElementById('cat-desc').value              = cat.description || '';
    document.getElementById('cat-parent').value            = cat.parent_id || '';
    document.getElementById('catModalTitle').innerHTML     =
        `<i class="fa-solid fa-pen-to-square" style="color:var(--dy);margin-right:8px;"></i>Modifier : ${cat.nom}`;
    updateCatPreview();
    openModal('catModal');
}

// ── Sauvegarder tag ──
async function saveTag() {
    const id  = document.getElementById('tag-id').value;
    const nom = document.getElementById('tag-nom').value.trim().toLowerCase().replace(/\s+/g,'-');
    if (!nom) { showToast('Le nom est obligatoire', 'error'); return; }

    const method = id ? 'PUT' : 'POST';
    const url    = id ? `/api/v1/tags/${id}` : '/api/v1/tags';
    const r = await apiFetch(url, {
        method,
        // headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},
        body: JSON.stringify({ nom })
    });
    const d = await r.json();

    if (r.ok) {
        showToast(id ? 'Tag modifié ✓' : 'Tag créé ✓');
        closeModal('tagModal');
        loadTags();
    } else {
        showToast(d.message || 'Erreur', 'error');
    }
}

// ── Éditer tag ──
function editTag(tag) {
    document.getElementById('tag-id').value  = tag.id;
    document.getElementById('tag-nom').value = tag.nom;
    document.getElementById('tagModalTitle').innerHTML =
        `<i class="fa-solid fa-pen-to-square" style="color:var(--dy);margin-right:8px;"></i>Modifier : #${tag.nom}`;
    openModal('tagModal');
}

function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    if (id === 'catModal') {
        document.getElementById('cat-id').value = '';
        document.getElementById('catModalTitle').innerHTML =
            '<i class="fa-solid fa-tag" style="color:var(--dy);margin-right:8px;"></i>Nouvelle catégorie';
    }
    if (id === 'tagModal') {
        document.getElementById('tag-id').value = '';
        document.getElementById('tagModalTitle').innerHTML =
            '<i class="fa-solid fa-hashtag" style="color:var(--dy);margin-right:8px;"></i>Nouveau tag';
    }
}

loadCategories();
loadTags();
</script>
@endpush