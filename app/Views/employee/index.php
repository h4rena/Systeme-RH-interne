<?php /** @var array<int, array<string, mixed>> $requests */ ?>
<?php /** @var int $currentYear */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card-x panel">
    <div class="section-title">Mes demandes de congé (<?= esc((string) $currentYear) ?>)</div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Période</th><th>Type</th><th>Jours</th><th>Motif</th><th>Statut</th></tr></thead>
            <tbody>
            <?php foreach ($requests as $request): ?>
                <?php $status = (string) ($request['statut'] ?? ''); ?>
                <tr>
                    <td><?= esc((string) $request['date_debut']) ?> → <?= esc((string) $request['date_fin']) ?></td>
                    <td><?= esc((string) ($request['type_label'] ?? '—')) ?></td>
                    <td><?= esc((string) ($request['nb_jours'] ?? 0)) ?></td>
                    <td><?= esc((string) ($request['motif'] ?? '')) ?></td>
                    <td><span class="badge-soft <?= $status === 'approuve' ? 'b-approved' : ($status === 'refuse' ? 'b-refused' : 'b-pending') ?>"><?= esc($status) ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($requests === []): ?>
                <tr><td colspan="5" class="text-muted">Aucune demande enregistrée.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>