// media.js - Gestion des médias
// VERSION CORRIGÉE - 2026-02-09

class MediaManager {
    constructor() {
        this.initElements();
    }

    initElements() {
        this.mediaLibraryInput = document.getElementById('mediaLibraryInput');
        this.mediaSearchInput = document.getElementById('mediaSearchInput');
        this.mediaFilterType = document.getElementById('mediaFilterType');
        this.mediaSortBy = document.getElementById('mediaSortBy');
        this.mediaGrid = document.getElementById('mediaLibraryGrid');
        this.mediaTotalCount = document.getElementById('mediaTotalCount');
        this.mediaImagesCount = document.getElementById('mediaImagesCount');
        this.mediaVideosCount = document.getElementById('mediaVideosCount');
    }

    init() {
        this.attachEventListeners();
        this.subscribeToState();
    }

    attachEventListeners() {
        if (this.mediaLibraryInput) {
            this.mediaLibraryInput.addEventListener('change', (e) => this.handleUpload(e));
        }
        
        if (this.mediaSearchInput) {
            this.mediaSearchInput.addEventListener('input', (e) => {
                state.setMediaFilter('search', e.target.value);
            });
        }
        
        if (this.mediaFilterType) {
            this.mediaFilterType.addEventListener('change', (e) => {
                state.setMediaFilter('type', e.target.value);
            });
        }
        
        if (this.mediaSortBy) {
            this.mediaSortBy.addEventListener('change', (e) => {
                state.setMediaFilter('sortBy', e.target.value);
            });
        }
    }

    subscribeToState() {
        state.subscribe('media', () => this.render());
        state.subscribe('media-filter', () => this.render());
    }

    async loadMedia() {
        try {
            const response = await api.getMedia();
            state.setMedia(response.data || response || []);
        } catch (error) {
            console.error('Erreur chargement médias:', error);
            throw error;
        }
    }

    render() {
        this.renderGrid();
        this.updateStats();
    }

    renderGrid() {
        if (!this.mediaGrid) return;
        
        const media = state.getFilteredMedia();
        
        this.mediaGrid.innerHTML = '';
        
        if (media.length === 0) {
            this.mediaGrid.innerHTML = '<div class="empty-media-state" style="grid-column: 1 / -1;"><div class="empty-icon">🖼️</div><div class="empty-text">Aucun média trouvé</div></div>';
        } else {
            media.forEach((mediaItem) => {
                const item = this.createMediaCard(mediaItem);
                this.mediaGrid.appendChild(item);
            });
        }
    }

    createMediaCard(media) {
        const div = document.createElement('div');
        div.className = 'media-library-item';
        
        const url = media.url || media.url_thumbnail;
        const content = media.type === 'video'
            ? '<img src="' + url + '" alt="Vidéo"><div class="video-overlay"><span class="play-btn">▶️</span></div>'
            : '<img src="' + url + '" alt="' + (media.titre || '') + '">';
        
        div.innerHTML = '<div class="media-library-preview">' + content + '<span class="media-library-type">' + (media.type || 'image').toUpperCase() + '</span><div class="media-library-actions"><button class="media-action-btn download" title="Télécharger">⬇️</button><button class="media-action-btn delete" title="Supprimer">🗑️</button></div></div><div class="media-library-info"><div class="media-library-name" title="' + (media.titre || 'Sans titre') + '">' + (media.titre || 'Sans titre') + '</div><div class="media-library-size">' + this.formatFileSize(media.taille || 0) + '</div><div class="media-library-date">' + this.formatDate(media.created_at) + '</div></div>';
        
        const btnDownload = div.querySelector('.download');
        const btnDelete = div.querySelector('.delete');
        
        if (btnDownload) {
            btnDownload.addEventListener('click', () => this.downloadMedia(media));
        }
        
        if (btnDelete) {
            btnDelete.addEventListener('click', () => this.deleteMedia(media.id));
        }
        
        return div;
    }

    async handleUpload(e) {
        const files = Array.from(e.target.files);
        if (files.length === 0) return;
        
        if (window.UI) {
            window.UI.showLoading('Upload de ' + files.length + ' fichier(s)...');
        }
        
        try {
            for (const file of files) {
                if (file.type.startsWith('image/')) {
                    const response = await api.uploadImage(file, {
                        titre: file.name,
                        alt_text: file.name,
                        category: 'galerie'
                    });
                    state.addMedia(response.media || response);
                } else if (file.type.startsWith('video/')) {
                    const response = await api.uploadVideo(file, {
                        titre: file.name
                    });
                    state.addMedia(response.media || response);
                }
            }
            
            if (window.UI) {
                window.UI.showSuccess(files.length + ' fichier(s) uploadé(s) !');
            }
            await this.loadMedia();
        } catch (error) {
            if (window.UI) {
                window.UI.showError('Erreur: ' + error.message);
            } else {
                alert('Erreur: ' + error.message);
            }
        } finally {
            if (window.UI) window.UI.hideLoading();
            e.target.value = '';
        }
    }

    downloadMedia(media) {
        const link = document.createElement('a');
        link.href = media.url;
        link.download = media.titre || 'media';
        link.target = '_blank';
        link.click();
    }

    async deleteMedia(id) {
        if (!confirm('Supprimer ce média ?')) return;
        
        if (window.UI) window.UI.showLoading('Suppression...');
        
        try {
            await api.deleteMedia(id);
            state.deleteMedia(id);
            if (window.UI) window.UI.showSuccess('Média supprimé !');
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

    updateStats() {
        const stats = state.getMediaStats();
        
        if (this.mediaTotalCount) this.mediaTotalCount.textContent = stats.total;
        if (this.mediaImagesCount) this.mediaImagesCount.textContent = stats.images;
        if (this.mediaVideosCount) this.mediaVideosCount.textContent = stats.videos;
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    formatDate(dateString) {
        if (!dateString) return 'Date inconnue';
        
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (minutes < 1) return 'À l\'instant';
        if (minutes < 60) return 'Il y a ' + minutes + ' min';
        if (hours < 24) return 'Il y a ' + hours + 'h';
        if (days < 7) return 'Il y a ' + days + 'j';
        
        return date.toLocaleDateString('fr-FR');
    }
}

// Instance globale
const mediaManager = new MediaManager();
console.log('✅ Media.js chargé');