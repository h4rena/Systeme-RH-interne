<?php /** @var array<int, array<string, mixed>> $departments */ ?>
<?php /** @var array<int, array<string, mixed>> $employees */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="grid-2 full">
    <section class="card-x panel">
        <div class="section-title">Nouvel employé</div>
        <form method="post" action="<?= site_url('admin/employes') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6"><label class="form-label">Nom</label><input name="nom" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Prénom</label><input name="prenom" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Mot de passe</label><input type="password" name="password" class="form-control" required></div>
            <div class="col-md-6">
                <label class="form-label">Département</label>
                <select name="departement_id" class="form-select">
                    <option value="">Choisir</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= esc((string) $department['id']) ?>"><?= esc((string) $department['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Date d'embauche</label><input type="date" name="date_embauche" class="form-control" value="<?= esc(date('Y-m-d')) ?>"></div>
            <div class="col-md-3">
                <label class="form-label">Rôle</label>
                <select name="role" class="form-select">
                    <option value="employee" selected>Employee</option>
                    <option value="rh">RH</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-12"><button class="btn btn-forest">Créer le compte</button></div>
        </form>
    </section>
</div>

<div class="card-x panel mt-4">
    <div class="section-title">Liste des employés</div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th><th>Département</th><th>Date embauche</th><th>Actif</th></tr></thead>
            <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= esc((string) $employee['id']) ?></td>
                    <td><?= esc((string) $employee['nom']) ?></td>
                    <td><?= esc((string) $employee['prenom']) ?></td>
                    <td><?= esc((string) $employee['email']) ?></td>
                    <td><?= esc(ucfirst((string) $employee['role'])) ?></td>
                    <td><?= esc((string) ($employee['departement_nom'] ?? '—')) ?></td>
                    <td><?= esc((string) ($employee['date_embauche'] ?? '—')) ?></td>
                    <td><?= (int) $employee['actif'] === 1 ? 'Oui' : 'Non' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>