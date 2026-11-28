{{-- ══════════════════════════════════════════
   DASHBOARD — PUBLICITÉS
   Route: GET /dashboard/publicites → DashboardController@publicites
══════════════════════════════════════════ --}}
@extends('layouts.dashboard')
@section('title','Publicités')
@section('page_title','Publicités')
@section('breadcrumb')
<span class="sep">/</span> <span>Publicités</span>
@endsection

@section('topbar_actions')
<button onclick="openPubModal()" class="btn-d primary">
    <i class="fa-solid fa-plus"></i> Nouvelle publicité
</button>
@endsection

@section('content')

<div class="table-wrap">
    <div class="table-top">
        <div>
            <div class="sec-title">
                <i class="fa-solid fa-rectangle-ad" style="color:var(--dv-l);margin-right:6px;"></i>Publicités
            </div>
            <div class="sec-sub" id="pubCount">— publicités</div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Annonceur</th>
                <th>Emplacement</th>
                <th>Période</th>
                <th>Clics</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="pubsBody">
            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-d);">
                <i class="fa-solid fa-spinner fa-spin"></i> Chargement…
            </td></tr>
        </tbody>
    </table>
</div>

{{-- ══ MODAL PUBLICITÉ ══ --}}
<div class="modal-back" id="pubModal" onclick="if(event.target===this)closePubModal()">
    <div class="modal" style="max-width:560px;">
        <div class="modal-head">
            <div class="modal-title" id="pubModalTitle">
                <i class="fa-solid fa-rectangle-ad" style="color:var(--dy);margin-right:8px;"></i>Nouvelle publicité
            </div>
            <button class="modal-x" onclick="closePubModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="modal-body">
            <input type="hidden" id="pub-id">

            <div class="f-group">
                <label class="f-label">Annonceur *</label>
                <input type="text" id="pub-nom" class="f-control" placeholder="Nom de l'entreprise">
            </div>

            <div class="f-group">
                <label class="f-label">Emplacement *</label>
                <select id="pub-emplacement" class="f-control" onchange="updateSizeHint()">
                    @foreach(\App\Models\Publicite::EMPLACEMENTS as $key => $meta)
                        <option value="{{ $key }}" data-taille="{{ $meta['taille'] }}">{{ $meta['label'] }}</option>
                    @endforeach
                </select>
                <div class="f-hint" id="pubSizeHint"></div>
            </div>

            <div class="f-group">
                <label class="f-label">Image * (JPG, PNG, WebP ou GIF — 2 Mo max)</label>
                <input type="file" id="pub-image" class="f-control" accept="image/jpeg,image/png,image/webp,image/gif"
                       onchange="previewPubImage(this)">
                <img id="pubPreview" alt="" style="display:none;margin-top:8px;max-width:100%;max-height:140px;border-radius:8px;">
                <div class="f-hint" id="pubImageHint"></div>
            </div>

            <div class="f-group">
                <label class="f-label">Lien de destination (optionnel)</label>
                <input type="url" id="pub-lien" class="f-control" placeholder="https://www.entreprise.bj">
            </div>

            <div class="f-group">
                <label class="f-label">Texte alternatif (optionnel)</label>
                <input type="text" id="pub-alt" class="f-control" placeholder="Description de l'image">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="f-group">
                    <label class="f-label">Début de diffusion</label>
                    <input type="date" id="pub-debut" class="f-control">
                </div>
                <div class="f-group">
                    <label class="f-label">Fin de diffusion</label>
                    <input type="date" id="pub-fin" class="f-control">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="f-group">
                    <label class="f-label">Ordre</label>
                    <input type="number" id="pub-ordre" class="f-control" min="0" value="0">
                </div>
                <div class="f-group">
                    <label class="f-label">Statut</label>
                    <select id="pub-statut" class="f-control">
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="modal-foot">
            <button onclick="closePubModal()" class="btn-d outline">
                <i class="fa-solid fa-xmark"></i> Annuler
            </button>
            <button onclick="savePub()" class="btn-d primary" id="pubSaveBtn">
                <i class="fa-solid fa-floppy-disk"></i> Enregistrer
            </button>
        </div>
    </div>
</div>

@endsection

@php
    $pubLabels = collect(\App\Models\Publicite::EMPLACEMENTS)->map(fn ($m) => $m['label'])->all();
@endphp
@push('scripts')
<script>
const PUB_EMPLACEMENTS = @json($pubLabels);

