// articles.js - Gestion des articles
// VERSION CORRIGÉE - 2026-02-09

class ArticlesManager {
    constructor() {
        this.initElements();
    }

    initElements() {
        this.btnNewArticle = document.getElementById('btnNewArticle');
        this.searchInput = document.getElementById('searchInput');
        this.filterStatutSelect = document.getElementById('filterStatut');
        this.filterTypeSelect = document.getElementById('filterType');
        this.articlesTableBody = document.getElementById('articlesTableBody');
        this.modal = document.getElementById('articleModal');
        this.modalTitle = document.getElementById('modalTitle');
        this.modalClose = document.getElementById('modalClose');
        this.btnCancel = document.getElementById('btnCancel');
        this.btnSave = document.getElementById('btnSave');
        this.btnSaveText = document.getElementById('btnSaveText');
        this.formTitre = document.getElementById('formTitre');
        this.formSlug = document.getElementById('formSlug');
        this.formExtrait = document.getElementById('formExtrait');
        this.formContenu = document.getElementById('formContenu');
        this.formType = document.getElementById('formType');
        this.formStatut = document.getElementById('formStatut');
        this.formMedia = document.getElementById('formMedia');
        this.mediaUploadArea = document.getElementById('mediaUploadArea');
        this.mediaPreview = document.getElementById('mediaPreview');
    }

    init() {
        this.attachEventListeners();
        this.subscribeToState();
    }

