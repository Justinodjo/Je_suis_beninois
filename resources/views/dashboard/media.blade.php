{{-- ══════════════════════════════════════════
   DASHBOARD — MÉDIATHÈQUE
   Route: GET /dashboard/media → DashboardController@media
══════════════════════════════════════════ --}}
@extends('layouts.dashboard')
@section('title','Médiathèque')
@section('page_title','Médiathèque')
@section('breadcrumb')
<span class="sep">/</span> <span>Médias</span>
@endsection

@section('content')

{{-- ══ ZONE UPLOAD ══ --}}
<div class="upload-zone" id="uploadZone" style="margin-bottom:20px;"
     onclick="document.getElementById('mediaFile').click()">
    <div class="upload-icon">
        <i class="fa-solid fa-cloud-arrow-up"></i>
    </div>
    <div style="font-size:.95rem;font-weight:600;margin-bottom:6px;color:var(--text);">
        Glisser des fichiers ici ou cliquer pour choisir
    </div>
    <div style="font-size:.78rem;color:var(--text-d);">
        <i class="fa-solid fa-image"></i> PNG, JPG, WebP &nbsp;·&nbsp;
        <i class="fa-solid fa-video"></i> MP4 &nbsp;·&nbsp; Max 10 MB par fichier
    </div>
    <input type="file" id="mediaFile" multiple accept="image/*,video/*"
           style="display:none" onchange="uploadFiles(this.files)">
</div>

<div id="uploadProgress" style="display:none;margin-bottom:16px;background:var(--bg-c);border:1px solid var(--border);border-radius:var(--r);padding:16px;">
    <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:8px;">
        <span id="uploadFileName" style="color:var(--text-m);">
            <i class="fa-solid fa-spinner fa-spin"></i> Téléchargement…
        </span>
        <span id="uploadPct" style="color:var(--text-d);font-family:var(--fm);">0%</span>
    </div>
    <div class="prog-bar">
        <div class="prog-fill" id="uploadBar" style="width:0%;background:var(--dv-l);"></div>
    </div>
</div>

{{-- ══ FILTRES & VUE ══ --}}
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;align-items:center;">
    <div style="position:relative;">
        <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-d);font-size:.8rem;pointer-events:none;"></i>
        <input type="text" id="mediaSearch" class="d-input search"
               placeholder="Rechercher…"
               style="width:200px;padding-left:32px;"
               oninput="filterMedia()">
    </div>
    <select id="mediaType" class="d-input d-select" onchange="filterMedia()">
        <option value="">Tous les types</option>
        <option value="image">Images</option>
        <option value="video">Vidéos</option>
    </select>
    <div style="margin-left:auto;display:flex;gap:6px;align-items:center;">
        <span style="font-size:.78rem;color:var(--text-d);" id="mediaCount">— médias</span>
        <button onclick="setView('grid')" class="t-btn" id="viewGrid" title="Vue grille">
            <i class="fa-solid fa-grip"></i>
        </button>
        <button onclick="setView('list')" class="t-btn" id="viewList" title="Vue liste">
            <i class="fa-solid fa-list"></i>
        </button>
    </div>
</div>

{{-- ══ GRILLE MÉDIAS ══ --}}
<div id="mediaViewGrid">
    <div class="media-grid" id="mediaGrid"
         style="grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;">
        <div style="color:var(--text-d);text-align:center;grid-column:1/-1;padding:40px;">
            <i class="fa-solid fa-spinner fa-spin" style="font-size:1.5rem;"></i>
        </div>
    </div>
</div>

{{-- ══ LISTE MÉDIAS ══ --}}
<div id="mediaViewList" style="display:none;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Fichier</th>
                    <th>Type</th>
                    <th>Dimensions</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="mediaList"></tbody>
        </table>
    </div>
</div>

<div class="pag" id="mediaPag"
     style="background:var(--bg-c);border:1px solid var(--border);border-radius:0 0 var(--r) var(--r);border-top:none;">
    <span id="mediaPagInfo">-</span>
    <div class="pag-pages" id="mediaPagPages"></div>
</div>

