<?php
/** @var array<int, array{label: string, uri: string, key: string, icon: string}> $sidebarItems */
$pageTitle = $pageTitle ?? 'TechMada RH';
$activeMenu = $activeMenu ?? '';
$role = (string) session()->get('role');
$userName = (string) (session()->get('user_name') ?: session()->get('user_email') ?: 'Utilisateur');

$menuItems = [
    'employee' => [
        ['label' => 'Tableau de bord', 'uri' => 'employee/dashboard', 'key' => 'employee-dashboard', 'icon' => 'speedometer2'],
        ['label' => 'Nouvelle demande', 'uri' => 'employee/conges/create', 'key' => 'employee-create', 'icon' => 'plus-circle'],
        ['label' => 'Mes demandes', 'uri' => 'employee/conges', 'key' => 'employee-requests', 'icon' => 'calendar3'],
    ],
    'rh' => [
        ['label' => 'Validation', 'uri' => 'rh/dashboard', 'key' => 'rh-dashboard', 'icon' => 'inbox'],
        ['label' => 'Toutes les demandes', 'uri' => 'rh/conges', 'key' => 'rh-requests', 'icon' => 'archive'],
    ],
    'admin' => [
        ['label' => 'Vue d’ensemble', 'uri' => 'admin/dashboard', 'key' => 'admin-dashboard', 'icon' => 'speedometer2'],
        ['label' => 'Employés', 'uri' => 'admin/employes', 'key' => 'admin-employees', 'icon' => 'people'],
        ['label' => 'Départements', 'uri' => 'admin/departements', 'key' => 'admin-departments', 'icon' => 'building'],
        ['label' => 'Types de congé', 'uri' => 'admin/types-conge', 'key' => 'admin-types', 'icon' => 'tags'],
    ],
];

