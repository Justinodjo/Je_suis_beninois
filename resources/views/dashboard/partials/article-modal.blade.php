{{-- ══════════════════════════════════════════
   PARTIAL — MODAL ARTICLE (CRUD)
   Inclus dans: dashboard/index.blade.php, articles.blade.php
══════════════════════════════════════════ --}}
<div class="modal-back" id="articleModal" onclick="if(event.target===this)closeModal('articleModal')">
    <div class="modal" style="max-width:780px;">

        <div class="modal-head">
            <div class="modal-title" id="modalTitle">
                <i class="fa-solid fa-file-pen" style="color:var(--dy);margin-right:8px;"></i>Nouvel article
            </div>
            <button class="modal-x" onclick="closeModal('articleModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <div id="modalError" class="alert alert-error" style="display:none;margin-bottom:16px;"></div>

            {{-- Tabs --}}
            <div style="display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:20px;">
                <button class="modal-tab active" onclick="switchTab('info')"    id="tab-info">
                    <i class="fa-solid fa-circle-info"></i> Infos
                </button>
                <button class="modal-tab"        onclick="switchTab('contenu')" id="tab-contenu">
                    <i class="fa-solid fa-align-left"></i> Contenu
                </button>
                <button class="modal-tab"        onclick="switchTab('media')"   id="tab-media">
                    <i class="fa-solid fa-photo-film"></i> Médias
                </button>
                <button class="modal-tab"        onclick="switchTab('seo')"     id="tab-seo">
                    <i class="fa-solid fa-magnifying-glass"></i> SEO
                </button>
            </div>

            <style>
            .modal-tab { padding:8px 18px; background:none; border:none; color:var(--text-d); font-size:.82rem; font-weight:600; cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px; font-family:var(--fb); transition:all .18s; display:inline-flex; align-items:center; gap:6px; }
            .modal-tab.active { color:var(--dy); border-bottom-color:var(--dy); }
            .modal-tab:hover:not(.active) { color:var(--text-m); }
            .modal-tab-panel { display:none; }
            .modal-tab-panel.show { display:block; }
            </style>

            {{-- ── TAB : INFOS ── --}}
            <div class="modal-tab-panel show" id="panel-info">
                <input type="hidden" id="art-id">

                <div class="f-row">
                    <div class="f-group" style="grid-column:1/-1">
                        <label class="f-label">Titre *</label>
                        <input type="text" id="art-titre" class="f-control"
                               placeholder="Ex: Les palais royaux d'Abomey…"
                               oninput="updateSlugPreview(this.value)">
                        <div class="f-hint" id="slug-preview" style="font-family:var(--fm);color:var(--text-d);"></div>
                    </div>
                </div>

                <div class="f-row">
                    <div class="f-group">
                        <label class="f-label">Type d'article *</label>
                        <select id="art-type" class="f-control">
                            <option value="article">Article général</option>
                            <option value="tradition">Tradition</option>
                            <option value="patrimoine">Patrimoine</option>
                            <option value="interview">Interview</option>
                            <option value="featured">Featured (À la une)</option>
                            <option value="galerie">Galerie</option>
                        </select>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Statut *</label>
                        <select id="art-statut" class="f-control">
                            <option value="brouillon">Brouillon</option>
                            <option value="publié">Publié</option>
                            <option value="archivé">Archivé</option>
                        </select>
                    </div>
                </div>

                <div class="f-group">
                    <label class="f-label">Extrait</label>
                    <textarea id="art-extrait" class="f-control" rows="2"
                              placeholder="Court résumé de l'article (300 caractères max)…"
                              maxlength="300"
                              oninput="document.getElementById('extraitCount').textContent=this.value.length+'/300'"></textarea>
                    <div class="char-count"><span id="extraitCount">0/300</span></div>
                </div>

                <div class="f-row">
                    <div class="f-group">
                        <label class="f-label">
                            <i class="fa-solid fa-tag" style="color:var(--dv-l);margin-right:4px;"></i>Catégories
                        </label>
                        <div id="catCheckboxes" style="display:flex;flex-wrap:wrap;gap:8px;padding:10px;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:8px;min-height:44px;">
                            <div style="color:var(--text-d);font-size:.78rem;">
                                <i class="fa-solid fa-spinner fa-spin"></i> Chargement…
                            </div>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">
                            <i class="fa-solid fa-hashtag" style="color:var(--dv-l);margin-right:4px;"></i>Tags
                        </label>
                        <div id="tagCheckboxes" style="display:flex;flex-wrap:wrap;gap:8px;padding:10px;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:8px;min-height:44px;max-height:100px;overflow-y:auto;">
                            <div style="color:var(--text-d);font-size:.78rem;">
                                <i class="fa-solid fa-spinner fa-spin"></i> Chargement…
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── TAB : CONTENU ── --}}
            <div class="modal-tab-panel" id="panel-contenu">
                <div class="f-group">
                    <label class="f-label">Contenu complet *</label>
                    <textarea id="art-contenu" class="f-control" rows="14"
                              placeholder="Rédigez votre article ici… Vous pouvez utiliser du HTML basique."></textarea>
                    <div class="f-hint">
                        <i class="fa-solid fa-code" style="margin-right:4px;"></i>
                        HTML basique autorisé : &lt;p&gt;, &lt;h2&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;blockquote&gt;
                    </div>
                </div>
            </div>

            {{-- ── TAB : MÉDIAS ── --}}
            <div class="modal-tab-panel" id="panel-media">

                {{-- Zone upload --}}
                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                    <div class="upload-icon">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <div style="font-size:.88rem;font-weight:600;margin-bottom:4px;">
                        Cliquer ou glisser une image ici
                    </div>
                    <div style="font-size:.75rem;color:var(--text-d);">
                        <i class="fa-solid fa-image"></i> PNG, JPG, WebP &nbsp;·&nbsp; Max 5 MB
                    </div>
                </div>
                <input type="file" id="fileInput" accept="image/*" style="display:none"
                       multiple onchange="handleUpload(this.files)">

                <div id="uploadProgress" style="display:none;margin-top:12px;">
                    <div class="f-label">
                        <i class="fa-solid fa-spinner fa-spin"></i> Téléchargement en cours…
                    </div>
                    <div class="prog-bar" style="margin-top:6px;">
                        <div class="prog-fill" id="uploadBar" style="width:0%;background:var(--dv-l);"></div>
                    </div>
                </div>

                {{-- Médias existants --}}
                <div style="margin-top:20px;">
                    <div class="f-label" style="margin-bottom:10px;">
                        <i class="fa-solid fa-images" style="margin-right:4px;color:var(--dv-l);"></i>
                        Sélectionner des médias existants
                    </div>
                    <div class="media-grid" id="mediaPickerGrid">
                        <div style="color:var(--text-d);font-size:.78rem;grid-column:1/-1;text-align:center;padding:20px;">
                            <i class="fa-solid fa-spinner fa-spin"></i> Chargement…
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── TAB : SEO ── --}}
            <div class="modal-tab-panel" id="panel-seo">
                <div class="f-group">
                    <label class="f-label">
                        <i class="fa-solid fa-heading" style="margin-right:4px;color:var(--dv-l);"></i>
                        Meta titre (SEO)
                    </label>
                    <input type="text" id="art-meta-titre" class="f-control"
                           placeholder="Titre pour les moteurs de recherche (60 car. max)">
                </div>
                <div class="f-group">
                    <label class="f-label">
                        <i class="fa-solid fa-align-left" style="margin-right:4px;color:var(--dv-l);"></i>
                        Meta description (SEO)
                    </label>
                    <textarea id="art-meta-desc" class="f-control" rows="3"
                              placeholder="Description pour les moteurs de recherche (160 car. max)…"></textarea>
                </div>
                <div class="f-group">
                    <label class="f-label">
                        <i class="fa-brands fa-google" style="margin-right:4px;color:#4285f4;"></i>
                        Aperçu Google
                    </label>
                    <div style="background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:8px;padding:16px;">
                        <div style="color:#93c5fd;font-size:.88rem;font-weight:500;" id="seo-preview-title">Titre de l'article…</div>
                        <div style="color:#86efac;font-size:.72rem;margin:2px 0;font-family:var(--fm);">jesuisbeninois.bj/culture/article/slug…</div>
                        <div style="color:var(--text-m);font-size:.78rem;" id="seo-preview-desc">Description de l'article…</div>
                    </div>
                </div>
                <div class="f-hint" style="margin-top:-6px;">
                    <i class="fa-solid fa-circle-info" style="margin-right:4px;"></i>
                    Ces champs sont indicatifs : ils ne sont pas encore enregistrés en base (colonnes non présentes sur la table <code>articles</code>).
                </div>
            </div>

        </div>{{-- /modal-body --}}

        <div class="modal-foot">
            <button onclick="closeModal('articleModal')" class="btn-d outline">
                <i class="fa-solid fa-xmark"></i> Annuler
            </button>
            <button onclick="saveArticle('brouillon')" class="btn-d jaune">
                <i class="fa-solid fa-floppy-disk"></i> Enregistrer brouillon
            </button>
            <button onclick="saveArticle('publié')" class="btn-d primary">
                <i class="fa-solid fa-circle-check"></i> Publier
            </button>
        </div>
    </div>
