<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification - Nebula</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Tab Styles */
        .auth-tabs {
            display: flex;
            gap: 0;
            margin-bottom: var(--spacing-lg);
            border-bottom: 2px solid var(--border-color);
        }

        .tab-button {
            flex: 1;
            padding: 1rem;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .tab-button:hover {
            color: var(--text-primary);
        }

        .tab-button.active {
            color: var(--accent-purple);
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--accent-purple);
        }

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-placeholder">N</div>
            <div class="logo-text">Nebula</div>
        </div>

        <!-- Auth Card -->
        <div class="auth-card">
            <!-- Tabs -->
            <div class="auth-tabs">
                <button class="tab-button active" onclick="switchTab('login')">Se connecter</button>
                <button class="tab-button" onclick="switchTab('register')">Créer un compte</button>
            </div>

            <!-- Login Tab -->
            <div id="login-tab" class="tab-content active">
                <form class="auth-form" action="#" method="POST">
                    <!-- Email Input -->
                    <div class="input-group">
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            <input 
                                type="email" 
                                id="login-email" 
                                name="email" 
                                placeholder="E-mail" 
                                required
                                autocomplete="email"
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="input-group">
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <input 
                                type="password" 
                                id="login-password" 
                                name="password" 
                                placeholder="Mot de passe" 
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('login-password')">
                                <svg id="eye-icon-login-password" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Forgot Password -->
                    <div class="forgot-password">
                        <a href="#">Oublier le mot de passe?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">Connecter</button>
                </form>
            </div>

            <!-- Register Tab -->
            <div id="register-tab" class="tab-content">
                <form class="auth-form" action="#" method="POST">
                    <!-- Email Input -->
                    <div class="input-group">
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            <input 
                                type="email" 
                                id="register-email" 
                                name="email" 
                                placeholder="E-mail" 
                                required
                                autocomplete="email"
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="input-group">
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <input 
                                type="password" 
                                id="register-password" 
                                name="password" 
                                placeholder="Mot de passe" 
                                required
                                autocomplete="new-password"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('register-password')">
                                <svg id="eye-icon-register-password" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="input-group">
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <input 
                                type="password" 
                                id="register-confirm-password" 
                                name="confirm_password" 
                                placeholder="Confirmer le mot de passe" 
                                required
                                autocomplete="new-password"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('register-confirm-password')">
                                <svg id="eye-icon-register-confirm-password" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">Créer le compte</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Activate button
            event.target.classList.add('active');
        }

        // Password Toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('eye-icon-' + inputId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
</body>
</html>
