<?php /** @var array<int, array<string, mixed>> $types */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="grid-2 full">
    <section class="card-x panel">
        <div class="section-title">Nouveau type de congé</div>
        <form method="post" action="<?= site_url('admin/types-conge') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-5"><label class="form-label">Libellé</label><input name="libelle" class="form-control" required></div>
            <div class="col-md-3"><label class="form-label">Jours annuels</label><input type="number" min="0" name="jours_annuels" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Déductible</label><select name="deductible" class="form-select"><option value="1">Oui</option><option value="0">Non</option></select></div>
            <div class="col-12"><button class="btn btn-forest">Ajouter</button></div>
        </form>
    </section>
</div>

<div class="card-x panel mt-4">
    <div class="section-title">Types de congé</div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Libellé</th><th>Jours annuels</th><th>Déductible</th></tr></thead>
            <tbody>
            <?php foreach ($types as $type): ?>
                <tr>
                    <td><?= esc((string) $type['libelle']) ?></td>
                    <td><?= esc((string) $type['jours_annuels']) ?></td>
                    <td><?= (int) $type['deductible'] === 1 ? 'Oui' : 'Non' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>