async function loadPubs() {
    const r = await apiFetch('/api/v1/publicites', { headers: { Accept: 'application/json' } });
    const pubs = await r.json();
    const today = new Date().toISOString().slice(0, 10);

    document.getElementById('pubCount').textContent = `${pubs.length} publicité${pubs.length > 1 ? 's' : ''}`;

    document.getElementById('pubsBody').innerHTML = pubs.length ? pubs.map(p => {
        const expiree = p.date_fin && p.date_fin < today;
        const aVenir  = p.date_debut && p.date_debut > today;
        let statut = p.statut === 'actif' ? 'Actif' : 'Inactif';
        let color  = p.statut === 'actif' ? '#4CAF50' : '#999';
        if (p.statut === 'actif' && expiree) { statut = 'Expirée';  color = '#C62828'; }
        if (p.statut === 'actif' && aVenir)  { statut = 'Planifiée'; color = '#F9A825'; }

        return `
        <tr>
            <td>
                <div class="td-main">
                    <img class="td-thumb" src="${esc(p.image_url)}" alt="">
                    <div>
                        <div class="td-name">${esc(p.nom)}</div>
                        <div class="td-sub">${p.lien ? esc(p.lien) : 'Sans lien'}</div>
                    </div>
                </div>
            </td>
            <td style="font-size:.78rem;">${esc(PUB_EMPLACEMENTS[p.emplacement] || p.emplacement)}</td>
            <td style="font-size:.76rem;font-family:var(--fm);">
                ${p.date_debut ? esc(p.date_debut) : '—'} → ${p.date_fin ? esc(p.date_fin) : '—'}
            </td>
            <td style="font-family:var(--fm);font-size:.8rem;">${p.nb_clics || 0}</td>
            <td><span style="font-size:.72rem;font-weight:700;color:${color};">● ${statut}</span></td>
            <td>
                <div class="td-actions">
                    <button class="ab ab-edit" title="Modifier"
                            onclick="editPub(${esc(JSON.stringify(p))})">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="ab ab-del" title="Supprimer"
                            onclick="confirmDelete('/api/v1/publicites/${p.id}',${esc(JSON.stringify(p.nom))})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('') : `<tr><td colspan="6">
        <div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-rectangle-ad"></i></div>
            <div class="empty-msg">Aucune publicité</div>
        </div></td></tr>`;
}

function updateSizeHint() {
    const opt = document.getElementById('pub-emplacement').selectedOptions[0];
    document.getElementById('pubSizeHint').textContent = `Taille recommandée : ${opt.dataset.taille}`;
}

function previewPubImage(input) {
    const img = document.getElementById('pubPreview');
    if (input.files && input.files[0]) {
        img.src = URL.createObjectURL(input.files[0]);
        img.style.display = 'block';
    }
}

function openPubModal() {
    resetPubForm();
    updateSizeHint();
    document.getElementById('pubModal').classList.add('open');
}
function closePubModal() {
    document.getElementById('pubModal').classList.remove('open');
}

function resetPubForm() {
    ['pub-id','pub-nom','pub-lien','pub-alt','pub-debut','pub-fin'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('pub-ordre').value = 0;
    document.getElementById('pub-statut').value = 'actif';
    document.getElementById('pub-emplacement').selectedIndex = 0;
    document.getElementById('pub-image').value = '';
    document.getElementById('pubPreview').style.display = 'none';
    document.getElementById('pubImageHint').textContent = '';
    document.getElementById('pubModalTitle').innerHTML =
        '<i class="fa-solid fa-rectangle-ad" style="color:var(--dy);margin-right:8px;"></i>Nouvelle publicité';
}

function editPub(p) {
    resetPubForm();
    document.getElementById('pub-id').value          = p.id;
    document.getElementById('pub-nom').value         = p.nom;
    document.getElementById('pub-emplacement').value = p.emplacement;
    document.getElementById('pub-lien').value        = p.lien || '';
    document.getElementById('pub-alt').value         = p.texte_alt || '';
    document.getElementById('pub-debut').value       = p.date_debut || '';
    document.getElementById('pub-fin').value         = p.date_fin || '';
    document.getElementById('pub-ordre').value       = p.ordre || 0;
    document.getElementById('pub-statut').value      = p.statut;

    const img = document.getElementById('pubPreview');
    img.src = p.image_url; img.style.display = 'block';
    document.getElementById('pubImageHint').textContent = 'Laisse vide pour garder l’image actuelle.';
    document.getElementById('pubModalTitle').innerHTML =
        `<i class="fa-solid fa-pen-to-square" style="color:var(--dy);margin-right:8px;"></i>Modifier : ${esc(p.nom)}`;

    updateSizeHint();
    document.getElementById('pubModal').classList.add('open');
}

async function savePub() {
    const id  = document.getElementById('pub-id').value;
    const nom = document.getElementById('pub-nom').value.trim();
    const file = document.getElementById('pub-image').files[0];

    if (!nom) { showToast('Le nom de l’annonceur est requis', 'error'); return; }
    if (!id && !file) { showToast('Choisis une image', 'error'); return; }

    const fd = new FormData();
    fd.append('nom', nom);
    fd.append('emplacement', document.getElementById('pub-emplacement').value);
    fd.append('lien', document.getElementById('pub-lien').value.trim());
    fd.append('texte_alt', document.getElementById('pub-alt').value.trim());
    fd.append('date_debut', document.getElementById('pub-debut').value);
    fd.append('date_fin', document.getElementById('pub-fin').value);
    fd.append('ordre', document.getElementById('pub-ordre').value || 0);
    fd.append('statut', document.getElementById('pub-statut').value);
    if (file) fd.append('image', file);

    const btn = document.getElementById('pubSaveBtn');
    btn.disabled = true;

    try {
        const r = await apiFetch(id ? `/api/v1/publicites/${id}` : '/api/v1/publicites', {
            method: 'POST',   // la mise à jour est aussi en POST (multipart)
            body: fd,
        });
        const d = await r.json().catch(() => ({}));

        if (r.ok) {
            showToast(id ? 'Publicité modifiée ✓' : 'Publicité créée ✓');
            closePubModal();
            loadPubs();
        } else {
            const first = d.errors ? Object.values(d.errors)[0][0] : (d.message || 'Erreur');
            showToast(first, 'error');
        }
    } catch (e) {
        showToast('Erreur réseau', 'error');
    } finally {
        btn.disabled = false;
    }
}

loadPubs();
</script>
@endpush