$sidebarItems = $menuItems[$role] ?? [];
?><!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageTitle) ?> | TechMada RH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <?= $this->renderSection('head') ?>
    <style>
        :root{--ink:#13201a;--forest:#264d35;--forest2:#3c7a53;--leaf:#7fbe8d;--cream:#f4f1e8;--line:#d9e2db;--muted:#6b7c71;--card:#ffffff;--shadow:0 18px 40px rgba(19,32,26,.08)}
        *{box-sizing:border-box}
        body{margin:0;font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:linear-gradient(180deg,#f8f5ee 0%,#f4f7f3 100%);color:var(--ink)}
        .app-shell{display:grid;grid-template-columns:280px minmax(0,1fr);min-height:100vh}
        .sidebar{background:linear-gradient(180deg,var(--ink),#0f1613 100%);color:#fff;padding:24px 18px;display:flex;flex-direction:column;gap:20px;position:sticky;top:0;height:100vh}
        .brand-row{display:flex;align-items:center;gap:12px}
        .brand-logo{width:44px;height:44px;border-radius:14px;display:grid;place-items:center;background:linear-gradient(135deg,var(--forest2),#7db58a);color:#fff;font-size:1.1rem}
        .brand-title{font-family:Georgia,serif;font-size:1.2rem;line-height:1.1;margin:0}
        .brand-sub{font-size:.75rem;opacity:.7;margin-top:4px;letter-spacing:.08em;text-transform:uppercase}
        .menu-label{font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.35);margin:6px 0 0}
        .menu{display:grid;gap:6px}
        .menu a{display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:14px;color:rgba(255,255,255,.75);text-decoration:none;background:transparent;transition:.18s ease}
        .menu a:hover,.menu a.active{background:rgba(127,190,141,.14);color:#fff}
        .menu i{font-size:1rem}
        .sidebar-footer{margin-top:auto;padding-top:18px;border-top:1px solid rgba(255,255,255,.08)}
        .user-card{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:16px;background:rgba(255,255,255,.05)}
        .avatar{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--forest2),var(--leaf));font-weight:800;color:#fff;font-size:.85rem;flex-shrink:0}
        .user-name{font-weight:700;line-height:1.15}
        .user-role{font-size:.75rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em}
        .main{min-width:0}
        .topbar{height:76px;background:rgba(255,255,255,.82);backdrop-filter:blur(10px);border-bottom:1px solid rgba(217,226,219,.8);display:flex;align-items:center;justify-content:space-between;padding:0 28px;position:sticky;top:0;z-index:10}
        .topbar h1{font-size:1.1rem;font-weight:800;margin:0}
        .topbar .meta{font-size:.85rem;color:var(--muted)}
        .content{padding:28px}
        .card-x{background:var(--card);border:1px solid rgba(217,226,219,.8);border-radius:22px;box-shadow:var(--shadow)}
        .panel{padding:22px}
        .stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}
        .stat{padding:18px;border-radius:20px;background:linear-gradient(180deg,#fff,#fbfcfa);border:1px solid rgba(217,226,219,.8);box-shadow:var(--shadow)}
        .stat .label{font-size:.78rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em}
        .stat .value{font-size:1.8rem;font-weight:900;line-height:1.05;margin-top:10px}
        .grid-2{display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:18px}
        .grid-2.full{grid-template-columns:1fr}
        .section-title{font-size:1rem;font-weight:800;margin:0 0 16px}
        .table thead th{font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);border-bottom:1px solid var(--line)}
        .badge-soft{padding:6px 10px;border-radius:999px;font-weight:700;font-size:.74rem}
        .b-pending{background:#fff4d9;color:#a86a00}
        .b-approved{background:#e5f6eb;color:#1d6b38}
        .b-refused{background:#fde8e7;color:#b13b31}
        .b-cancelled{background:#ece9e2;color:#718074}
        .form-control,.form-select,.btn{border-radius:14px}
        .btn-forest{background:var(--forest);color:#fff;border:none}
        .btn-forest:hover{background:var(--forest2);color:#fff}
        .btn-lightx{background:#fff;border:1px solid var(--line);color:var(--ink)}
        .btn-lightx:hover{border-color:var(--forest2);color:var(--forest2)}
        .flash{border-radius:16px;padding:14px 16px;margin-bottom:18px;border:1px solid transparent}
        .flash-success{background:#eaf7ef;border-color:#b8e1c4;color:#1d6b38}
        .flash-error{background:#fdeceb;border-color:#f0bfba;color:#b13b31}
        .flash-info{background:#eef4fb;border-color:#c9dced;color:#1e4d79}
        @media (max-width: 1100px){.app-shell{grid-template-columns:1fr}.sidebar{position:relative;height:auto}.grid-2,.stats{grid-template-columns:1fr}.content{padding:18px}.topbar{padding:0 18px}}
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand-row">
            <div class="brand-logo"><i class="bi bi-briefcase"></i></div>
            <div>
                <p class="brand-title">TechMada RH</p>
                <div class="brand-sub"><?= esc($role ?: 'accès') ?></div>
            </div>
        </div>
        <div>
            <div class="menu-label">Navigation</div>
            <nav class="menu mt-3">
                <?php foreach ($sidebarItems as $item): ?>
                    <a href="<?= site_url($item['uri']) ?>" class="<?= $activeMenu === $item['key'] ? 'active' : '' ?>">
                        <i class="bi bi-<?= esc((string) $item['icon']) ?>"></i>
                        <span><?= esc((string) $item['label']) ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="avatar"><?= esc(strtoupper(substr($userName, 0, 2))) ?></div>
                <div>
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($role ?: 'invite') ?></div>
                </div>
            </div>
            <a class="btn btn-lightx w-100 mt-3" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Déconnexion</a>
        </div>
    </aside>
    <main class="main">
        <div class="topbar">
            <div>
                <h1><?= esc($pageTitle) ?></h1>
                <div class="meta">Connecté en tant que <?= esc($userName) ?></div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-lightx" href="<?= site_url('/') ?>"><i class="bi bi-house-door me-2"></i>Accueil</a>
            </div>
        </div>
        <div class="content">
            <?php if ($message = session()->getFlashdata('success')): ?>
                <div class="flash flash-success"><i class="bi bi-check-circle-fill me-2"></i><?= esc((string) $message) ?></div>
            <?php endif; ?>
            <?php if ($message = session()->getFlashdata('error')): ?>
                <div class="flash flash-error"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc((string) $message) ?></div>
            <?php endif; ?>
            <?php if ($message = session()->getFlashdata('info')): ?>
                <div class="flash flash-info"><i class="bi bi-info-circle-fill me-2"></i><?= esc((string) $message) ?></div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>
        </div>
    </main>
</div>
<?= $this->renderSection('scripts') ?>
</body>
</html>
