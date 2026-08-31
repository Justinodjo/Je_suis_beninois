{{-- ══════════════════════════════════════════
   DASHBOARD — ARTICLES
   Route: GET /dashboard/articles → DashboardController@articles
══════════════════════════════════════════ --}}
@extends('layouts.dashboard')
@section('title','Articles')
@section('page_title','Articles')
@section('breadcrumb')
<span class="sep">/</span> <span>Articles</span>
@endsection

@section('topbar_actions')
<button onclick="openModal('articleModal')" class="btn-d primary">
    <i class="fa-solid fa-plus"></i> Nouvel article
</button>
@endsection

@section('content')

{{-- ══ FILTRES ══ --}}
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;align-items:center;">
    <div style="position:relative;">
        <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-d);font-size:.8rem;pointer-events:none;"></i>
        <input type="text" id="artSearch" class="d-input search"
               placeholder="Rechercher un article…"
               style="width:220px;padding-left:32px;"
               oninput="filterArticles()">
    </div>
    <select id="artFilterType" class="d-input d-select" onchange="filterArticles()">
        <option value="">Tous les types</option>
        <option value="article">Article général</option>
        <option value="tradition">Tradition</option>
        <option value="patrimoine">Patrimoine</option>
        <option value="interview">Interview</option>
        <option value="featured">Featured</option>
        <option value="galerie">Galerie</option>
    </select>
    <select id="artFilterStatut" class="d-input d-select" onchange="filterArticles()">
        <option value="">Tous les statuts</option>
        <option value="brouillon">Brouillon</option>
        <option value="publié">Publié</option>
        <option value="archivé">Archivé</option>
    </select>
    <span style="margin-left:auto;font-size:.78rem;color:var(--text-d);" id="artCount">— articles</span>
</div>

{{-- ══ TABLEAU ARTICLES ══ --}}
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Catégories</th>
                <th>Vues</th>
                <th>Likes</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="articlesBody">
            <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--text-d);">
                <i class="fa-solid fa-spinner fa-spin"></i> Chargement…
            </td></tr>
        </tbody>
    </table>
</div>

<div class="pag" id="artPag"
     style="background:var(--bg-c);border:1px solid var(--border);border-radius:0 0 var(--r) var(--r);border-top:none;">
    <span id="artPagInfo">-</span>
    <div class="pag-pages" id="artPagPages"></div>
</div>

{{-- ══ MODAL ARTICLE (CRUD) ══ --}}
@include('dashboard.partials.article-modal')

@endsection

@push('scripts')
<script>
let currentPage = 1;
let allArticles = [];
let _artFilterTimer;

// ── Charger articles ──
async function loadArticles(page = 1) {
    currentPage = page;
    const search = document.getElementById('artSearch').value;
    const type   = document.getElementById('artFilterType').value;
    const statut = document.getElementById('artFilterStatut').value;

    let url = `/api/v1/articles?page=${page}&per_page=15`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (type)   url += `&type=${encodeURIComponent(type)}`;
    if (statut) url += `&statut=${encodeURIComponent(statut)}`;

    try {
        const r = await apiFetch(url, {headers:{Accept:'application/json'}});
        const d = await r.json();
        allArticles = d.data || [];
        document.getElementById('artCount').textContent =
            (d.total ?? allArticles.length) + ' article' + ((d.total ?? allArticles.length) !== 1 ? 's' : '');
        renderArticles(allArticles);
        renderArtPag(d);
    } catch (e) {
        console.error(e);
        showToast('Erreur de chargement des articles', 'error');
    }
}

