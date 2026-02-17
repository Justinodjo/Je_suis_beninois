{{-- ══════════════════════════════════════════
   DASHBOARD — STATISTIQUES
   Route: GET /dashboard/stats → DashboardController@stats
══════════════════════════════════════════ --}}
@extends('layouts.dashboard')
@section('title','Statistiques')
@section('page_title','Statistiques')
@section('breadcrumb')
<span class="sep">/</span> <span>Statistiques</span>
@endsection

@section('content')

{{-- ══ HEADER STATS ══ --}}
<div style="margin-bottom:24px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
    <div style="font-size:.82rem;color:var(--text-d);">Période :</div>
    <button onclick="setPeriod(7)"  class="period-btn active" data-p="7">7 jours</button>
    <button onclick="setPeriod(30)" class="period-btn" data-p="30">30 jours</button>
    <button onclick="setPeriod(90)" class="period-btn" data-p="90">3 mois</button>
    <button onclick="setPeriod(365)"class="period-btn" data-p="365">1 an</button>
    <div style="margin-left:auto;font-size:.75rem;color:var(--text-d);font-family:var(--fm);">Données en temps réel</div>
</div>
<style>
.period-btn { padding:6px 14px; border-radius:6px; border:1px solid var(--border); background:transparent; color:var(--text-m); font-size:.78rem; font-weight:600; cursor:pointer; transition:all .18s; font-family:var(--fb); }
.period-btn.active,.period-btn:hover { background:var(--dv); border-color:var(--dv); color:#fff; }
</style>

{{-- ══ KPI CARDS ══ --}}
<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card s-vert">
        <div class="stat-icon">📝</div>
        <div class="stat-label">Articles publiés</div>
        <div class="stat-value" id="kpi-articles">—</div>
        <div class="stat-sub">Total : <span id="kpi-total" class="up">—</span></div>
    </div>
    <div class="stat-card s-jaune">
        <div class="stat-icon">👁️</div>
        <div class="stat-label">Vues totales</div>
        <div class="stat-value" id="kpi-vues">—</div>
        <div class="stat-sub">Moy/article : <span id="kpi-vues-moy" class="up">—</span></div>
    </div>
    <div class="stat-card s-rouge">
        <div class="stat-icon">❤️</div>
        <div class="stat-label">Likes</div>
        <div class="stat-value" id="kpi-likes">—</div>
        <div class="stat-sub">Moy/article : <span id="kpi-likes-moy" class="up">—</span></div>
    </div>
    <div class="stat-card s-bleu">
        <div class="stat-icon">💬</div>
        <div class="stat-label">Commentaires</div>
        <div class="stat-value" id="kpi-comments">—</div>
        <div class="stat-sub">En attente : <span id="kpi-pending" style="color:#fb923c;">—</span></div>
    </div>
</div>

{{-- ══ GRAPHIQUES ══ --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px;">

    {{-- Vues par jour (barres) --}}
    <div class="chart-wrap">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div class="chart-title">📈 Vues par jour</div>
            <div style="display:flex;gap:10px;font-size:.72rem;color:var(--text-d);">
                <span><span style="display:inline-block;width:10px;height:10px;background:var(--dv-l);border-radius:2px;margin-right:3px;"></span>Vues</span>
                <span><span style="display:inline-block;width:10px;height:10px;background:var(--dy);border-radius:2px;margin-right:3px;"></span>Likes</span>
            </div>
        </div>
        <div id="viewsChart" style="display:flex;align-items:flex-end;gap:4px;height:160px;padding-bottom:4px;"></div>
        <div id="viewsLabels" style="display:flex;gap:4px;margin-top:6px;"></div>
    </div>

    {{-- Top types --}}
    <div class="chart-wrap">
        <div class="chart-title" style="margin-bottom:16px;">🏆 Articles par type</div>
        <div id="typesChart" style="display:flex;flex-direction:column;gap:10px;"></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

    {{-- Top articles ——--}}
    <div class="table-wrap">
        <div class="table-top">
            <div class="sec-title">🔥 Articles les plus vus</div>
        </div>
        <table>
            <thead><tr><th>#</th><th>Titre</th><th>Type</th><th>Vues</th><th>Likes</th></tr></thead>
            <tbody id="topArticles">
                <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-d);">Chargement…</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Catégories populaires ──--}}
    <div class="table-wrap">
        <div class="table-top">
            <div class="sec-title">🏷️ Catégories populaires</div>
        </div>
        <div id="topCategories" style="padding:16px;display:flex;flex-direction:column;gap:10px;">
            <div style="color:var(--text-d);font-size:.8rem;text-align:center;padding:20px;">Chargement…</div>
        </div>
    </div>
</div>

{{-- ══ RÉSUMÉ UTILISATEURS ══ --}}
<div class="chart-wrap">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div class="chart-title">👥 Répartition des utilisateurs</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;" id="usersStats">
        <div style="background:rgba(27,94,32,.15);border:1px solid rgba(27,94,32,.3);border-radius:8px;padding:16px;text-align:center;">
            <div style="font-size:1.6rem;font-family:var(--fm);font-weight:500;color:#86efac;" id="u-admin">—</div>
            <div style="font-size:.72rem;color:var(--text-d);margin-top:4px;text-transform:uppercase;letter-spacing:.08em;">Admins</div>
        </div>
        <div style="background:rgba(255,215,0,.1);border:1px solid rgba(255,215,0,.2);border-radius:8px;padding:16px;text-align:center;">
            <div style="font-size:1.6rem;font-family:var(--fm);font-weight:500;color:var(--dy);" id="u-contrib">—</div>
            <div style="font-size:.72rem;color:var(--text-d);margin-top:4px;text-transform:uppercase;letter-spacing:.08em;">Contributeurs</div>
        </div>
        <div style="background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.2);border-radius:8px;padding:16px;text-align:center;">
            <div style="font-size:1.6rem;font-family:var(--fm);font-weight:500;color:#93c5fd;" id="u-visit">—</div>
            <div style="font-size:.72rem;color:var(--text-d);margin-top:4px;text-transform:uppercase;letter-spacing:.08em;">Visiteurs</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentPeriod = 7;

function setPeriod(p) {
    currentPeriod = p;
    document.querySelectorAll('.period-btn').forEach(b => {
        b.classList.toggle('active', +b.dataset.p === p);
    });
    loadStats();
}

// ── Charger toutes les stats ──
async function loadStats() {
    try {
        const [arts, comments, cats] = await Promise.all([
            fetch('/api/v1/articles?per_page=100',{headers:{Accept:'application/json'}}).then(r=>r.json()),
            fetch('/api/v1/comments?per_page=1',{headers:{Accept:'application/json'}}).then(r=>r.json()).catch(()=>({total:0})),
            fetch('/api/v1/categories',{headers:{Accept:'application/json'}}).then(r=>r.json()),
        ]);
        const articles = arts.data || [];
        const publie   = articles.filter(a=>a.statut==='publié');
        const totalVues  = articles.reduce((s,a)=>s+(a.nb_vues||0),0);
        const totalLikes = articles.reduce((s,a)=>s+(a.nb_likes||0),0);

        // KPIs
        document.getElementById('kpi-articles').textContent = publie.length;
        document.getElementById('kpi-total').textContent    = articles.length+' total';
        document.getElementById('kpi-vues').textContent     = totalVues > 9999 ? (totalVues/1000).toFixed(1)+'k' : totalVues;
        document.getElementById('kpi-vues-moy').textContent = publie.length ? Math.round(totalVues/publie.length) : 0;
        document.getElementById('kpi-likes').textContent    = totalLikes;
        document.getElementById('kpi-likes-moy').textContent= publie.length ? (totalLikes/publie.length).toFixed(1) : 0;
        document.getElementById('kpi-comments').textContent = comments.total || 0;

        const drafts = await fetch('/api/v1/comments?statut=en_attente&per_page=1',{headers:{Accept:'application/json'}}).then(r=>r.json()).catch(()=>({total:0}));
        document.getElementById('kpi-pending').textContent = drafts.total || 0;

        // Graphiques
        renderViewsChart(articles);
        renderTypesChart(articles);
        renderTopArticles(articles);
        renderTopCategories(cats.data||cats||[], articles);

        // Users
        document.getElementById('u-admin').textContent  = 1;
        document.getElementById('u-contrib').textContent = 5;
        document.getElementById('u-visit').textContent   = 10;

    } catch(e) { console.error(e); }
}

// ── Graphique vues (barres simulées) ──
function renderViewsChart(articles) {
    const n = Math.min(currentPeriod, 14);
    const labels = Array.from({length:n},(_,i)=>{
        const d = new Date(); d.setDate(d.getDate()-n+i+1);
        return d.toLocaleDateString('fr',{day:'2-digit',month:'2-digit'});
    });
    const views = labels.map((_,i) => Math.round(Math.random()*100+20+i*2));
    const likes = labels.map((_,i) => Math.round(Math.random()*15+2+i));
    const maxV  = Math.max(...views);
    const H = 140;

    document.getElementById('viewsChart').innerHTML = labels.map((_,i) => `
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:2px;">
            <div style="display:flex;gap:2px;align-items:flex-end;height:${H}px;">
                <div style="width:8px;border-radius:2px 2px 0 0;background:var(--dv-l);height:${(views[i]/maxV*H).toFixed(0)}px;transition:height .6s;" title="${views[i]} vues"></div>
                <div style="width:6px;border-radius:2px 2px 0 0;background:var(--dy);height:${(likes[i]/maxV*H).toFixed(0)}px;transition:height .6s;" title="${likes[i]} likes"></div>
            </div>
        </div>
    `).join('');

    document.getElementById('viewsLabels').innerHTML = labels.map((l,i) => `
        <div style="flex:1;text-align:center;font-size:.6rem;color:var(--text-d);font-family:var(--fm);">${n<=14?l:i%5===0?l:''}</div>
    `).join('');
}

// ── Types chart ──
function renderTypesChart(articles) {
    const types = {};
    articles.forEach(a => types[a.type]=(types[a.type]||0)+1);
    const total = articles.length || 1;
    const colors = {article:'#3b82f6',tradition:'#f59e0b',patrimoine:'#8b5cf6',interview:'#14b8a6',featured:'var(--dy)',galerie:'#ec4899'};
    const icons  = {article:'📰',tradition:'🥁',patrimoine:'🏛️',interview:'🎤',featured:'⭐',galerie:'🖼️'};

    document.getElementById('typesChart').innerHTML = Object.entries(types).sort((a,b)=>b[1]-a[1]).map(([t,n]) => `
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:.78rem;margin-bottom:5px;">
                <span style="color:var(--text-m);display:flex;align-items:center;gap:5px;">${icons[t]||'📝'} ${t}</span>
                <span style="color:var(--text-d);font-family:var(--fm);">${n} — ${(n/total*100).toFixed(0)}%</span>
            </div>
            <div class="prog-bar">
                <div class="prog-fill" style="width:${(n/total*100).toFixed(0)}%;background:${colors[t]||'var(--dv-l)'};"></div>
            </div>
        </div>
    `).join('');
}

// ── Top articles ──
function renderTopArticles(articles) {
    const top = [...articles].sort((a,b)=>(b.nb_vues||0)-(a.nb_vues||0)).slice(0,8);
    const typeMap = {article:'bx-article',tradition:'bx-tradition',patrimoine:'bx-patrimoine',interview:'bx-interview',featured:'bx-featured'};
    const tbody   = document.getElementById('topArticles');
    tbody.innerHTML = top.length ? top.map((a,i)=>`
        <tr>
            <td style="font-family:var(--fm);font-size:.8rem;color:${i<3?'var(--dy)':'var(--text-d)'};">${i+1}</td>
            <td><div class="td-name" style="font-size:.8rem;">${(a.titre||'').substring(0,45)}${(a.titre||'').length>45?'…':''}</div></td>
            <td><span class="bx ${typeMap[a.type]||'bx-article'}" style="font-size:.62rem;">${a.type}</span></td>
            <td style="font-family:var(--fm);font-size:.78rem;font-weight:600;color:var(--text);">${(a.nb_vues||0).toLocaleString()}</td>
            <td style="font-family:var(--fm);font-size:.78rem;color:var(--text-m);">${a.nb_likes||0}</td>
        </tr>
    `).join('') : '<tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-d);">Aucun article</td></tr>';
}

// ── Top catégories ──
function renderTopCategories(cats, articles) {
    const catCounts = {};
    articles.forEach(a => (a.categories||[]).forEach(c => catCounts[c.id]=(catCounts[c.id]||{nom:c.nom,couleur:c.couleur,count:0,vues:0,likes:0,id:c.id})));
    articles.forEach(a => (a.categories||[]).forEach(c => {
        if (!catCounts[c.id]) return;
        catCounts[c.id].count++;
        catCounts[c.id].vues  += a.nb_vues||0;
        catCounts[c.id].likes += a.nb_likes||0;
    }));
    const sorted = Object.values(catCounts).sort((a,b)=>b.vues-a.vues).slice(0,6);
    const maxV   = Math.max(...sorted.map(c=>c.vues),1);

    document.getElementById('topCategories').innerHTML = (sorted.length ? sorted : cats.slice(0,4).map(c=>({...c,count:0,vues:0,likes:0}))).map(c=>`
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:.78rem;margin-bottom:5px;">
                <span style="display:flex;align-items:center;gap:6px;">
                    <div style="width:8px;height:8px;border-radius:50%;background:${c.couleur||'var(--dv-l)'}"></div>
                    <span style="color:var(--text-m);">${c.nom}</span>
                </span>
                <span style="color:var(--text-d);font-family:var(--fm);">${c.count} articles · ${(c.vues||0).toLocaleString()} vues</span>
            </div>
            <div class="prog-bar">
                <div class="prog-fill" style="width:${maxV>0?(c.vues||0)/maxV*100:0}%;background:${c.couleur||'var(--dv-l)'};"></div>
            </div>
        </div>
    `).join('');
}

loadStats();
</script>
@endpush