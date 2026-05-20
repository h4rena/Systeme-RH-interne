<?php /** @var int $employeeCount */ ?>
<?php /** @var int $departmentCount */ ?>
<?php /** @var int $requestCount */ ?>
<?php /** @var int $pendingCount */ ?>
<?php /** @var int $approvedCount */ ?>
<?php /** @var array{labels: array<int, string>, data: array<int, int>} $monthlyLeaveChart */ ?>
<?php /** @var array{labels: array<int, string>, data: array<int, int>} $weekdayLeaveChart */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('head') ?>
<style>
    .admin-stats {
        grid-template-columns: repeat(5, minmax(0, 1fr));
    }

    .dashboard-charts {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        margin-top: 18px;
    }

    .chart-card {
        min-height: 380px;
    }

    .chart-card-monthly {
        min-height: 460px;
    }

    .chart-card-weekly {
        min-height: 360px;
    }

    .chart-card canvas {
        width: 100% !important;
    }

    #monthlyLeavesChart {
        height: 360px !important;
    }

    #weekdayLeavesChart {
        height: 280px !important;
    }

    .chart-kicker {
        font-size: .78rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--muted);
    }

    @media (max-width: 1400px) {
        .admin-stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 1100px) {
        .admin-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .chart-card-monthly {
            min-height: 420px;
        }

        #monthlyLeavesChart {
            height: 320px !important;
        }
    }

    @media (max-width: 700px) {
        .admin-stats {
            grid-template-columns: 1fr;
        }

        .chart-card,
        .chart-card-monthly,
        .chart-card-weekly {
            min-height: 320px;
        }

        #monthlyLeavesChart,
        #weekdayLeavesChart {
            height: 240px !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stats admin-stats mb-4">
    <div class="stat"><div class="label">Employés</div><div class="value"><?= esc((string) $employeeCount) ?></div></div>
    <div class="stat"><div class="label">Départements</div><div class="value"><?= esc((string) $departmentCount) ?></div></div>
    <div class="stat"><div class="label">Demandes</div><div class="value"><?= esc((string) $requestCount) ?></div></div>
    <div class="stat"><div class="label">En attente</div><div class="value"><?= esc((string) $pendingCount) ?></div></div>
    <div class="stat"><div class="label">Approuvées</div><div class="value"><?= esc((string) $approvedCount) ?></div></div>
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

<section class="card-x panel mt-4">
    <div class="section-title mb-2">Statistiques des congés</div>
    <p class="text-muted mb-3">Les graphiques ci-dessous fonctionnent hors ligne avec la version locale de Chart.js servie depuis <span class="chart-kicker">public/assets/vendor</span>.</p>
    <div class="dashboard-charts">
        <div class="card-x panel chart-card chart-card-monthly">
            <div class="chart-kicker mb-1">Vue mensuelle</div>
            <div class="fw-bold mb-1">Nombre de congés par mois</div>
            <div class="small text-muted mb-3">Répartition des congés selon leur date de début sur l’année.</div>
            <canvas id="monthlyLeavesChart"></canvas>
        </div>
        <div class="card-x panel chart-card chart-card-weekly">
            <div class="chart-kicker mb-1">Vue hebdomadaire</div>
            <div class="fw-bold mb-1">Jours de congé demandés</div>
            <div class="small text-muted mb-3">Répartition selon le jour de début du congé.</div>
            <canvas id="weekdayLeavesChart"></canvas>
        </div>
    </div>
</section>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/vendor/chart.umd.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const monthlyCtx = document.getElementById('monthlyLeavesChart');
    const weekdayCtx = document.getElementById('weekdayLeavesChart');

    if (!monthlyCtx || !weekdayCtx || typeof Chart === 'undefined') {
        return;
    }

    const monthlyLabels = <?= json_encode($monthlyLeaveChart['labels'], JSON_UNESCAPED_UNICODE) ?>;
    const monthlyData = <?= json_encode($monthlyLeaveChart['data'], JSON_UNESCAPED_UNICODE) ?>;
    const weekdayLabels = <?= json_encode($weekdayLeaveChart['labels'], JSON_UNESCAPED_UNICODE) ?>;
    const weekdayData = <?= json_encode($weekdayLeaveChart['data'], JSON_UNESCAPED_UNICODE) ?>;

    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Demandes',
                data: monthlyData,
                backgroundColor: '#3c7a53',
                borderRadius: 12,
                borderSkipped: false,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                    },
                    grid: {
                        color: 'rgba(19, 32, 26, 0.08)',
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                },
            },
        },
    });

    new Chart(weekdayCtx, {
        type: 'bar',
        data: {
            labels: weekdayLabels,
            datasets: [{
                label: 'Jours',
                data: weekdayData,
                backgroundColor: [
                    '#264d35',
                    '#3c7a53',
                    '#5d9f6f',
                    '#7fbe8d',
                    '#9fd0aa',
                    '#bfdcc6',
                    '#dceadb',
                ],
                borderRadius: 12,
                borderSkipped: false,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                    },
                    grid: {
                        color: 'rgba(19, 32, 26, 0.08)',
                    },
                },
                y: {
                    grid: {
                        display: false,
                    },
                },
            },
        },
    });
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>