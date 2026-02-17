// app.js - Application principale
// VERSION CORRIGÉE - 2026-02-09

class App {
    constructor() {
        this.initElements();
        this.currentView = 'articles';
    }

    initElements() {
        this.sidebarLinks = document.querySelectorAll('.sidebar-link');
        this.views = {
            articles: document.getElementById('articlesView'),
            categories: document.getElementById('categoriesView'),
            tags: document.getElementById('tagsView'),
            medias: document.getElementById('mediasView'),
            stats: document.getElementById('statsView')
        };
        this.pageTitle = document.getElementById('pageTitle');
        this.pageSubtitle = document.getElementById('pageSubtitle');
        this.btnNewArticle = document.getElementById('btnNewArticle');
    }

    async init() {
        console.log('🇧🇯 Je Suis Béninois - Dashboard v1.0');
        
        // 1. UI en premier
        UI.init();
        
        // 2. Vérifier que les modules existent
        if (typeof authManager === 'undefined') {
            console.error('❌ authManager non défini !');
            return;
        }
        if (typeof articlesManager === 'undefined') {
            console.error('❌ articlesManager non défini !');
            return;
        }
        if (typeof mediaManager === 'undefined') {
            console.error('❌ mediaManager non défini !');
            return;
        }
        
        // 3. Initialiser les modules
        authManager.init();
        articlesManager.init();
        mediaManager.init();
        
        // 4. Event listeners
        this.attachEventListeners();
        this.subscribeToState();
    }

    attachEventListeners() {
        this.sidebarLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const view = link.dataset.view;
                this.switchView(view);
            });
        });
    }

    subscribeToState() {
        state.subscribe('view', () => {
            this.updateView();
        });
    }

    async loadInitialData() {
        state.setLoading(true, 'Chargement des données...');
        UI.showLoading('Chargement des données...');
        
        try {
            await Promise.all([
                articlesManager.loadArticles(),
                mediaManager.loadMedia()
            ]);
            
            articlesManager.render();
            mediaManager.render();
            
            console.log('✅ Données chargées');
        } catch (error) {
            console.error('❌ Erreur:', error);
            UI.showError('Erreur lors du chargement');
        } finally {
            state.setLoading(false);
            UI.hideLoading();
        }
    }

    switchView(view) {
        if (this.currentView === view) return;
        
        this.currentView = view;
        state.setView(view);
        
        this.animateViewTransition(view);
        
        if (view === 'medias') {
            mediaManager.render();
        }
    }

    animateViewTransition(newView) {
        Object.values(this.views).forEach(view => {
            if (view) view.classList.remove('active');
        });
        
        setTimeout(() => {
            Object.entries(this.views).forEach(([key, view]) => {
                if (view && key === newView) {
                    view.classList.add('active');
                }
            });
        }, 100);
        
        this.sidebarLinks.forEach(link => {
            if (link.dataset.view === newView) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
        
        this.updatePageTitle(newView);
        
        if (this.btnNewArticle) {
            this.btnNewArticle.style.display = newView === 'articles' ? 'inline-flex' : 'none';
        }
    }

    updateView() {
        const view = state.ui.currentView;
        this.switchView(view);
    }

    updatePageTitle(view) {
        const titles = {
            articles: 'Gestion des Articles',
            categories: 'Gestion des Catégories',
            tags: 'Gestion des Tags',
            medias: 'Gestion des Médias',
            stats: 'Statistiques'
        };
        
        const getSubtitle = (view) => {
            switch (view) {
                case 'articles':
                    return state.getFilteredArticles().length + ' article(s)';
                case 'medias':
                    return state.data.media.length + ' média(s)';
                case 'stats':
                    return 'Vue d\'ensemble';
                default:
                    return 'Gérez vos contenus';
            }
        };
        
        if (this.pageTitle) {
            this.pageTitle.textContent = titles[view] || '';
        }
        
        if (this.pageSubtitle) {
            this.pageSubtitle.textContent = getSubtitle(view);
        }
    }
}

// ============================================
// UI Manager
// ============================================

class UIManager {
    constructor() {
        this.loadingOverlay = null;
        this.toastContainer = null;
    }

    init() {
        this.createToastContainer();
    }

    createToastContainer() {
        this.toastContainer = document.createElement('div');
        this.toastContainer.className = 'toast-container';
        document.body.appendChild(this.toastContainer);
    }

    showLoading(message) {
        this.hideLoading();
        
        this.loadingOverlay = document.createElement('div');
        this.loadingOverlay.className = 'loading-overlay fade-in';
        this.loadingOverlay.innerHTML = '<div class="loading-content scale-in"><div class="loading-spinner">⏳</div><div class="loading-text">' + message + '</div></div>';
        document.body.appendChild(this.loadingOverlay);
    }

    hideLoading() {
        if (this.loadingOverlay) {
            this.loadingOverlay.remove();
            this.loadingOverlay = null;
        }
    }

    showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = 'toast toast-' + type + ' slide-in-right';
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };
        
        toast.innerHTML = '<span class="toast-icon">' + icons[type] + '</span><span class="toast-message">' + message + '</span>';
        
        this.toastContainer.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 10);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    showSuccess(message) {
        this.showToast(message, 'success');
    }

    showError(message) {
        this.showToast(message, 'error');
    }

    showWarning(message) {
        this.showToast(message, 'warning');
    }

    showInfo(message) {
        this.showToast(message, 'info');
    }
}

// ============================================
// Initialisation
// ============================================

const UI = new UIManager();
const app = new App();

// Démarrer l'application
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => app.init());
} else {
    app.init();
}

// Exposer globalement
window.app = app;
window.state = state;
window.api = api;
window.UI = UI;

console.log('✅ App.js chargé');