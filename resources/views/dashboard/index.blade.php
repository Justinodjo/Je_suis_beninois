{{-- ══════════════════════════════════════════
   DASHBOARD — VUE D'ENSEMBLE (index)
   Route: GET /dashboard → DashboardController@index
══════════════════════════════════════════ --}}
@extends('layouts.dashboard')
@section('title','Vue d\'ensemble')
@section('page_title','Vue d\'ensemble')
@section('breadcrumb')@endsection

@section('topbar_actions')
<a href="#" onclick="openModal('articleModal')" class="btn-d primary">
    <i class="fa-solid fa-plus"></i> Nouvel article
</a>
@endsection

@section('content')

{{-- ══ BIENVENUE ══ --}}
<div style="margin-bottom:24px;padding:20px 24px;background:linear-gradient(135deg,rgba(27,94,32,.25),rgba(27,94,32,.08));border:1px solid rgba(27,94,32,.3);border-radius:var(--r);display:flex;align-items:center;gap:16px;">
    <div style="font-size:2rem;">🇧🇯</div>
    <div>
        <div style="font-family:var(--fh);font-size:1.1rem;font-weight:700;color:#fff;">
            Bienvenue, {{ auth()->user()?->name ?? 'Admin' }} !
        </div>
        <div style="font-size:.8rem;color:var(--text-m);margin-top:2px;">
            {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }} · Tableau de bord Je Suis Béninois
        </div>
    </div>
    <div style="margin-left:auto;text-align:right;">
        <div style="font-size:.72rem;color:var(--text-d);">Dernière connexion</div>
        <div style="font-size:.8rem;color:var(--text-m);font-family:var(--fm);">{{ now()->format('H:i') }}</div>
    </div>
</div>

{{-- ══ STAT CARDS ══ --}}
<div class="stats-grid">
    <div class="stat-card s-vert">
        <div class="stat-icon"><i class="fa-solid fa-newspaper"></i></div>
        <div class="stat-label">Articles publiés</div>
        <div class="stat-value" id="statArticles">—</div>
        <div class="stat-sub"><span class="up" id="statDraft">0 brouillons</span></div>
    </div>
    <div class="stat-card s-jaune">
        <div class="stat-icon"><i class="fa-regular fa-eye"></i></div>
        <div class="stat-label">Vues totales</div>
        <div class="stat-value" id="statVues">—</div>
        <div class="stat-sub">Ce mois <span class="up" id="statVuesMois">—</span></div>
    </div>
    <div class="stat-card s-rouge">
        <div class="stat-icon"><i class="fa-solid fa-heart"></i></div>
        <div class="stat-label">Likes totaux</div>
        <div class="stat-value" id="statLikes">—</div>
        <div class="stat-sub"><span class="up" id="statComments">0 commentaires</span></div>
    </div>
    <div class="stat-card s-bleu">
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        <div class="stat-label">Contributeurs</div>
        <div class="stat-value" id="statUsers">—</div>
        <div class="stat-sub">Actifs ce mois</div>
    </div>
</div>

{{-- ══ GRILLE PRINCIPALE ══ --}}
<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;margin-bottom:20px;">

    {{-- Derniers articles --}}
    <div class="table-wrap">
        <div class="table-top">
            <div>
                <div class="sec-title"><i class="fa-solid fa-newspaper" style="color:var(--dv-l);margin-right:6px;"></i>Derniers articles</div>
                <div class="sec-sub">Articles récemment créés ou modifiés</div>
            </div>
            <div class="sec-actions">
                <a href="{{ route('dashboard.articles') }}" class="btn-d outline sm">Voir tout →</a>
                <button onclick="openModal('articleModal')" class="btn-d primary sm">
                    <i class="fa-solid fa-plus"></i> Nouveau
                </button>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Vues</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="recentArticlesBody">
                <tr><td colspan="5" style="text-align:center;padding:32px;">
                    <i class="fa-solid fa-spinner fa-spin" style="color:var(--text-d);font-size:1.2rem;"></i>
                </td></tr>
            </tbody>
        </table>
    </div>

    {{-- Sidebar stats --}}
    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- Répartition par type --}}
        <div class="chart-wrap">
            <div class="chart-title">
                <i class="fa-solid fa-chart-bar" style="color:var(--dy);margin-right:6px;"></i>Par type d'article
            </div>
            <div id="typeChart" style="display:flex;flex-direction:column;gap:8px;"></div>
        </div>

        {{-- Commentaires récents --}}
        <div class="table-wrap" style="overflow:visible;">
            <div class="table-top">
                <div class="sec-title">
                    <i class="fa-solid fa-comments" style="color:var(--dv-l);margin-right:6px;"></i>Commentaires
                </div>
                <span class="bx bx-rouge" id="pendingCommentsBadge" style="display:none">0 en attente</span>
            </div>
            <div id="recentCommentsBody" style="padding:4px 0;">
                <div style="padding:20px;color:var(--text-d);font-size:.8rem;text-align:center;">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ GRAPHIQUE ACTIVITÉ ══ --}}