{{-- ══ MODAL DÉTAIL MÉDIA ══ --}}
<div class="modal-back" id="mediaModal" onclick="if(event.target===this)closeMediaModal()">
    <div class="modal" style="max-width:700px;">
        <div class="modal-head">
            <div class="modal-title" id="mediaModalTitle">
                <i class="fa-solid fa-photo-film" style="color:var(--dy);margin-right:8px;"></i>Détails du média
            </div>
            <button class="modal-x" onclick="closeMediaModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div>
                    <div id="mediaPreviewWrap"
                         style="border-radius:8px;overflow:hidden;background:var(--bg-c2);aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-image" style="font-size:2.5rem;color:rgba(255,255,255,.1);"></i>
                    </div>
                </div>
                <div>
                    <div class="f-group">
                        <label class="f-label">
                            <i class="fa-solid fa-pen" style="margin-right:4px;color:var(--dv-l);"></i>Nom du fichier
                        </label>
                        <input type="text" id="media-nom" class="f-control" placeholder="Nom descriptif…">
                    </div>
                    <div class="f-group">
                        <label class="f-label">
                            <i class="fa-solid fa-link" style="margin-right:4px;color:var(--dv-l);"></i>URL directe
                        </label>
                        <div style="display:flex;gap:6px;">
                            <input type="text" id="media-url-display" class="f-control"
                                   readonly style="font-family:var(--fm);font-size:.72rem;">
                            <button onclick="copyUrl()" class="btn-d outline sm" title="Copier l'URL">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div style="padding:12px;background:rgba(255,255,255,.04);border-radius:8px;font-size:.78rem;color:var(--text-d);">
                        <div>
                            <strong style="color:var(--text-m);">
                                <i class="fa-solid fa-file" style="margin-right:4px;"></i>Type :
                            </strong>
                            <span id="media-type-info">—</span>
                        </div>
                        <div style="margin-top:4px;">
                            <strong style="color:var(--text-m);">
                                <i class="fa-solid fa-up-right-and-down-left-from-center" style="margin-right:4px;"></i>Dimensions :
                            </strong>
                            <span id="media-dims">—</span>
                        </div>
                        <div style="margin-top:4px;">
                            <strong style="color:var(--text-m);">
                                <i class="fa-solid fa-weight-hanging" style="margin-right:4px;"></i>Taille :
                            </strong>
                            <span id="media-size">—</span>
                        </div>
                        <div style="margin-top:4px;">
                            <strong style="color:var(--text-m);">
                                <i class="fa-regular fa-calendar" style="margin-right:4px;"></i>Ajouté :
                            </strong>
                            <span id="media-date">—</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="deleteCurrentMedia()" class="btn-d danger">
                <i class="fa-solid fa-trash"></i> Supprimer
            </button>
            <button onclick="closeMediaModal()" class="btn-d outline">
                <i class="fa-solid fa-xmark"></i> Fermer
            </button>
            <button onclick="saveMediaName()" class="btn-d primary">
                <i class="fa-solid fa-floppy-disk"></i> Enregistrer
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentView  = 'grid';
let currentMedia = null;
let currentPage  = 1;
let allMedia     = [];

// ── Charger médias ──
async function loadMedia(page=1) {
    currentPage = page;
    const search = document.getElementById('mediaSearch').value;
    const type   = document.getElementById('mediaType').value;
    let url = `/api/v1/media?page=${page}&per_page=24`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (type)   url += `&type=${encodeURIComponent(type)}`;

    try {
        const r = await fetch(url, {headers:{Accept:'application/json'}});
        const d = await r.json();
        allMedia = d.data||[];
        document.getElementById('mediaCount').textContent = (d.total||0) + ' média' + ((d.total||0) !== 1 ? 's' : '');
        renderGrid(allMedia);
        renderList(allMedia);
        renderMediaPag(d);
    } catch {
        showToast('Erreur de chargement', 'error');
    }
}

