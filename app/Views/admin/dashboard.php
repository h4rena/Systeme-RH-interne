<?php /** @var int $employeeCount */ ?>
<?php /** @var int $departmentCount */ ?>
<?php /** @var int $requestCount */ ?>
<?php /** @var int $pendingCount */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="stats mb-4">
    <div class="stat"><div class="label">Employés</div><div class="value"><?= esc((string) $employeeCount) ?></div></div>
    <div class="stat"><div class="label">Départements</div><div class="value"><?= esc((string) $departmentCount) ?></div></div>
    <div class="stat"><div class="label">Demandes</div><div class="value"><?= esc((string) $requestCount) ?></div></div>
    <div class="stat"><div class="label">En attente</div><div class="value"><?= esc((string) $pendingCount) ?></div></div>
</div>

<div class="grid-2">
    <section class="card-x panel">
        <div class="section-title">Pilotage</div>
        <p class="mb-2 text-muted">L’administration gère les comptes, les départements et les types de congé.</p>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-forest" href="<?= site_url('admin/employes') ?>">Gérer les employés</a>
            <a class="btn btn-lightx" href="<?= site_url('admin/departements') ?>">Départements</a>
            <a class="btn btn-lightx" href="<?= site_url('admin/types-conge') ?>">Types de congé</a>
        </div>
    </section>
    <aside class="card-x panel">
        <div class="section-title">Sécurité</div>
        <div class="small text-muted">Les nouvelles inscriptions reçoivent le rôle employee par défaut et les mots de passe sont hashés avant insertion.</div>
    </aside>
</div>
<?= $this->endSection() ?>