<div class="chart-wrap">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div class="chart-title">
            <i class="fa-solid fa-chart-line" style="color:var(--dv-l);margin-right:6px;"></i>Activité — 7 derniers jours
        </div>
        <div style="display:flex;gap:12px;font-size:.72rem;color:var(--text-d);">
            <span><span style="display:inline-block;width:10px;height:10px;background:var(--dv-l);border-radius:2px;margin-right:4px;"></span>Articles</span>
            <span><span style="display:inline-block;width:10px;height:10px;background:var(--dy);border-radius:2px;margin-right:4px;"></span>Vues (/10)</span>
        </div>
    </div>
    <div class="chart-canvas" id="activityChart"></div>
</div>

{{-- ══ MODAL ARTICLE ══ --}}
@include('dashboard.partials.article-modal')

@endsection

@push('scripts')
<script>
async function loadStats() {
    try {
        const [arts, cats, tags, media] = await Promise.all([
            fetch('/api/v1/articles?per_page=1').then(r=>r.json()),
            fetch('/api/v1/categories').then(r=>r.json()),
            fetch('/api/v1/tags').then(r=>r.json()),
            fetch('/api/v1/media?per_page=1').then(r=>r.json()),
        ]);

        document.getElementById('statArticles').textContent = arts.total || 0;

        const drafts = await fetch('/api/v1/articles?statut=brouillon&per_page=1').then(r=>r.json());
        document.getElementById('statDraft').textContent = (drafts.total||0) + ' brouillons';

        const allArts = await fetch('/api/v1/articles?per_page=100').then(r=>r.json());
        const vues = (allArts.data||[]).reduce((s,a)=>s+(a.nb_vues||0),0);
        document.getElementById('statVues').textContent = vues > 999 ? (vues/1000).toFixed(1)+'k' : vues;
        document.getElementById('statLikes').textContent = (allArts.data||[]).reduce((s,a)=>s+(a.nb_likes||0),0);
        document.getElementById('statUsers').textContent = '16';

        const comments = await fetch('/api/v1/comments?per_page=1').then(r=>r.json()).catch(()=>({total:0}));
        document.getElementById('statComments').textContent = (comments.total||0) + ' commentaires';

        loadTypeChart(allArts.data||[]);
        loadRecentArticles((allArts.data||[]).slice(0,6));

    } catch(e) { console.error('Erreur stats:', e); }
}

function loadTypeChart(articles) {
    const types = {};
    articles.forEach(a => { types[a.type] = (types[a.type]||0)+1; });
    const total = articles.length || 1;
    const colors = {article:'#3b82f6',tradition:'#fde68a',patrimoine:'#a5b4fc',interview:'#5eead4',featured:'var(--dy)',galerie:'#f9a8d4'};
    // ✅ FA icons pour les types
    const icons = {
        article:    '<i class="fa-solid fa-newspaper"></i>',
        tradition:  '<i class="fa-solid fa-drum"></i>',
        patrimoine: '<i class="fa-solid fa-landmark"></i>',
        interview:  '<i class="fa-solid fa-microphone"></i>',
        featured:   '<i class="fa-solid fa-star"></i>',
        galerie:    '<i class="fa-solid fa-images"></i>',
    };
    const labels = {article:'Article',tradition:'Tradition',patrimoine:'Patrimoine',interview:'Interview',featured:'Featured',galerie:'Galerie'};

    document.getElementById('typeChart').innerHTML = Object.entries(types).map(([type,count]) => `
        <div>
            <div style="display:flex;justify-content:space-between;font-size:.72rem;margin-bottom:4px;">
                <span style="color:var(--text-m);display:flex;align-items:center;gap:5px;">${icons[type]||'<i class="fa-solid fa-file"></i>'} ${labels[type]||type}</span>
                <span style="color:var(--text-d);font-family:var(--fm);">${count}</span>
            </div>
            <div class="prog-bar">
                <div class="prog-fill" style="width:${(count/total*100).toFixed(0)}%;background:${colors[type]||'var(--dv-l)'}"></div>
            </div>
        </div>
    `).join('');
}