// ── Rendu tableau ──
function renderArticles(articles) {
    const tbody = document.getElementById('articlesBody');

    const typeIcons = {
        article:    '<i class="fa-solid fa-newspaper"></i>',
        tradition:  '<i class="fa-solid fa-drum"></i>',
        patrimoine: '<i class="fa-solid fa-landmark"></i>',
        interview:  '<i class="fa-solid fa-microphone"></i>',
        featured:   '<i class="fa-solid fa-star"></i>',
        galerie:    '<i class="fa-solid fa-images"></i>',
    };
    const statutIcons = {
        'publié':    '<i class="fa-solid fa-circle-check"></i>',
        'brouillon': '<i class="fa-solid fa-file-pen"></i>',
        'archivé':   '<i class="fa-solid fa-box-archive"></i>',
    };

    if (!articles.length) {
        tbody.innerHTML = `<tr><td colspan="8">
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-newspaper"></i></div>
                <div class="empty-msg">Aucun article</div>
            </div>
        </td></tr>`;
        return;
    }

    tbody.innerHTML = articles.map(a => `
        <tr>
            <td>
                <div class="td-main">
                    <div class="td-name">${(a.titre||'').substring(0,50)}${(a.titre||'').length>50?'…':''}</div>
                </div>
            </td>
            <td>
                <span style="display:inline-flex;align-items:center;gap:4px;">
                    ${typeIcons[a.type]||typeIcons.article} ${a.type||'article'}
                </span>
            </td>
            <td>
                <span style="display:inline-flex;align-items:center;gap:4px;">
                    ${statutIcons[a.statut]||statutIcons.brouillon} ${a.statut||'brouillon'}
                </span>
            </td>
            <td style="font-size:.75rem;color:var(--text-d);">
                ${(a.categories||[]).map(c=>c.nom).join(', ') || '—'}
            </td>
            <td style="font-family:var(--fm);font-size:.78rem;">${(a.nb_vues||0).toLocaleString()}</td>
            <td style="font-family:var(--fm);font-size:.78rem;">${a.nb_likes||0}</td>
            <td style="font-family:var(--fm);font-size:.72rem;color:var(--text-d);">
                ${a.created_at ? new Date(a.created_at).toLocaleDateString('fr') : '—'}
            </td>
            <td>
                <div class="td-actions">
                    <button class="ab ab-view" onclick="window.open('/culture/article/${a.slug}','_blank')" title="Voir">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <button class="ab ab-edit" onclick="editArticle(${a.id})" title="Modifier">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="ab ab-del" onclick="confirmDelete('/api/v1/articles/${a.id}','${(a.titre||'').replace(/'/g,"\\'")}')" title="Supprimer">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// ── Pagination ──
function renderArtPag(d) {
    const last = Math.ceil((d.total||0) / (d.per_page||15));
    const page = d.current_page || 1;
    document.getElementById('artPagInfo').textContent = `Page ${page}/${last||1} · ${d.total||0} articles`;
    const pages = document.getElementById('artPagPages');
    pages.innerHTML = '';
    for (let i = 1; i <= Math.min(last, 10); i++) {
        const b = document.createElement('button');
        b.className = 'pg' + (i === page ? ' active' : '');
        b.textContent = i;
        b.onclick = () => loadArticles(i);
        pages.appendChild(b);
    }
}

// ── Filtre avec debounce ──
function filterArticles() {
    clearTimeout(_artFilterTimer);
    _artFilterTimer = setTimeout(() => loadArticles(1), 400);
}

// ── Éditer un article (ouvre la modale pré-remplie) ──
async function editArticle(id) {
    try {
        const r = await apiFetch(`/api/v1/articles/${id}`, {headers:{Accept:'application/json'}});
        const d = await r.json();
        const a = d.data || d;

        document.getElementById('art-id').value       = a.id;
        document.getElementById('art-titre').value     = a.titre || '';
        document.getElementById('art-type').value      = a.type || 'article';
        document.getElementById('art-statut').value     = a.statut || 'brouillon';
        document.getElementById('art-extrait').value    = a.extrait || '';
        document.getElementById('art-contenu').value    = a.contenu || '';
        document.getElementById('art-meta-titre').value = a.meta_titre || '';
        document.getElementById('art-meta-desc').value  = a.meta_description || '';

        document.querySelectorAll('input[name="art-cats"]').forEach(cb => {
            cb.checked = (a.categories||[]).some(c => c.id === +cb.value);
        });
        document.querySelectorAll('input[name="art-tags"]').forEach(cb => {
            cb.checked = (a.tags||[]).some(t => t.id === +cb.value);
        });

        window._pendingSelectedMediaIds = (a.medias || []).map(m => m.id);
        
        document.getElementById('modalTitle').innerHTML =
            '<i class="fa-solid fa-pen-to-square" style="color:var(--dy);margin-right:8px;"></i>Modifier l\'article';

        openModal('articleModal');
    } catch (e) {
        showToast('Erreur lors du chargement de l\'article', 'error');
    }
}

// ── Ouvrir / fermer les modales ──
function openModal(id) {
    document.getElementById(id).classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    if (id === 'articleModal' && typeof resetForm === 'function') {
        resetForm();
    }
}

loadArticles(1);
</script>
@endpush