    attachEventListeners() {
        if (this.btnNewArticle) {
            this.btnNewArticle.addEventListener('click', () => this.openCreateModal());
        }
        
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                state.setArticleFilter('search', e.target.value);
            });
        }
        
        if (this.filterStatutSelect) {
            this.filterStatutSelect.addEventListener('change', (e) => {
                state.setArticleFilter('statut', e.target.value);
            });
        }
        
        if (this.filterTypeSelect) {
            this.filterTypeSelect.addEventListener('change', (e) => {
                state.setArticleFilter('type', e.target.value);
            });
        }
        
        if (this.modalClose) {
            this.modalClose.addEventListener('click', () => this.closeModal());
        }
        
        if (this.btnCancel) {
            this.btnCancel.addEventListener('click', () => this.closeModal());
        }
        
        if (this.btnSave) {
            this.btnSave.addEventListener('click', () => this.saveArticle());
        }
        
        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal || e.target.classList.contains('modal-overlay')) {
                    this.closeModal();
                }
            });
        }
        
        if (this.formTitre) {
            this.formTitre.addEventListener('input', (e) => {
                if (this.formSlug) {
                    this.formSlug.value = this.generateSlug(e.target.value);
                }
            });
        }
    }

    subscribeToState() {
        state.subscribe('articles', () => this.render());
        state.subscribe('articles-filter', () => this.render());
        state.subscribe('modal', () => this.updateModal());
    }

    async loadArticles() {
        try {
            const response = await api.getArticles();
            state.setArticles(response.data || response || []);
        } catch (error) {
            console.error('Erreur chargement articles:', error);
            throw error;
        }
    }

    render() {
        this.renderTable();
        this.updateStats();
    }

    renderTable() {
        if (!this.articlesTableBody) return;
        
        const articles = state.getFilteredArticles();
        
        this.articlesTableBody.innerHTML = '';
        
        if (articles.length === 0) {
            this.articlesTableBody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 60px; color: var(--gray-400);"><div style="font-size: 48px; margin-bottom: 16px;">📝</div><div style="font-size: 16px; color: var(--gray-500);">Aucun article trouvé</div></td></tr>';
        } else {
            articles.forEach((article) => {
                const row = this.createArticleRow(article);
                this.articlesTableBody.appendChild(row);
            });
        }
    }

    createArticleRow(article) {
        const row = document.createElement('tr');
        
        let mediaHTML = '<span class="no-media-text">Aucun média</span>';
        if (article.medias && article.medias.length > 0) {
            const media = article.medias[0];
            const url = media.url || media.url_thumbnail;
            if (media.type === 'video') {
                mediaHTML = '<div class="media-thumbnail-container"><img class="article-media-thumbnail" src="' + url + '" alt="Vidéo"><span class="media-play-icon">▶️</span></div>';
            } else {
                mediaHTML = '<img class="article-media-thumbnail" src="' + url + '" alt="' + (media.titre || '') + '">';
            }
        }
        
        const userName = article.user ? article.user.name : 'Utilisateur';
        
        row.innerHTML = '<td><span class="article-id">#' + article.id + '</span></td><td>' + mediaHTML + '</td><td><div><p class="article-title">' + this.truncate(article.titre, 50) + '</p><p class="article-excerpt">' + this.truncate(article.extrait || '', 60) + '</p></div></td><td><span class="article-author">' + userName + '</span></td><td><span class="type-badge">' + article.type + '</span></td><td><span class="status-badge status-' + article.statut + '">' + article.statut + '</span></td><td><div class="article-stats"><span title="Vues">👁️ ' + (article.nb_vues || 0) + '</span><span title="Likes">❤️ ' + (article.nb_likes || 0) + '</span><span title="Commentaires">💬 ' + (article.nb_commentaires || 0) + '</span></div></td><td><div class="action-buttons"><button class="btn btn-edit" data-id="' + article.id + '">✏️</button><button class="btn btn-delete" data-id="' + article.id + '">🗑️</button></div></td>';
        
        const btnEdit = row.querySelector('.btn-edit');
        const btnDelete = row.querySelector('.btn-delete');
        
        if (btnEdit) {
            btnEdit.addEventListener('click', () => this.openEditModal(article.id));
        }
        
        if (btnDelete) {
            btnDelete.addEventListener('click', () => this.deleteArticle(article.id));
        }
        
        return row;
    }

    updateStats() {
        const stats = state.getStats();
        
        const elTotal = document.getElementById('statTotal');
        const elPublie = document.getElementById('statPublie');
        const elBrouillon = document.getElementById('statBrouillon');
        const elArchive = document.getElementById('statArchive');
        
        if (elTotal) elTotal.textContent = stats.total;
        if (elPublie) elPublie.textContent = stats.publie;
        if (elBrouillon) elBrouillon.textContent = stats.brouillon;
        if (elArchive) elArchive.textContent = stats.archive;
        
        const elGlobalTotal = document.getElementById('statsGlobalTotal');
        const elGlobalVues = document.getElementById('statsGlobalVues');
        const elGlobalLikes = document.getElementById('statsGlobalLikes');
        
        if (elGlobalTotal) elGlobalTotal.textContent = stats.total;
        if (elGlobalVues) elGlobalVues.textContent = stats.totalVues.toLocaleString();
        if (elGlobalLikes) elGlobalLikes.textContent = stats.totalLikes.toLocaleString();
        
        const publiePercent = stats.total > 0 ? Math.round((stats.publie / stats.total) * 100) : 0;
        const brouillonPercent = stats.total > 0 ? Math.round((stats.brouillon / stats.total) * 100) : 0;
        const archivePercent = stats.total > 0 ? Math.round((stats.archive / stats.total) * 100) : 0;
        
        const updateProgress = (id, percent, count) => {
            const elem = document.getElementById(id);
            const textElem = document.getElementById(id + 'Text');
            if (elem) elem.style.width = percent + '%';
            if (textElem) textElem.textContent = count + ' (' + percent + '%)';
        };
        
        updateProgress('progressPublie', publiePercent, stats.publie);
        updateProgress('progressBrouillon', brouillonPercent, stats.brouillon);
        updateProgress('progressArchive', archivePercent, stats.archive);
    }

    openCreateModal() {
        state.setModalMode('create');
        state.clearTempMediaFiles();
        
        this.resetForm();
        if (this.modal) this.modal.classList.add('active');
    }

    openEditModal(id) {
        const article = state.data.articles.find(a => a.id === id);
        if (!article) return;
        
        state.setModalMode('edit', article);
        state.setTempMediaFiles(article.medias || []);
        
        this.fillForm(article);
        if (this.modal) this.modal.classList.add('active');
    }

    closeModal() {
        if (this.modal) this.modal.classList.remove('active');
    }

    updateModal() {
        const { modalMode } = state.ui;
        
        if (this.modalTitle) {
            this.modalTitle.textContent = modalMode === 'create' ? '➕ Nouvel Article' : '✏️ Modifier l\'Article';
        }
        
        if (this.btnSaveText) {
            this.btnSaveText.textContent = modalMode === 'create' ? 'Créer' : 'Sauvegarder';
        }
    }

    resetForm() {
        if (this.formTitre) this.formTitre.value = '';
        if (this.formSlug) this.formSlug.value = '';
        if (this.formExtrait) this.formExtrait.value = '';
        if (this.formContenu) this.formContenu.value = '';
        if (this.formType) this.formType.value = 'article';
        if (this.formStatut) this.formStatut.value = 'brouillon';
    }

    fillForm(article) {
        if (this.formTitre) this.formTitre.value = article.titre;
        if (this.formSlug) this.formSlug.value = article.slug;
        if (this.formExtrait) this.formExtrait.value = article.extrait || '';
        if (this.formContenu) this.formContenu.value = article.contenu;
        if (this.formType) this.formType.value = article.type;
        if (this.formStatut) this.formStatut.value = article.statut;
    }

    async saveArticle() {
        const titre = this.formTitre ? this.formTitre.value.trim() : '';
        const slug = this.formSlug ? this.formSlug.value.trim() : '';
        const extrait = this.formExtrait ? this.formExtrait.value.trim() : '';
        const contenu = this.formContenu ? this.formContenu.value.trim() : '';
        const type = this.formType ? this.formType.value : 'article';
        const statut = this.formStatut ? this.formStatut.value : 'brouillon';
        
        if (!titre || !slug || !contenu) {
            if (window.UI) {
                window.UI.showError('Veuillez remplir les champs obligatoires');
            } else {
                alert('Veuillez remplir les champs obligatoires');
            }
            return;
        }
        
        const articleData = { titre, slug, extrait, contenu, type, statut, user_id: 1 };
        
        if (window.UI) {
            window.UI.showLoading(state.ui.modalMode === 'create' ? 'Création...' : 'Mise à jour...');
        }
        
        try {
            if (state.ui.modalMode === 'create') {
                const response = await api.createArticle(articleData);
                state.addArticle(response.article || response);
                if (window.UI) window.UI.showSuccess('Article créé !');
            } else {
                const response = await api.updateArticle(state.ui.currentArticle.id, articleData);
                state.updateArticle(state.ui.currentArticle.id, response.article || response);
                if (window.UI) window.UI.showSuccess('Article mis à jour !');
            }
            
            this.closeModal();
        } catch (error) {
            if (window.UI) {
                window.UI.showError(error.message);
            } else {
                alert('Erreur: ' + error.message);
            }
        } finally {
            if (window.UI) window.UI.hideLoading();
        }
    }

    async deleteArticle(id) {
        if (!confirm('Supprimer cet article ?')) return;
        
        if (window.UI) window.UI.showLoading('Suppression...');
        
        try {
            await api.deleteArticle(id);
            state.deleteArticle(id);
            if (window.UI) window.UI.showSuccess('Article supprimé !');
        } catch (error) {
            if (window.UI) {
                window.UI.showError(error.message);
            } else {
                alert('Erreur: ' + error.message);
            }
        } finally {
            if (window.UI) window.UI.hideLoading();
        }
    }

    truncate(text, length) {
        if (!text) return '';
        return text.length <= length ? text : text.substring(0, length) + '...';
    }

    generateSlug(text) {
        return text.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
}

// Instance globale
const articlesManager = new ArticlesManager();
console.log('✅ Articles.js chargé');