function loadRecentArticles(articles) {
    const tbody = document.getElementById('recentArticlesBody');
    if (!articles.length) {
        tbody.innerHTML = `<tr><td colspan="5"><div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
            <div class="empty-msg">Aucun article</div>
        </div></td></tr>`;
        return;
    }

    // ✅ FA icons pour les types — remplace les classes bx
    const typeIcons = {
        article:    '<i class="fa-solid fa-newspaper"></i>',
        tradition:  '<i class="fa-solid fa-drum"></i>',
        patrimoine: '<i class="fa-solid fa-landmark"></i>',
        interview:  '<i class="fa-solid fa-microphone"></i>',
        featured:   '<i class="fa-solid fa-star"></i>',
        galerie:    '<i class="fa-solid fa-images"></i>',
    };
    // ✅ FA icons pour les statuts
    const statutIcons = {
        'publié':   '<i class="fa-solid fa-circle-check"></i>',
        'brouillon':'<i class="fa-solid fa-file-pen"></i>',
        'archivé':  '<i class="fa-solid fa-box-archive"></i>',
    };

    tbody.innerHTML = articles.map(a => `
        <tr>
            <td>
                <div class="td-main">
                    <div class="td-name">${(a.titre||'').substring(0,45)}${(a.titre||'').length>45?'…':''}</div>
                </div>
            </td>
            <td>
                <span class="bx bx-${a.type||'article'}" style="display:inline-flex;align-items:center;gap:4px;">
                    ${typeIcons[a.type]||typeIcons.article} ${a.type||'article'}
                </span>
            </td>
            <td>
                <span class="bx bx-${a.statut||'brouillon'}" style="display:inline-flex;align-items:center;gap:4px;">
                    ${statutIcons[a.statut]||statutIcons.brouillon} ${a.statut||'brouillon'}
                </span>
            </td>
            <td style="font-family:var(--fm);font-size:.78rem;">${(a.nb_vues||0).toLocaleString()}</td>
            <td>
                <div class="td-actions">
                    <button class="ab ab-view" onclick="window.open('/culture/article/${a.slug}','_blank')" title="Voir">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <button class="ab ab-edit" onclick="editArticle(${a.id})" title="Modifier">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="ab ab-del" onclick="confirmDelete('/api/v1/articles/${a.id}','${(a.titre||'').replace(/'/g,"\\'")}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function loadActivityChart() {
    const days = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];
    const viewData = [12,8,22,15,30,18,24];
    const artData  = [1,0,2,1,3,0,2];
    const maxV = Math.max(...viewData);

    document.getElementById('activityChart').innerHTML = days.map((d,i) => `
        <div class="bar-col">
            <div style="display:flex;flex-direction:column;gap:3px;align-items:center;flex:1;justify-content:flex-end;">
                <div class="bar" style="height:${(viewData[i]/maxV*120).toFixed(0)}px;background:rgba(255,215,0,.4);width:100%;"></div>
                <div class="bar" style="height:${artData[i]*20}px;background:var(--dv-l);width:60%;"></div>
            </div>
            <div class="bar-label">${d}</div>
        </div>
    `).join('');
}

async function loadComments() {
    try {
        const r = await fetch('/api/v1/comments?per_page=4&statut=en_attente');
        const d = await r.json();
        const pending = d.total || 0;
        if (pending > 0) {
            const b = document.getElementById('pendingCommentsBadge');
            b.style.display = 'flex'; b.textContent = pending + ' en attente';
        }
        const items = d.data||[];
        document.getElementById('recentCommentsBody').innerHTML = items.length
            ? items.map(c=>`
                <div style="padding:10px 18px;border-bottom:1px solid var(--border);font-size:.78rem;">
                    <div style="font-weight:600;color:var(--text);margin-bottom:3px;">${(c.contenu||'').substring(0,60)}…</div>
                    <div style="color:var(--text-d);display:flex;justify-content:space-between;">
                        <span>${c.user?.name||'Visiteur'}</span>
                        <div style="display:flex;gap:6px;">
                            <button onclick="moderateComment(${c.id},'publie')" class="ab ab-pub" style="height:22px;width:auto;padding:0 8px;font-size:.65rem;">
                                <i class="fa-solid fa-check"></i> Approuver
                            </button>
                            <button onclick="moderateComment(${c.id},'rejete')" class="ab ab-del" style="height:22px;width:auto;padding:0 8px;font-size:.65rem;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('')
            : '<div style="padding:20px;color:var(--text-d);font-size:.8rem;text-align:center;"><i class="fa-solid fa-circle-check" style="color:var(--dv-l);margin-right:6px;"></i>Aucun commentaire en attente</div>';
    } catch {}
}

async function moderateComment(id, statut) {
    await fetch(`/api/v1/comments/${id}`, {
        method:'PUT',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},
        body: JSON.stringify({statut})
    });
    showToast(statut==='publie' ? 'Commentaire approuvé ✓' : 'Commentaire rejeté', statut==='publie'?'success':'error');
    loadComments();
}

loadStats();
loadActivityChart();
loadComments();

function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

async function editArticle(id) {
    const r = await fetch(`/api/v1/articles/${id}`,{headers:{Accept:'application/json'}});
    const d = await r.json();
    const a = d.data || d;
    document.getElementById('art-id').value      = a.id;
    document.getElementById('art-titre').value   = a.titre;
    document.getElementById('art-type').value    = a.type;
    document.getElementById('art-statut').value  = a.statut;
    document.getElementById('art-extrait').value = a.extrait||'';
    document.getElementById('art-contenu').value = a.contenu||'';
    document.getElementById('modalTitle').textContent = 'Modifier l\'article';
    openModal('articleModal');
}
</script>
@endpush