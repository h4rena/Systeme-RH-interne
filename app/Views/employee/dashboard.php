<?php /** @var array<string, mixed>|null $employee */ ?>
<?php /** @var array<int, array<string, mixed>> $balances */ ?>
<?php /** @var array<int, array<string, mixed>> $requests */ ?>
<?php /** @var int $currentYear */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="stats mb-4">
    <div class="stat"><div class="label">Employé</div><div class="value"><?= esc((string) ($employee['prenom'] ?? '—')) ?></div><div class="text-muted">Département: <?= esc((string) ($employee['departement_nom'] ?? 'Non renseigné')) ?></div></div>
    <div class="stat"><div class="label">Demandes (année)</div><div class="value"><?= count($requests) ?></div><div class="text-muted">Année <?= esc((string) $currentYear) ?></div></div>
    <div class="stat"><div class="label">Soldes suivis</div><div class="value"><?= count($balances) ?></div><div class="text-muted">Soldes <?= esc((string) $currentYear) ?></div></div>
    <div class="stat"><div class="label">Contexte</div><div class="value"><?= esc((string) $currentYear) ?></div><div class="text-muted">Données base RH.db</div></div>
</div>

<div class="grid-2">
    <section class="card-x panel">
        <div class="section-title">Mes soldes</div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Type</th><th>Attribués</th><th>Pris</th><th>Restant</th></tr></thead>
                <tbody>
                    <?php foreach ($balances as $balance): ?>
                        <?php $restant = (int) $balance['jours_attribues'] - (int) $balance['jours_pris']; ?>
                        <tr>
                            <td><?= esc((string) ($balance['libelle'] ?? '—')) ?></td>
                            <td><?= esc((string) ($balance['jours_attribues'] ?? 0)) ?></td>
                            <td><?= esc((string) ($balance['jours_pris'] ?? 0)) ?></td>
                            <td><span class="badge-soft <?= $restant <= 0 ? 'b-refused' : 'b-approved' ?>"><?= esc((string) $restant) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($balances === []): ?>
                        <tr><td colspan="4" class="text-muted">Aucun solde disponible.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <aside class="card-x panel">
        <div class="section-title">Raccourcis</div>
        <div class="d-grid gap-2">
            <a class="btn btn-forest" href="<?= site_url('employee/conges/create') ?>">Nouvelle demande</a>
            <a class="btn btn-lightx" href="<?= site_url('employee/conges') ?>">Voir mes demandes</a>
        </div>
        <div class="mt-4 small text-muted">Les demandes validées par le RH mettent à jour le solde automatiquement.</div>
    </aside>
</div>

<div class="card-x panel mt-4">
    <div class="section-title">Dernières demandes (<?= esc((string) $currentYear) ?>)</div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Période</th><th>Type</th><th>Jours</th><th>Statut</th></tr></thead>
            <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= esc((string) $request['date_debut']) ?> → <?= esc((string) $request['date_fin']) ?></td>
                    <td><?= esc((string) ($request['type_label'] ?? '—')) ?></td>
                    <td><?= esc((string) ($request['nb_jours'] ?? 0)) ?></td>
                    <td>
                        <?php $status = (string) ($request['statut'] ?? ''); ?>
                        <span class="badge-soft <?= $status === 'approuve' ? 'b-approved' : ($status === 'refuse' ? 'b-refused' : 'b-pending') ?>"><?= esc((string) $status) ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($requests === []): ?>
                <tr><td colspan="4" class="text-muted">Aucune demande trouvée.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>