</div>

<script>
function switchTab(name) {
    document.querySelectorAll('.modal-tab-panel').forEach(p => p.classList.remove('show'));
    document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('panel-'+name).classList.add('show');
    document.getElementById('tab-'+name).classList.add('active');
    if (name === 'media') loadMediaPicker();
}

function updateSlugPreview(titre) {
    const slug = titre.toLowerCase().normalize('NFD')
        .replace(/[\u0300-\u036f]/g,'')
        .replace(/[^a-z0-9]+/g,'-')
        .replace(/^-|-$/g,'');
    document.getElementById('slug-preview').textContent = slug ? '/culture/article/'+slug+'-xxxxx' : '';
    document.getElementById('seo-preview-title').textContent = titre || 'Titre de l\'article…';
}

// ✅ JWT uniquement — apiFetch
async function loadModalFilters() {
    const [cats, tags] = await Promise.all([
        apiFetch('/api/v1/categories').then(r=>r.json()).catch(()=>({data:[]})),
        apiFetch('/api/v1/tags').then(r=>r.json()).catch(()=>({data:[]})),
    ]);

    const catEl = document.getElementById('catCheckboxes');
    catEl.innerHTML = (cats.data||cats||[]).map(c=>`
        <label style="display:flex;align-items:center;gap:5px;cursor:pointer;font-size:.75rem;color:var(--text-m);padding:3px 8px;border-radius:5px;border:1px solid var(--border);transition:all .15s;"
               onmouseover="this.style.borderColor='var(--dv-l)'"
               onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--border)'">
            <input type="checkbox" name="art-cats" value="${c.id}" style="accent-color:var(--dv-l);">
            ${c.icone ? c.icone+' ' : ''}${c.nom}
        </label>
    `).join('') || '<div style="color:var(--text-d);font-size:.78rem;">Aucune catégorie</div>';

    const tagEl = document.getElementById('tagCheckboxes');
    tagEl.innerHTML = (tags.data||tags||[]).map(t=>`
        <label style="display:flex;align-items:center;gap:4px;cursor:pointer;font-size:.72rem;color:var(--text-m);padding:2px 8px;border-radius:12px;border:1px solid var(--border);transition:all .15s;">
            <input type="checkbox" name="art-tags" value="${t.id}" style="accent-color:var(--dv-l);">
            #${t.nom}
        </label>
    `).join('') || '<div style="color:var(--text-d);font-size:.78rem;">Aucun tag</div>';
}