// ── Grille ──
function renderGrid(medias) {
    const grid = document.getElementById('mediaGrid');
    grid.innerHTML = medias.length
        ? medias.map(m => `
            <div class="m-item"
                 style="border-radius:10px;border:1px solid var(--border);cursor:pointer;transition:all .2s;position:relative;aspect-ratio:1;overflow:hidden;"
                 onclick="openMediaDetail(${JSON.stringify(m).replace(/"/g,'&quot;')})"
                 onmouseover="this.querySelector('.m-overlay').style.opacity='1'"
                 onmouseout="this.querySelector('.m-overlay').style.opacity='0'">

                ${m.type === 'video'
                    ? `<div style="width:100%;height:100%;background:var(--bg-c2);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:6px;">
                           <i class="fa-solid fa-video" style="font-size:2rem;color:var(--text-d);"></i>
                           <span style="font-size:.65rem;color:var(--text-d);">${(m.nom||'Vidéo').substring(0,20)}</span>
                       </div>`
                    : `<img src="${m.url_thumbnail||m.url}"
                            alt="${m.nom||'Média'}"
                            style="width:100%;height:100%;object-fit:cover;"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                        >
                        <div style="display:none;width:100%;height:100%;align-items:center;justify-content:center;background:var(--bg-c2);">
                            <i class="fa-solid fa-image-slash" style="font-size:1.5rem;color:var(--text-d);"></i>
                        </div>`
                }

                <div class="m-overlay"
                     style="position:absolute;inset:0;background:rgba(0,0,0,.55);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;opacity:0;transition:opacity .18s;">
                    <div style="color:#fff;font-size:.68rem;font-weight:600;text-align:center;padding:0 8px;line-height:1.3;">
                        ${(m.nom||'').substring(0,28)}${(m.nom||'').length>28?'…':''}
                    </div>
                    <div style="display:flex;gap:6px;">
                        <button onclick="event.stopPropagation();copyUrl('${m.url}')"
                                class="ab ab-view"
                                style="width:auto;padding:4px 10px;font-size:.7rem;"
                                title="Copier l'URL">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                        <button onclick="event.stopPropagation();confirmDelete('/api/v1/media/${m.id}','${(m.nom||'').replace(/'/g,"\\'")}')"
                                class="ab ab-del"
                                title="Supprimer">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>

                ${m.type === 'video'
                    ? `<span style="position:absolute;top:6px;left:6px;background:rgba(0,0,0,.6);color:#fff;font-size:.6rem;padding:2px 6px;border-radius:4px;display:flex;align-items:center;gap:4px;">
                           <i class="fa-solid fa-video"></i> Vidéo
                       </span>`
                    : ''}
            </div>
        `).join('')
        : `<div style="grid-column:1/-1;padding:56px 24px;text-align:center;color:var(--text-d);">
               <i class="fa-solid fa-photo-film" style="font-size:3rem;margin-bottom:16px;display:block;opacity:.3;"></i>
               <div>Aucun média</div>
           </div>`;
}

// ── Liste ──
function renderList(medias) {
    const tbody = document.getElementById('mediaList');
    tbody.innerHTML = medias.map(m => `
        <tr>
            <td>
                <div class="td-main">
                    ${m.type === 'image'
                        ? `<img src="${m.url_thumbnail||m.url}"
                                class="td-thumb"
                                alt="${m.nom||''}"
                                onerror="this.style.display='none'">`
                        : `<div class="td-thumb" style="display:flex;align-items:center;justify-content:center;background:var(--bg-c2);">
                               <i class="fa-solid fa-video" style="color:var(--text-d);"></i>
                           </div>`
                    }
                    <div>
                        <div class="td-name">${m.nom||'Sans nom'}</div>
                        <div class="td-sub" style="font-family:var(--fm);font-size:.68rem;">${(m.url||'').substring(0,40)}…</div>
                    </div>
                </div>
            </td>
            <td>
                <span class="bx bx-${m.type === 'video' ? 'interview' : 'vert'}"
                      style="display:inline-flex;align-items:center;gap:4px;">
                    <i class="fa-solid fa-${m.type === 'video' ? 'video' : 'image'}"></i> ${m.type||'image'}
                </span>
            </td>
            <td style="font-family:var(--fm);font-size:.75rem;">
                ${m.largeur && m.hauteur ? m.largeur + '×' + m.hauteur : '—'}
            </td>
            <td style="font-family:var(--fm);font-size:.73rem;color:var(--text-d);">
                ${m.created_at ? new Date(m.created_at).toLocaleDateString('fr') : '—'}
            </td>
            <td>
                <div class="td-actions">
                    <button class="ab ab-view"
                            onclick="openMediaDetail(${JSON.stringify(m).replace(/"/g,'&quot;')})"
                            title="Voir les détails">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <button class="ab ab-view"
                            onclick="copyUrl('${m.url}')"
                            title="Copier l'URL">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                    <button class="ab ab-del"
                            onclick="confirmDelete('/api/v1/media/${m.id}','${(m.nom||'').replace(/'/g,"\\'")}')"
                            title="Supprimer">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// ── Pagination ──
function renderMediaPag(d) {
    const last = Math.ceil((d.total||0) / (d.per_page||24));
    const page = d.current_page || 1;
    document.getElementById('mediaPagInfo').textContent = `Page ${page}/${last||1} · ${d.total||0} médias`;
    const pages = document.getElementById('mediaPagPages');
    pages.innerHTML = '';
    for (let i = 1; i <= Math.min(last, 8); i++) {
        const b = document.createElement('button');
        b.className = 'pg' + (i === page ? ' active' : '');
        b.textContent = i;
        b.onclick = () => loadMedia(i);
        pages.appendChild(b);
    }
}

// ── Détail média ──
function openMediaDetail(m) {
    currentMedia = m;
    document.getElementById('mediaModalTitle').innerHTML =
        `<i class="fa-solid fa-photo-film" style="color:var(--dy);margin-right:8px;"></i>${m.nom||'Détails'}`;
    document.getElementById('media-nom').value         = m.nom || '';
    document.getElementById('media-url-display').value = m.url || '';
    document.getElementById('media-type-info').textContent  = m.type || '—';
    document.getElementById('media-dims').textContent       = m.largeur && m.hauteur ? m.largeur + '×' + m.hauteur + ' px' : '—';
    document.getElementById('media-size').textContent       = m.poids ? (m.poids / 1024).toFixed(0) + ' KB' : '—';
    document.getElementById('media-date').textContent       = m.created_at ? new Date(m.created_at).toLocaleDateString('fr') : '—';

    const preview = document.getElementById('mediaPreviewWrap');
    if (m.type === 'video') {
        preview.innerHTML = `<video controls style="width:100%;height:100%;border-radius:8px;">
            <source src="${m.url}">
        </video>`;
    } else {
        preview.innerHTML = `<img src="${m.url}"
            alt="${m.nom||''}"
            style="width:100%;height:100%;object-fit:contain;"
            onerror="this.style.display='none';this.insertAdjacentHTML('afterend','<div style=\\'display:flex;align-items:center;justify-content:center;width:100%;height:100%;\\'>
                <i class=\\'fa-solid fa-image-slash\\' style=\\'font-size:2rem;color:rgba(255,255,255,.15);\\'></i></div>')">`;
    }

    document.getElementById('mediaModal').classList.add('open');
}

