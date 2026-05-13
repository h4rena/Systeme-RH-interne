<?php /** @var array<int, array<string, mixed>> $departments */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="grid-2 full">
    <section class="card-x panel">
        <div class="section-title">Nouveau département</div>
        <form method="post" action="<?= site_url('admin/departements') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6"><label class="form-label">Nom</label><input name="nom" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Description</label><input name="description" class="form-control"></div>
            <div class="col-12"><button class="btn btn-forest">Ajouter</button></div>
        </form>
    </section>
</div>

<div class="card-x panel mt-4">
    <div class="section-title">Départements</div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>ID</th><th>Nom</th><th>Description</th></tr></thead>
            <tbody>
            <?php foreach ($departments as $department): ?>
                <tr>
                    <td><?= esc((string) ($department['id'] ?? '')) ?></td>
                    <td><?= esc((string) $department['nom']) ?></td>
                    <td><?= esc((string) $department['description']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>