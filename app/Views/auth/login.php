<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<?php /** @var string|null $message */ ?>
<div class="title">Connexion</div>
<div class="subtitle">Accédez à votre espace personnel selon votre rôle.</div>

<?php if ($message = session()->getFlashdata('success')): ?>
    <div class="alert alert-success rounded-4"><?= esc((string) $message) ?></div>
<?php endif; ?>
<?php if ($message = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger rounded-4"><?= esc((string) $message) ?></div>
<?php endif; ?>

<form method="post" action="<?= site_url('login') ?>" class="mt-3">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label">Adresse email</label>
        <input type="email" name="email" class="form-control" placeholder="vous@techmada.mg" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Mot de passe</label>
        <div class="input-group password-toggle">
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Afficher le mot de passe" aria-pressed="false">
                <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
            </button>
        </div>
    </div>
    <button type="submit" class="btn btn-forest">Se connecter</button>
</form>
<?= $this->endSection() ?>