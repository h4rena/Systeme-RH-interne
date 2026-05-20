<?php /** @var array<string, mixed>|null $employee */ ?>
<?php /** @var array<int, array<string, mixed>> $balances */ ?>
<?php /** @var array<int, array<string, mixed>> $requests */ ?>
<?php /** @var int $requestCountYear */ ?>
<?php /** @var array{labels: array<int, string>, data: array<int, int>} $leaveTypeChart */ ?>
<?php /** @var array<int, array<string, mixed>> $calendarEvents */ ?>
<?php /** @var int $currentYear */ ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('head') ?>
<style>
    .employee-analytics {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 18px;
        margin-top: 18px;
    }

    .calendar-card,
    .chart-card {
        min-height: 360px;
    }

    #employeeCalendar {
        min-height: 300px;
    }

    .chart-wrap {
        position: relative;
        min-height: 260px;
        height: clamp(260px, 38vh, 380px);
    }

    #leaveTypeChart {
        width: 100% !important;
        height: 100% !important;
    }

    @media (max-width: 1100px) {
        .employee-analytics {
            grid-template-columns: 1fr;
        }

        .calendar-card,
        .chart-card {
            min-height: 320px;
        }
    }

    @media (max-width: 768px) {
        .chart-wrap {
            height: clamp(240px, 44vh, 340px);
        }

        #employeeCalendar .fc .fc-toolbar {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        #employeeCalendar .fc .fc-toolbar-chunk {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        #employeeCalendar .fc .fc-button {
            padding: .25rem .55rem;
            font-size: .78rem;
        }

        #employeeCalendar .fc .fc-toolbar-title {
            font-size: 1rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stats mb-4">
    <div class="stat"><div class="label">Employé</div><div class="value"><?= esc((string) ($employee['prenom'] ?? '—')) ?></div><div class="text-muted">Département: <?= esc((string) ($employee['departement_nom'] ?? 'Non renseigné')) ?></div></div>
    <div class="stat"><div class="label">Demandes (année)</div><div class="value"><?= esc((string) $requestCountYear) ?></div><div class="text-muted">Année <?= esc((string) $currentYear) ?></div></div>
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

<section class="card-x panel mt-4">
    <div class="section-title mb-2">Historique & statistiques</div>
    <p class="text-muted mb-3">Visualisez vos congés sous forme de calendrier interactif et le nombre total de demandes par type.</p>
    <div class="employee-analytics">
        <div class="card-x panel calendar-card">
            <div class="section-title">Vue calendrier</div>
            <div class="small text-muted mb-3">Affichage hebdomadaire interactif des congés.</div>
            <div id="employeeCalendar"></div>
        </div>
        <div class="card-x panel chart-card">
            <div class="section-title">Demandes par type</div>
            <div class="small text-muted mb-3">Nombre total de demandes de congé, par type.</div>
            <div class="chart-wrap">
                <canvas id="leaveTypeChart"></canvas>
            </div>
        </div>
    </div>
</section>

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

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/vendor/fullcalendar/index.global.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/chart.umd.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('employeeCalendar');
    const chartEl = document.getElementById('leaveTypeChart');

    if (calendarEl && typeof FullCalendar !== 'undefined') {
        const calendarEvents = <?= json_encode($calendarEvents, JSON_UNESCAPED_UNICODE) ?>;

        const calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'fr',
            initialView: 'timeGridWeek',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            events: calendarEvents,
        });

        calendar.render();
    }

    if (chartEl && typeof Chart !== 'undefined') {
        const chartLabels = <?= json_encode($leaveTypeChart['labels'], JSON_UNESCAPED_UNICODE) ?>;
        const chartData = <?= json_encode($leaveTypeChart['data'], JSON_UNESCAPED_UNICODE) ?>;
        const isMobile = window.matchMedia('(max-width: 768px)').matches;
        const tickRotation = isMobile ? 40 : 0;

        new Chart(chartEl, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Demandes',
                    data: chartData,
                    backgroundColor: ['#264d35', '#3c7a53', '#5d9f6f', '#7fbe8d', '#9fd0aa', '#bfdcc6'],
                    borderRadius: 10,
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
                    x: {
                        ticks: {
                            maxRotation: tickRotation,
                            minRotation: tickRotation,
                        },
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                        grid: {
                            color: 'rgba(19, 32, 26, 0.08)',
                        },
                    },
                },
            },
        });
    }
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>