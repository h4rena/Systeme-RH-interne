<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
/** @var array<int, array<string, mixed>> $types */
/** @var array<int, array<string, mixed>> $balances */
/** @var int $currentYear */
?>
<div class="grid-2 full">
    <section class="card-x panel">
        <div class="section-title">Nouvelle demande de congé</div>
        <form method="post" action="<?= site_url('employee/conges') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label class="form-label">Type de congé</label>
                <select name="type_conge_id" class="form-select" required>
                    <option value="">Choisir</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= esc((string) $type['id']) ?>"><?= esc((string) $type['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Début</label>
                <input type="date" name="date_debut" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fin</label>
                <input type="date" name="date_fin" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Motif</label>
                <textarea name="motif" class="form-control" rows="4" required></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-forest">Soumettre la demande</button>
            </div>
        </form>
    </section>
</div>
<div class="card-x panel mt-4">
    <div class="section-title">Soldes actuels (<?= esc((string) $currentYear) ?>)</div>
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
                    <td><?= esc((string) $restant) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>