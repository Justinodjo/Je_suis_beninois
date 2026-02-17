// auth.js - Gestion de l'authentification
// VERSION CORRIGÉE - 2026-02-09

class AuthManager {
    constructor() {
        this.initElements();
    }

    initElements() {
        this.loginPage = document.getElementById('loginPage');
        this.dashboardContainer = document.getElementById('dashboardContainer');
        this.loginForm = document.getElementById('loginForm');
        this.loginEmail = document.getElementById('loginEmail');
        this.loginPassword = document.getElementById('loginPassword');
        this.loginError = document.getElementById('loginError');
        this.loginBtnText = document.getElementById('loginBtnText');
        this.loginSpinner = document.getElementById('loginSpinner');
    }

    init() {
        this.checkAuthentication();
        this.attachEventListeners();
        this.subscribeToState();
    }

    attachEventListeners() {
        if (this.loginForm) {
            this.loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }
    }

    subscribeToState() {
        state.subscribe('auth', () => {
            if (state.auth.isAuthenticated) {
                this.showDashboard();
            } else {
                this.showLogin();
            }
        });
    }

    checkAuthentication() {
        if (state.auth.token) {
            state.auth.isAuthenticated = true;
            this.showDashboard();
            if (window.app) {
                window.app.loadInitialData();
            }
        } else {
            this.showLogin();
        }
    }

    async handleLogin(e) {
        e.preventDefault();
        
        const email = this.loginEmail ? this.loginEmail.value.trim() : '';
        const password = this.loginPassword ? this.loginPassword.value.trim() : '';
        
        if (!email || !password) {
            this.showLoginError('Veuillez remplir tous les champs');
            return;
        }
        
        this.setLoginLoading(true);
        this.hideLoginError();
        
        try {
            await api.login(email, password);
            
            if (window.app) {
                await window.app.loadInitialData();
            }
        } catch (error) {
            this.showLoginError(error.message || 'Identifiants incorrects');
        } finally {
            this.setLoginLoading(false);
        }
    }

    showLogin() {
        if (this.loginPage) {
            this.loginPage.style.display = 'flex';
        }
        if (this.dashboardContainer) {
            this.dashboardContainer.style.display = 'none';
        }
    }

    showDashboard() {
        if (this.loginPage) {
            this.loginPage.style.display = 'none';
        }
        if (this.dashboardContainer) {
            this.dashboardContainer.style.display = 'flex';
        }
    }

    setLoginLoading(isLoading) {
        if (this.loginBtnText) {
            this.loginBtnText.textContent = isLoading ? 'Connexion...' : 'Se connecter';
        }
        if (this.loginSpinner) {
            this.loginSpinner.style.display = isLoading ? 'inline-block' : 'none';
        }
    }

    showLoginError(message) {
        if (this.loginError) {
            this.loginError.textContent = message;
            this.loginError.style.display = 'block';
        }
    }

    hideLoginError() {
        if (this.loginError) {
            this.loginError.style.display = 'none';
        }
    }
}

// Instance globale
const authManager = new AuthManager();
console.log('✅ Auth.js chargé');