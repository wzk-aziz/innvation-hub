<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Connexion</h1>
            <p>Accédez à votre espace de gestion des idées</p>
        </div>
        
        <form method="POST" action="/login" id="loginForm" class="auth-form">
            <input type="hidden" name="<?= $csrfTokenName ?>" value="<?= $csrfToken ?>">
            
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? '') ?>"
                    required
                />
                <div class="invalid-feedback" id="email-error"></div>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control"
                        required
                    />
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <span id="password-toggle-icon">👁️</span>
                    </button>
                </div>
                <div class="invalid-feedback" id="password-error"></div>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember">
                    <span class="checkmark"></span>
                    Se souvenir de moi
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block" id="loginBtn">
                <span id="loginBtnText">Se connecter</span>
                <div id="loginSpinner" class="spinner" style="display: none;"></div>
            </button>
        </form>
        
        <div class="auth-footer">
            <div class="demo-credentials">
                <h4>Comptes de démonstration :</h4>
                <div class="demo-accounts">
                    <div class="demo-account">
                        <strong>👨‍💼 Administrateur :</strong><br>
                        <span>admin@company.com</span> / <span>admin123</span>
                        <button type="button" class="btn-demo" onclick="fillCredentials('admin@company.com', 'admin123')">Utiliser</button>
                    </div>
                    <div class="demo-account">
                        <strong>👨‍💻 Salarié :</strong><br>
                        <span>marie.dupont@company.com</span> / <span>password123</span>
                        <button type="button" class="btn-demo" onclick="fillCredentials('marie.dupont@company.com', 'password123')">Utiliser</button>
                    </div>
                    <div class="demo-account">
                        <strong>👨‍🔬 Évaluateur :</strong><br>
                        <span>sophie.bernard@company.com</span> / <span>password123</span>
                        <button type="button" class="btn-demo" onclick="fillCredentials('sophie.bernard@company.com', 'password123')">Utiliser</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 1rem;
}

.auth-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 500px;
    overflow: hidden;
}

.auth-header {
    text-align: center;
    padding: 2rem 2rem 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.auth-header h1 {
    margin: 0 0 0.5rem;
    font-size: 2rem;
    font-weight: 600;
}

.auth-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 1rem;
}

.auth-form {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: background-color 0.2s ease;
}

.password-toggle:hover {
    background-color: #f8f9fa;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.9rem;
    color: #666;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 0.5rem;
    transform: scale(1.1);
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-block {
    width: 100%;
}

.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid #ffffff;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.invalid-feedback.show {
    display: block;
}

.auth-footer {
    background: #f8f9fa;
    padding: 1.5rem 2rem;
    border-top: 1px solid #e9ecef;
}

.demo-credentials h4 {
    margin: 0 0 1rem;
    color: #495057;
    font-size: 1rem;
    text-align: center;
}

.demo-accounts {
    display: grid;
    gap: 1rem;
}

.demo-account {
    background: white;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
}

.demo-account span {
    font-family: 'Courier New', monospace;
    background: #f8f9fa;
    padding: 0.125rem 0.25rem;
    border-radius: 4px;
    font-size: 0.8rem;
}

.btn-demo {
    background: #28a745;
    color: white;
    border: none;
    padding: 0.375rem 0.75rem;
    border-radius: 4px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-demo:hover {
    background: #218838;
}

@media (max-width: 576px) {
    .auth-container {
        padding: 1rem;
    }
    
    .auth-form {
        padding: 1.5rem;
    }
    
    .auth-footer {
        padding: 1rem 1.5rem;
    }
    
    .demo-account {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
}
</style>

<script src="/assets/js/login.js"></script>
