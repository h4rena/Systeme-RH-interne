<?php /** @var array<int, array<string, mixed>> $requests */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card-x panel">
    <div class="section-title">Toutes les demandes</div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Employé</th><th>Type</th><th>Période</th><th>Jours</th><th>Statut</th></tr></thead>
            <tbody>
            <?php foreach ($requests as $request): ?>
                <?php $status = (string) ($request['statut'] ?? ''); ?>
                <tr>
                    <td><?= esc(trim((string) ($request['employee_prenom'] ?? '') . ' ' . (string) ($request['employee_nom'] ?? ''))) ?></td>
                    <td><?= esc((string) ($request['type_label'] ?? '—')) ?></td>
                    <td><?= esc((string) $request['date_debut']) ?> → <?= esc((string) $request['date_fin']) ?></td>
                    <td><?= esc((string) ($request['nb_jours'] ?? 0)) ?></td>
                    <td><span class="badge-soft <?= $status === 'approuve' ? 'b-approved' : ($status === 'refuse' ? 'b-refused' : ($status === 'annulee' ? 'b-cancelled' : 'b-pending')) ?>"><?= esc($status) ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($requests === []): ?>
                <tr><td colspan="5" class="text-muted">Aucune demande trouvée.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>