// ✅ JWT uniquement — apiFetch
async function loadMediaPicker() {
    try {
        const r = await apiFetch('/api/v1/media?per_page=30');
        const d = await r.json();
        const grid = document.getElementById('mediaPickerGrid');
        const medias = d.data||[];
        grid.innerHTML = medias.length ? medias.map(m=>`
            <div class="m-item" onclick="toggleMedia(this,${m.id})" data-media-id="${m.id}">
                ${m.type === 'video'
                    ? `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--bg-c2);">
                           <i class="fa-solid fa-video" style="font-size:1.5rem;color:var(--text-d);"></i>
                       </div>`
                    : `<img src="${m.url}" alt="${m.nom||'Média'}"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                       <div style="display:none;width:100%;height:100%;align-items:center;justify-content:center;background:var(--bg-c2);">
                           <i class="fa-solid fa-image-slash" style="color:var(--text-d);"></i>
                       </div>`
                }
                <div class="m-check"><i class="fa-solid fa-check"></i></div>
            </div>
        `).join('')
        : `<div style="color:var(--text-d);font-size:.78rem;grid-column:1/-1;text-align:center;padding:20px;">
               <i class="fa-solid fa-photo-film" style="font-size:1.5rem;margin-bottom:8px;display:block;"></i>
               Aucun média. Uploadez des images d'abord.
           </div>`;
    } catch {}
}

function toggleMedia(el, id) {
    el.classList.toggle('selected');
}

