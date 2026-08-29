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

@push('styles')
<style>
/* ══════════ ZONE UPLOAD PRO ══════════ */
.upload-zone.drag {
    border-color: var(--dv-l);
    background: rgba(56,142,60,.06);
}
.upload-zone {
    transition: all .18s;
}

/* ── File d'attente d'upload ── */
.upload-queue {
    margin-top: 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.upload-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg-c);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 10px 14px;
    animation: uploadItemIn .25s ease;
}
@keyframes uploadItemIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.upload-item-thumb {
    width: 44px; height: 44px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background: var(--bg-c2);
    display: flex;
    align-items: center;
    justify-content: center;
}
.upload-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.upload-item-thumb i { color: var(--text-d); font-size: 1.1rem; }

.upload-item-info { flex: 1; min-width: 0; }
.upload-item-name {
    font-size: .82rem;
    font-weight: 600;
    color: var(--text-m);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.upload-item-meta {
    font-size: .7rem;
    color: var(--text-d);
    margin-top: 2px;
    display: flex;
    gap: 8px;
    align-items: center;
}
.upload-item-bar {
    height: 4px;
    background: var(--border);
    border-radius: 4px;
    overflow: hidden;
    margin-top: 6px;
}
.upload-item-bar-fill {
    height: 100%;
    background: var(--dv-l);
    border-radius: 4px;
    width: 0%;
    transition: width .2s;
}
.upload-item.error .upload-item-bar-fill { background: #ef4444; }
.upload-item.success .upload-item-bar-fill { background: var(--dv-l); width: 100% !important; }

.upload-item-status {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}
.upload-item-pct {
    font-size: .72rem;
    font-family: var(--fm);
    color: var(--text-d);
    min-width: 34px;
    text-align: right;
}
.upload-item-action {
    width: 26px; height: 26px;
    border-radius: 6px;
    border: none;
    background: none;
    color: var(--text-d);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
}
.upload-item-action:hover { background: var(--bg-c2); color: var(--text-m); }
.upload-item.error .upload-item-action.retry { color: #fb923c; }
.upload-item.success .upload-item-status i { color: var(--dv-l); }
.upload-item.error .upload-item-status .status-icon { color: #ef4444; }

.upload-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: .76rem;
    color: var(--text-d);
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px solid var(--border);
}
.upload-summary a {
    color: var(--dv-l);
    font-weight: 600;
    cursor: pointer;
}

.upload-reject {
    font-size: .74rem;
    color: #ef4444;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>
@endpush

@section('content')

{{-- ══ ZONE UPLOAD ══ --}}
<div class="upload-zone" id="uploadZone" style="margin-bottom:12px;"
     onclick="document.getElementById('mediaFile').click()">
    <div class="upload-icon">
        <i class="fa-solid fa-cloud-arrow-up"></i>
    </div>
    <div style="font-size:.95rem;font-weight:600;margin-bottom:6px;color:var(--text);">
        Glisser des fichiers ici ou cliquer pour choisir
    </div>
    <div style="font-size:.78rem;color:var(--text-d);">
        <i class="fa-solid fa-image"></i> PNG, JPG, WebP (max 5 Mo) &nbsp;·&nbsp;
        <i class="fa-solid fa-video"></i> MP4 (max 10 Mo)
    </div>
    <input type="file" id="mediaFile" multiple accept="image/png,image/jpeg,image/webp,video/mp4"
           style="display:none" onchange="handleFileSelection(this.files)">
</div>

{{-- ══ FICHIERS REJETÉS (validation) ══ --}}
<div id="uploadRejects"></div>

{{-- ══ FILE D'ATTENTE D'UPLOAD ══ --}}
<div class="upload-queue" id="uploadQueue" style="margin-bottom:16px;"></div>
<div class="upload-summary" id="uploadSummary" style="display:none;margin-bottom:16px;">
    <span id="uploadSummaryText">—</span>
    <a onclick="clearFinishedUploads()">Effacer la liste</a>
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

/*
|--------------------------------------------------------------------------
| UPLOAD PROFESSIONNEL — file d'attente, validation, progression réelle
|--------------------------------------------------------------------------
*/

const UPLOAD_LIMITS = {
    image: 5 * 1024 * 1024,   // 5 Mo
    video: 10 * 1024 * 1024,  // 10 Mo
};
const ALLOWED_TYPES = ['image/png', 'image/jpeg', 'image/webp', 'video/mp4'];

let uploadQueueItems = []; // { id, file, status, progress, xhr }

function formatSize(bytes) {
    if (bytes < 1024) return bytes + ' o';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' Ko';
    return (bytes / (1024 * 1024)).toFixed(1) + ' Mo';
}

function handleFileSelection(fileList) {
    const files = Array.from(fileList);
    const rejects = [];

    files.forEach(file => {
        // ── Validation type ──
        if (!ALLOWED_TYPES.includes(file.type)) {
            rejects.push(`${file.name} : format non supporté`);
            return;
        }

        // ── Validation taille ──
        const isVideo = file.type.startsWith('video');
        const limit = isVideo ? UPLOAD_LIMITS.video : UPLOAD_LIMITS.image;
        if (file.size > limit) {
            rejects.push(`${file.name} : dépasse ${formatSize(limit)}`);
            return;
        }

        // ── Ajout à la file d'attente ──
        const item = {
            id: 'up_' + Date.now() + '_' + Math.random().toString(36).slice(2, 8),
            file,
            status: 'pending', // pending | uploading | success | error
            progress: 0,
            xhr: null,
        };
        uploadQueueItems.push(item);
        renderUploadQueue();
        startUpload(item);
    });

    if (rejects.length) showUploadRejects(rejects);

    // Reset l'input pour permettre de reprendre les mêmes fichiers si besoin
    document.getElementById('mediaFile').value = '';
}

function showUploadRejects(rejects) {
    const el = document.getElementById('uploadRejects');
    el.innerHTML = rejects.map(msg => `
        <div class="upload-reject">
            <i class="fa-solid fa-circle-exclamation"></i> ${msg}
        </div>
    `).join('');
    setTimeout(() => { el.innerHTML = ''; }, 6000);
}

function renderUploadQueue() {
    const container = document.getElementById('uploadQueue');

    container.innerHTML = uploadQueueItems.map(item => {
        const isImage = item.file.type.startsWith('image');
        const thumb = isImage
            ? `<img src="${URL.createObjectURL(item.file)}" alt="">`
            : `<i class="fa-solid fa-video"></i>`;

        let statusIcon = '';
        if (item.status === 'success') statusIcon = '<i class="fa-solid fa-circle-check"></i>';
        else if (item.status === 'error') statusIcon = '<i class="fa-solid fa-circle-exclamation status-icon"></i>';

        let actionBtn = '';
        if (item.status === 'uploading') {
            actionBtn = `<button class="upload-item-action" onclick="cancelUpload('${item.id}')" title="Annuler">
                <i class="fa-solid fa-xmark"></i>
            </button>`;
        } else if (item.status === 'error') {
            actionBtn = `<button class="upload-item-action retry" onclick="retryUpload('${item.id}')" title="Réessayer">
                <i class="fa-solid fa-rotate-right"></i>
            </button>`;
        } else if (item.status === 'success') {
            actionBtn = `<button class="upload-item-action" onclick="removeUploadItem('${item.id}')" title="Retirer">
                <i class="fa-solid fa-xmark"></i>
            </button>`;
        }

        return `
            <div class="upload-item ${item.status}" data-upload-id="${item.id}">
                <div class="upload-item-thumb">${thumb}</div>
                <div class="upload-item-info">
                    <div class="upload-item-name">${item.file.name}</div>
                    <div class="upload-item-meta">
                        <span>${formatSize(item.file.size)}</span>
                        ${item.status === 'error' ? '<span style="color:#ef4444;">Échec de l\'envoi</span>' : ''}
                    </div>
                    <div class="upload-item-bar">
                        <div class="upload-item-bar-fill" style="width:${item.progress}%;"></div>
                    </div>
                </div>
                <div class="upload-item-status">
                    <span class="upload-item-pct">${item.status === 'success' ? '' : item.progress + '%'}</span>
                    ${statusIcon}
                    ${actionBtn}
                </div>
            </div>
        `;
    }).join('');

    updateUploadSummary();
}

function updateUploadSummary() {
    const summary = document.getElementById('uploadSummary');
    const text    = document.getElementById('uploadSummaryText');

    if (!uploadQueueItems.length) {
        summary.style.display = 'none';
        return;
    }

    const total   = uploadQueueItems.length;
    const done    = uploadQueueItems.filter(i => i.status === 'success').length;
    const failed  = uploadQueueItems.filter(i => i.status === 'error').length;
    const pending = total - done - failed;

    summary.style.display = 'flex';
    text.textContent = pending > 0
        ? `${done}/${total} envoyés${failed ? ` · ${failed} échec(s)` : ''}`
        : `${done}/${total} envoyés${failed ? ` · ${failed} échec(s)` : ''} — terminé`;
}

function startUpload(item) {
    item.status = 'uploading';
    renderUploadQueue();

    const fd = new FormData();
    fd.append('fichier', item.file);
    fd.append('nom', item.file.name.replace(/\.[^/.]+$/, ''));
    fd.append('type', item.file.type.startsWith('video') ? 'video' : 'image');

    const xhr = new XMLHttpRequest();
    item.xhr = xhr;

    xhr.open('POST', '/api/v1/media');
    xhr.setRequestHeader('Accept', 'application/json');

    const token = typeof getAuthToken === 'function' ? getAuthToken() : null;
    if (token) xhr.setRequestHeader('Authorization', 'Bearer ' + token);

    xhr.upload.onprogress = (e) => {
        if (!e.lengthComputable) return;
        item.progress = Math.round((e.loaded / e.total) * 100);
        renderUploadQueue();
    };

    xhr.onload = () => {
        if (xhr.status >= 200 && xhr.status < 300) {
            item.status = 'success';
            item.progress = 100;
            loadMedia(currentPage);
        } else {
            item.status = 'error';
        }
        renderUploadQueue();
    };

    xhr.onerror = () => {
        item.status = 'error';
        renderUploadQueue();
    };

    xhr.onabort = () => {
        item.status = 'error';
        renderUploadQueue();
    };

    xhr.send(fd);
}

function cancelUpload(id) {
    const item = uploadQueueItems.find(i => i.id === id);
    if (item && item.xhr) item.xhr.abort();
}

function retryUpload(id) {
    const item = uploadQueueItems.find(i => i.id === id);
    if (item) { item.progress = 0; startUpload(item); }
}

function removeUploadItem(id) {
    uploadQueueItems = uploadQueueItems.filter(i => i.id !== id);
    renderUploadQueue();
}

function clearFinishedUploads() {
    uploadQueueItems = uploadQueueItems.filter(i => i.status === 'uploading' || i.status === 'pending');
    renderUploadQueue();
}

// Drag & drop
const _zone = document.getElementById('uploadZone');
_zone.addEventListener('dragover',  e => { e.preventDefault(); _zone.classList.add('drag'); });
_zone.addEventListener('dragleave', ()  => _zone.classList.remove('drag'));
_zone.addEventListener('drop',      e => {
    e.preventDefault();
    _zone.classList.remove('drag');
    handleFileSelection(e.dataTransfer.files);
});


/*
|--------------------------------------------------------------------------
| CHARGEMENT / AFFICHAGE MÉDIATHÈQUE (inchangé)
|--------------------------------------------------------------------------
*/

async function loadMedia(page=1) {
    currentPage = page;
    const search = document.getElementById('mediaSearch').value;
    const type   = document.getElementById('mediaType').value;
    let url = `/api/v1/media?page=${page}&per_page=24`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (type)   url += `&type=${encodeURIComponent(type)}`;

    try {
        const r = await apiFetch(url, {headers:{Accept:'application/json'}});
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
    await apiFetch(`/api/v1/media/${currentMedia.id}`, {
        method: 'PUT',
        body: JSON.stringify({ nom })
    });
    showToast('Média mis à jour ✓');
    loadMedia(currentPage);
    closeMediaModal();
}

async function deleteCurrentMedia() {
    if (!currentMedia || !confirm(`Supprimer "${currentMedia.nom||'ce média'}" ?`)) return;
    await apiFetch(`/api/v1/media/${currentMedia.id}`, {
        method: 'DELETE',
    });
    showToast('Média supprimé');
    closeMediaModal();
    loadMedia(currentPage);
}

function copyUrl(url) {
    const u = url || document.getElementById('media-url-display').value;
    navigator.clipboard.writeText(u).then(() => showToast('URL copiée !'));
}

function setView(v) {
    currentView = v;
    document.getElementById('mediaViewGrid').style.display = v === 'grid' ? 'block' : 'none';
    document.getElementById('mediaViewList').style.display = v === 'list' ? 'block' : 'none';
    document.getElementById('viewGrid').style.background   = v === 'grid' ? 'rgba(255,255,255,.12)' : '';
    document.getElementById('viewList').style.background   = v === 'list' ? 'rgba(255,255,255,.12)' : '';
}

let _ft;
function filterMedia() { clearTimeout(_ft); _ft = setTimeout(() => loadMedia(1), 400); }

// Init
loadMedia(1);
setView('grid');
</script>
@endpush