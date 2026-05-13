<?php
$pageTitle = $pageTitle ?? 'Connexion';
?><!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageTitle) ?> | TechMada RH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root{--ink:#13201a;--forest:#264d35;--forest2:#3c7a53;--leaf:#7fbe8d;--cream:#f5f1e8;--line:#d9e2db;--muted:#6c7d71;}
        body{margin:0;font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:radial-gradient(circle at top left,#173125 0,#0f1713 42%,#0b0f0d 100%);color:var(--ink);min-height:100vh;}
        .auth-shell{min-height:100vh;display:grid;place-items:center;padding:24px;}
        .auth-card{width:min(1040px,100%);display:grid;grid-template-columns:1.1fr .9fr;background:#fff;border-radius:28px;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,.25)}
        .auth-aside{padding:40px;background:linear-gradient(160deg,var(--forest),#1c3027 70%);color:#fff;position:relative;overflow:hidden}
        .auth-aside:before{content:"";position:absolute;inset:auto -80px -120px auto;width:280px;height:280px;border-radius:50%;background:rgba(127,190,141,.18)}
        .brand{font-family:Georgia,serif;font-size:2rem;font-weight:700;letter-spacing:-.03em;margin:0}
        .brand span{display:block;font-size:.82rem;font-family:inherit;opacity:.75;margin-top:.35rem}
        .auth-copy{margin-top:40px;max-width:30rem;color:rgba(255,255,255,.82);line-height:1.75}
        .auth-copy strong{display:block;color:#fff;font-size:1.35rem;line-height:1.2;margin-bottom:12px}
        .demo-list{margin-top:34px;display:grid;gap:10px}
        .demo-item{padding:12px 14px;border:1px solid rgba(255,255,255,.14);border-radius:16px;background:rgba(255,255,255,.06)}
        .demo-item .role{font-weight:700}
        .demo-item .cred{font-size:.85rem;opacity:.72}
        .auth-body{padding:44px}
        .title{font-size:1.6rem;font-weight:800;letter-spacing:-.03em;margin-bottom:4px}
        .subtitle{color:var(--muted);margin-bottom:28px}
        .form-label{font-size:.85rem;font-weight:700;color:#23342b}
        .form-control{border-radius:14px;padding:12px 14px;border:1px solid var(--line)}
        .form-control:focus{border-color:var(--forest2);box-shadow:0 0 0 .2rem rgba(60,122,83,.14)}
        .password-toggle .form-control{border-top-right-radius:0;border-bottom-right-radius:0}
        .password-toggle .btn{border-color:var(--line);border-top-right-radius:14px;border-bottom-right-radius:14px}
        .btn-forest{background:var(--forest);border:none;color:#fff;border-radius:14px;padding:12px 18px;font-weight:700;width:100%}
        .btn-forest:hover{background:var(--forest2);color:#fff}
        @media (max-width: 920px){.auth-card{grid-template-columns:1fr}.auth-aside{display:none}.auth-body{padding:28px}}
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <aside class="auth-aside">
            <p class="brand">TechMada RH<span>Gestion des congés et des équipes</span></p>
            <div class="auth-copy">
                <strong>Accès sécurisé au portail RH.</strong>
                Chaque compte doit se connecter avant d’accéder aux tableaux de bord et aux formulaires.
            </div>
            <div class="demo-list">
                <div class="demo-item"><div class="role">Administrateur</div><div class="cred">admin@techmada.mg / admin123</div></div>
                <div class="demo-item"><div class="role">Responsable RH</div><div class="cred">rh@techmada.mg / rh123</div></div>
                <div class="demo-item"><div class="role">Employé</div><div class="cred">employe@techmada.mg / emp123</div></div>
            </div>
        </aside>
        <main class="auth-body">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>
<script>
    const passwordInput = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');

    if (passwordInput && togglePasswordButton && togglePasswordIcon) {
        togglePasswordButton.addEventListener('click', () => {
            const isVisible = passwordInput.type === 'text';
            passwordInput.type = isVisible ? 'password' : 'text';
            togglePasswordIcon.classList.toggle('bi-eye', !isVisible);
            togglePasswordIcon.classList.toggle('bi-eye-slash', isVisible);
            togglePasswordButton.setAttribute('aria-label', isVisible ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
            togglePasswordButton.setAttribute('aria-pressed', String(!isVisible));
        });
    }
</script>
</body>
</html>