// ✅ JWT uniquement — apiFetch, plus de X-CSRF-TOKEN / Laravel.csrfToken
async function handleUpload(files) {
    if (!files.length) return;
    const prog = document.getElementById('uploadProgress');
    const bar  = document.getElementById('uploadBar');
    prog.style.display = 'block';
    bar.style.width = '20%';

    for (const file of files) {
        const fd = new FormData();
        fd.append('fichier', file);
        fd.append('nom', file.name);
        fd.append('type', file.type.startsWith('video') ? 'video' : 'image');

        try {
            const r = await apiFetch('/api/v1/media', {
                method: 'POST',
                body: fd
            });
            const d = await r.json();
            if (r.ok) showToast(file.name + ' uploadé ✓');
            else showToast((d.message||'Erreur upload'), 'error');
        } catch {
            showToast('Erreur réseau', 'error');
        }
    }

    bar.style.width = '100%';
    setTimeout(() => {
        prog.style.display = 'none';
        bar.style.width = '0';
        loadMediaPicker();
    }, 800);
}

// Drag & drop
const _zone = document.getElementById('uploadZone');
if (_zone) {
    _zone.addEventListener('dragover',  e => { e.preventDefault(); _zone.classList.add('drag'); });
    _zone.addEventListener('dragleave', ()  => _zone.classList.remove('drag'));
    _zone.addEventListener('drop',      e => { e.preventDefault(); _zone.classList.remove('drag'); handleUpload(e.dataTransfer.files); });
}

// ✅ JWT uniquement — apiFetch, plus de X-CSRF-TOKEN / Laravel.csrfToken
// ⚠️ meta_titre / meta_description ne sont PAS envoyés : colonnes absentes de la table articles
async function saveArticle(statutOverride) {
    const errEl   = document.getElementById('modalError');
    errEl.style.display = 'none';

    const id      = document.getElementById('art-id').value;
    const titre   = document.getElementById('art-titre').value.trim();
    const contenu = document.getElementById('art-contenu').value.trim();

    if (!titre)   { errEl.style.display='block'; errEl.innerHTML='<i class="fa-solid fa-circle-xmark"></i> Le titre est obligatoire.'; return; }
    if (!contenu) { errEl.style.display='block'; errEl.innerHTML='<i class="fa-solid fa-circle-xmark"></i> Le contenu est obligatoire.'; return; }

    const cats     = [...document.querySelectorAll('input[name="art-cats"]:checked')].map(c=>+c.value);
    const tags     = [...document.querySelectorAll('input[name="art-tags"]:checked')].map(t=>+t.value);
    const mediaIds = [...document.querySelectorAll('.m-item.selected')].map(el=>+el.dataset.mediaId);

    const payload = {
        titre,
        contenu,
        extrait:    document.getElementById('art-extrait').value.trim() || null,
        type:       document.getElementById('art-type').value,
        statut:     statutOverride || document.getElementById('art-statut').value,
        categories: cats,
        tags,
        medias:     mediaIds,
    };

    const method = id ? 'PUT' : 'POST';
    const url    = id ? `/api/v1/articles/${id}` : '/api/v1/articles';

    try {
        const r = await apiFetch(url, {
            method,
            body: JSON.stringify(payload)
        });
        const d = await r.json();

        if (r.ok) {
            showToast(id ? 'Article modifié ✓' : 'Article créé ✓');
            closeModal('articleModal');
            resetForm();
            if (typeof loadArticles === 'function') loadArticles(currentPage||1);
            if (typeof loadStats    === 'function') loadStats();
        } else {
            const errors = d.errors ? Object.values(d.errors).flat().join(' | ') : d.message;
            errEl.style.display = 'block';
            errEl.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> ' + errors;
        }
    } catch(e) {
        errEl.style.display = 'block';
        errEl.innerHTML = '<i class="fa-solid fa-wifi"></i> Erreur réseau.';
    }
}

function resetForm() {
    ['art-id','art-titre','art-extrait','art-contenu','art-meta-titre','art-meta-desc'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.getElementById('art-type').value   = 'article';
    document.getElementById('art-statut').value = 'brouillon';
    document.querySelectorAll('input[name="art-cats"]:checked,input[name="art-tags"]:checked').forEach(c => c.checked = false);
    document.querySelectorAll('.m-item.selected').forEach(el => el.classList.remove('selected'));
    document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-file-pen" style="color:var(--dy);margin-right:8px;"></i>Nouvel article';
    document.getElementById('modalError').style.display = 'none';
    document.getElementById('slug-preview').textContent = '';
    switchTab('info');
}

loadModalFilters();
</script>