function closeMediaModal() {
    document.getElementById('mediaModal').classList.remove('open');
}

async function saveMediaName() {
    if (!currentMedia) return;
    const nom = document.getElementById('media-nom').value;
    await fetch(`/api/v1/media/${currentMedia.id}`, {
        method: 'PUT',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},
        body: JSON.stringify({ nom })
    });
    showToast('Média mis à jour ✓');
    loadMedia(currentPage);
    closeMediaModal();
}

async function deleteCurrentMedia() {
    if (!currentMedia || !confirm(`Supprimer "${currentMedia.nom||'ce média'}" ?`)) return;
    await fetch(`/api/v1/media/${currentMedia.id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': CSRF_TOKEN}
    });
    showToast('Média supprimé');
    closeMediaModal();
    loadMedia(currentPage);
}

function copyUrl(url) {
    const u = url || document.getElementById('media-url-display').value;
    navigator.clipboard.writeText(u).then(() => showToast('URL copiée !'));
}

// ── Vue grille / liste ──
function setView(v) {
    currentView = v;
    document.getElementById('mediaViewGrid').style.display = v === 'grid' ? 'block' : 'none';
    document.getElementById('mediaViewList').style.display = v === 'list' ? 'block' : 'none';
    document.getElementById('viewGrid').style.background   = v === 'grid' ? 'rgba(255,255,255,.12)' : '';
    document.getElementById('viewList').style.background   = v === 'list' ? 'rgba(255,255,255,.12)' : '';
}

// ── Upload ──
async function uploadFiles(files) {
    if (!files.length) return;
    const prog = document.getElementById('uploadProgress');
    const bar  = document.getElementById('uploadBar');
    const pct  = document.getElementById('uploadPct');
    prog.style.display = 'block';
    let done = 0;

    for (const file of files) {
        document.getElementById('uploadFileName').innerHTML =
            `<i class="fa-solid fa-spinner fa-spin"></i> ${file.name}`;
        const fd = new FormData();
        fd.append('fichier', file);
        fd.append('nom', file.name.replace(/\.[^/.]+$/, ''));
        fd.append('type', file.type.startsWith('video') ? 'video' : 'image');

        try {
            const r = await fetch('/api/v1/media', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json'},
                body: fd
            });
            if (r.ok) { done++; showToast(file.name + ' uploadé ✓'); }
            else showToast(file.name + ' : erreur upload', 'error');
        } catch {
            showToast(file.name + ' : erreur réseau', 'error');
        }

        const p = Math.round(done / files.length * 100);
        bar.style.width = p + '%';
        pct.textContent = p + '%';
    }

    setTimeout(() => {
        prog.style.display = 'none';
        bar.style.width = '0';
        pct.textContent = '0%';
        loadMedia(1);
    }, 800);
}

// Drag & drop
const _zone = document.getElementById('uploadZone');
_zone.addEventListener('dragover',  e => { e.preventDefault(); _zone.classList.add('drag'); });
_zone.addEventListener('dragleave', ()  => _zone.classList.remove('drag'));
_zone.addEventListener('drop',      e => { e.preventDefault(); _zone.classList.remove('drag'); uploadFiles(e.dataTransfer.files); });

// Filtre avec debounce
let _ft;
function filterMedia() { clearTimeout(_ft); _ft = setTimeout(() => loadMedia(1), 400); }

// Init
loadMedia(1);
setView('grid');
</script>
@endpush