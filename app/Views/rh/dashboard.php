<?php /** @var int $pendingCount */ ?>
<?php /** @var int $approvedCount */ ?>
<?php /** @var int $refusedCount */ ?>
<?php /** @var int $currentYear */ ?>
<?php /** @var array<int, array<string, mixed>> $requests */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="stats mb-4">
    <div class="stat"><div class="label">Demandes en attente</div><div class="value"><?= esc((string) $pendingCount) ?></div><div class="text-muted">Année <?= esc((string) $currentYear) ?></div></div>
    <div class="stat"><div class="label">Demandes approuvées</div><div class="value"><?= esc((string) $approvedCount) ?></div><div class="text-muted">Année <?= esc((string) $currentYear) ?></div></div>
    <div class="stat"><div class="label">Demandes refusées</div><div class="value"><?= esc((string) $refusedCount) ?></div><div class="text-muted">Année <?= esc((string) $currentYear) ?></div></div>
    <div class="stat"><div class="label">Contexte</div><div class="value"><?= esc((string) $currentYear) ?></div><div class="text-muted">Données base RH.db</div></div>
</div>

<div class="card-x panel">
    <div class="section-title">Demandes en attente (<?= esc((string) $currentYear) ?>)</div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Employé</th><th>Département</th><th>Type</th><th>Période</th><th>Jours</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= esc(trim((string) ($request['employee_prenom'] ?? '') . ' ' . (string) ($request['employee_nom'] ?? ''))) ?></td>
                    <td><?= esc((string) ($request['departement_nom'] ?? '—')) ?></td>
                    <td><?= esc((string) ($request['type_label'] ?? '—')) ?></td>
                    <td><?= esc((string) $request['date_debut']) ?> → <?= esc((string) $request['date_fin']) ?></td>
                    <td><?= esc((string) ($request['nb_jours'] ?? 0)) ?></td>
                    <td class="d-flex gap-2 flex-wrap">
                        <form method="post" action="<?= site_url('rh/conges/' . $request['id'] . '/approve') ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-success">Approuver</button>
                        </form>
                        <form method="post" action="<?= site_url('rh/conges/' . $request['id'] . '/refuse') ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger">Refuser</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($requests === []): ?>
                <tr><td colspan="6" class="text-muted">Aucune demande en attente.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>