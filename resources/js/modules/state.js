// state.js - Gestion centralisée de l'état de l'application
// VERSION CORRIGÉE - 2026-02-09

class AppState {
    constructor() {
        // État de l'authentification
        this.auth = {
            isAuthenticated: false,
            user: null,
            token: localStorage.getItem('auth_token') || null
        };

        // Données de l'application
        this.data = {
            articles: [],
            categories: [],
            tags: [],
            media: []
        };

        // État de l'interface
        this.ui = {
            currentView: 'articles',
            modalMode: 'create',
            currentArticle: null,
            isLoading: false,
            loadingMessage: ''
        };

        // Filtres
        this.filters = {
            articles: {
                search: '',
                statut: 'tous',
                type: 'tous'
            },
            media: {
                search: '',
                type: 'tous',
                sortBy: 'recent'
            }
        };

        // Fichiers temporaires
        this.temp = {
            mediaFiles: []
        };

        // Observateurs
        this.observers = [];
    }

    // Méthodes d'authentification
    setAuth(user, token) {
        this.auth.user = user;
        this.auth.token = token;
        this.auth.isAuthenticated = true;
        if (token) {
            localStorage.setItem('auth_token', token);
        }
        this.notify('auth');
    }

    clearAuth() {
        this.auth.user = null;
        this.auth.token = null;
        this.auth.isAuthenticated = false;
        localStorage.removeItem('auth_token');
        this.notify('auth');
    }

    // Méthodes de données
    setArticles(articles) {
        this.data.articles = articles;
        this.notify('articles');
    }

    addArticle(article) {
        this.data.articles.push(article);
        this.notify('articles');
    }

    updateArticle(id, articleData) {
        const index = this.data.articles.findIndex(a => a.id === id);
        if (index !== -1) {
            this.data.articles[index] = { ...this.data.articles[index], ...articleData };
            this.notify('articles');
        }
    }

    deleteArticle(id) {
        this.data.articles = this.data.articles.filter(a => a.id !== id);
        this.notify('articles');
    }

    setCategories(categories) {
        this.data.categories = categories;
        this.notify('categories');
    }

    setTags(tags) {
        this.data.tags = tags;
        this.notify('tags');
    }

    setMedia(media) {
        this.data.media = media;
        this.notify('media');
    }

    addMedia(mediaItem) {
        this.data.media.push(mediaItem);
        this.notify('media');
    }

    deleteMedia(id) {
        this.data.media = this.data.media.filter(m => m.id !== id);
        this.notify('media');
    }

    // Méthodes UI
    setView(view) {
        this.ui.currentView = view;
        this.notify('view');
    }

    setModalMode(mode, article = null) {
        this.ui.modalMode = mode;
        this.ui.currentArticle = article;
        this.notify('modal');
    }

    setLoading(isLoading, message = '') {
        this.ui.isLoading = isLoading;
        this.ui.loadingMessage = message;
        this.notify('loading');
    }

    // Méthodes de filtres
    setArticleFilter(key, value) {
        this.filters.articles[key] = value;
        this.notify('articles-filter');
    }

    setMediaFilter(key, value) {
        this.filters.media[key] = value;
        this.notify('media-filter');
    }

    // Méthodes de fichiers temporaires
    setTempMediaFiles(files) {
        this.temp.mediaFiles = files;
        this.notify('temp-media');
    }

    addTempMediaFile(file) {
        this.temp.mediaFiles.push(file);
        this.notify('temp-media');
    }

    removeTempMediaFile(index) {
        this.temp.mediaFiles.splice(index, 1);
        this.notify('temp-media');
    }

    clearTempMediaFiles() {
        this.temp.mediaFiles = [];
        this.notify('temp-media');
    }

    // Getters
    getFilteredArticles() {
        const { search, statut, type } = this.filters.articles;
        return this.data.articles.filter(article => {
            const matchSearch = !search || 
                article.titre.toLowerCase().includes(search.toLowerCase()) ||
                (article.extrait && article.extrait.toLowerCase().includes(search.toLowerCase()));
            const matchStatut = statut === 'tous' || article.statut === statut;
            const matchType = type === 'tous' || article.type === type;
            return matchSearch && matchStatut && matchType;
        });
    }

    getFilteredMedia() {
        const { search, type, sortBy } = this.filters.media;
        let filtered = [...this.data.media];

        if (search) {
            filtered = filtered.filter(media => {
                const name = media.titre || media.name || '';
                return name.toLowerCase().includes(search.toLowerCase());
            });
        }

        if (type !== 'tous') {
            filtered = filtered.filter(media => media.type === type);
        }

        switch (sortBy) {
            case 'recent':
                filtered.sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
                break;
            case 'ancien':
                filtered.sort((a, b) => new Date(a.created_at || 0) - new Date(b.created_at || 0));
                break;
            case 'nom':
                filtered.sort((a, b) => (a.titre || '').localeCompare(b.titre || ''));
                break;
            case 'taille':
                filtered.sort((a, b) => (b.taille || 0) - (a.taille || 0));
                break;
        }

        return filtered;
    }

    // Statistiques
    getStats() {
        const articles = this.data.articles;
        return {
            total: articles.length,
            publie: articles.filter(a => a.statut === 'publié').length,
            brouillon: articles.filter(a => a.statut === 'brouillon').length,
            archive: articles.filter(a => a.statut === 'archivé').length,
            totalVues: articles.reduce((sum, a) => sum + (a.nb_vues || 0), 0),
            totalLikes: articles.reduce((sum, a) => sum + (a.nb_likes || 0), 0),
            totalCommentaires: articles.reduce((sum, a) => sum + (a.nb_commentaires || 0), 0)
        };
    }

    getMediaStats() {
        const media = this.data.media;
        return {
            total: media.length,
            images: media.filter(m => m.type === 'image').length,
            videos: media.filter(m => m.type === 'video').length,
            totalSize: media.reduce((sum, m) => sum + (m.taille || 0), 0)
        };
    }

    // Observateurs
    subscribe(type, callback) {
        this.observers.push({ type, callback });
    }

    unsubscribe(callback) {
        this.observers = this.observers.filter(obs => obs.callback !== callback);
    }

    notify(type) {
        this.observers
            .filter(obs => obs.type === type || obs.type === '*')
            .forEach(obs => obs.callback(this));
    }
}

// Instance globale
const state = new AppState();
console.log('✅ State.js chargé');