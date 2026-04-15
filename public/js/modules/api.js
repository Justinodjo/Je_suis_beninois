// api.js - Gestion des appels API
// VERSION CORRIGÉE - 2026-02-09

const API_CONFIG = {
    baseURL: 'http://127.0.0.1:8000/api/v1',
    endpoints: {
        login: '/login',
        register: '/register',
        logout: '/logout',
        articles: '/articles',
        article: (id) => `/articles/${id}`,
        categories: '/categories',
        category: (id) => `/categories/${id}`,
        tags: '/tags',
        tag: (id) => `/tags/${id}`,
        media: '/media',
        mediaItem: (id) => `/media/${id}`,
        uploadImage: '/media/upload/image',
        uploadVideo: '/media/upload/video',
        mediaStats: '/media/stats/overview',
    }
};

class ApiService {
    constructor() {
        this.baseURL = API_CONFIG.baseURL;
    }

    getToken() {
        return state.auth.token;
    }

    getHeaders(isFormData = false) {
        const headers = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': window.Laravel.csrfToken 
        };
        
        const token = this.getToken();
        if (token) {
            headers['Authorization'] = 'Bearer ' + token;
        }
        
        if (!isFormData) {
            headers['Content-Type'] = 'application/json';
        }
        
        return headers;
    }

    async request(endpoint, options = {}) {
        const url = this.baseURL + endpoint;
        const isFormData = options.body instanceof FormData;
        
        const config = {
            method: options.method || 'GET',
            headers: this.getHeaders(isFormData),
            ...options
        };

        try {
            const response = await fetch(url, config);
            
            if (response.status === 401) {
                state.clearAuth();
                throw new Error('Session expirée. Veuillez vous reconnecter.');
            }

            if (!response.ok) {
                const error = await response.json().catch(() => ({ message: 'Erreur serveur' }));
                throw new Error(error.message || 'Erreur ' + response.status);
            }

            return await response.json();
        } catch (error) {
            console.error('Erreur API:', error);
            throw error;
        }
    }

    async get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    }

    async post(endpoint, data) {
        return this.request(endpoint, {
            method: 'POST',
            body: data instanceof FormData ? data : JSON.stringify(data)
        });
    }

    async put(endpoint, data) {
        return this.request(endpoint, {
            method: 'PUT',
            body: data instanceof FormData ? data : JSON.stringify(data)
        });
    }

    async delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }

    // Auth
    async login(email, password) {
        const response = await this.post(API_CONFIG.endpoints.login, { email, password });
        if (response.token) {
            state.setAuth(response.user, response.token);
        }
        return response;
    }

    async logout() {
        try {
            await this.post(API_CONFIG.endpoints.logout);
        } finally {
            state.clearAuth();
        }
    }

    // Articles
    async getArticles(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = queryString ? API_CONFIG.endpoints.articles + '?' + queryString : API_CONFIG.endpoints.articles;
        return this.get(endpoint);
    }

    async getArticle(id) {
        return this.get(API_CONFIG.endpoints.article(id));
    }

    async createArticle(articleData) {
        return this.post(API_CONFIG.endpoints.articles, articleData);
    }

    async updateArticle(id, articleData) {
        return this.put(API_CONFIG.endpoints.article(id), articleData);
    }

    async deleteArticle(id) {
        return this.delete(API_CONFIG.endpoints.article(id));
    }

    // Médias
    async getMedia(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = queryString ? API_CONFIG.endpoints.media + '?' + queryString : API_CONFIG.endpoints.media;
        return this.get(endpoint);
    }

    async uploadImage(file, metadata = {}) {
        const formData = new FormData();
        formData.append('image', file);
        
        Object.keys(metadata).forEach(key => {
            formData.append(key, metadata[key]);
        });

        return this.post(API_CONFIG.endpoints.uploadImage, formData);
    }

    async uploadVideo(file, metadata = {}) {
        const formData = new FormData();
        formData.append('video', file);
        
        Object.keys(metadata).forEach(key => {
            formData.append(key, metadata[key]);
        });

        return this.post(API_CONFIG.endpoints.uploadVideo, formData);
    }

    async deleteMedia(id) {
        return this.delete(API_CONFIG.endpoints.mediaItem(id));
    }
}

// Instance globale
const api = new ApiService();
console.log('✅ API.js chargé');