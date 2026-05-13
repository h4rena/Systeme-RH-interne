<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RhSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = OFF');

        $this->db->table('conges')->truncate();
        $this->db->table('soldes')->truncate();
        $this->db->table('employees')->truncate();
        $this->db->table('types_conge')->truncate();
        $this->db->table('departements')->truncate();

        $this->db->query("DELETE FROM sqlite_sequence WHERE name IN ('conges','soldes','employees','types_conge','departements')");

        $this->db->query('PRAGMA foreign_keys = ON');

        $this->db->table('departements')->insertBatch([
            ['id' => 1, 'nom' => 'Informatique', 'description' => 'Infrastructure, applications et support interne'],
            ['id' => 2, 'nom' => 'Ressources humaines', 'description' => 'Gestion des equipes et des conges'],
            ['id' => 3, 'nom' => 'Direction', 'description' => 'Pilotage operationnel et administratif'],
        ]);

        $this->db->table('employees')->insertBatch([
            ['id' => 1, 'nom' => 'Mamy', 'prenom' => 'Administrateur', 'email' => 'admin@techmada.mg', 'password' => '$2y$10$rR9fnHiiiQLDgqtDQWvBN.EwP.oqfSf54N4Jl9hIv.5lvs6L273Pi', 'role' => 'admin', 'departement_id' => 3, 'date_embauche' => '2023-01-10', 'actif' => 1],
            ['id' => 2, 'nom' => 'Rabe', 'prenom' => 'Marie', 'email' => 'rh@techmada.mg', 'password' => '$2y$10$l.ZHpNE6zFSrNlYrkocx0eOHnuGhkkxtJ7uHdtxvlMzdV0Ap0gxOq', 'role' => 'rh', 'departement_id' => 2, 'date_embauche' => '2023-03-15', 'actif' => 1],
            ['id' => 3, 'nom' => 'Rakoto', 'prenom' => 'Soa', 'email' => 'employe@techmada.mg', 'password' => '$2y$10$QwkqeJrB3BR5JP6/9EKfB.HGe1o3gNpV9GReSB0/Ii2IVZKrD6iq.', 'role' => 'employee', 'departement_id' => 1, 'date_embauche' => '2024-02-05', 'actif' => 1],
            ['id' => 4, 'nom' => 'Rasoa', 'prenom' => 'Fetra', 'email' => 'fetra@techmada.mg', 'password' => '$2y$10$QwkqeJrB3BR5JP6/9EKfB.HGe1o3gNpV9GReSB0/Ii2IVZKrD6iq.', 'role' => 'employee', 'departement_id' => 1, 'date_embauche' => '2024-05-20', 'actif' => 1],
        ]);

        $this->db->table('types_conge')->insertBatch([
            ['id' => 1, 'libelle' => 'Conge annuel', 'jours_annuels' => 30, 'deductible' => 1],
            ['id' => 2, 'libelle' => 'Conge maladie', 'jours_annuels' => 10, 'deductible' => 1],
            ['id' => 3, 'libelle' => 'Conge special', 'jours_annuels' => 5, 'deductible' => 0],
            ['id' => 4, 'libelle' => 'Sans solde', 'jours_annuels' => 0, 'deductible' => 0],
        ]);

        $this->db->table('soldes')->insertBatch([
            ['id' => 1, 'employee_id' => 3, 'type_conge_id' => 1, 'annee' => 2025, 'jours_attribues' => 30, 'jours_pris' => 12],
            ['id' => 2, 'employee_id' => 3, 'type_conge_id' => 2, 'annee' => 2025, 'jours_attribues' => 10, 'jours_pris' => 2],
            ['id' => 3, 'employee_id' => 4, 'type_conge_id' => 1, 'annee' => 2025, 'jours_attribues' => 30, 'jours_pris' => 6],
            ['id' => 4, 'employee_id' => 4, 'type_conge_id' => 3, 'annee' => 2025, 'jours_attribues' => 5, 'jours_pris' => 1],
            ['id' => 5, 'employee_id' => 2, 'type_conge_id' => 1, 'annee' => 2025, 'jours_attribues' => 30, 'jours_pris' => 4],
            ['id' => 6, 'employee_id' => 3, 'type_conge_id' => 1, 'annee' => 2026, 'jours_attribues' => 30, 'jours_pris' => 8],
            ['id' => 7, 'employee_id' => 3, 'type_conge_id' => 2, 'annee' => 2026, 'jours_attribues' => 10, 'jours_pris' => 1],
            ['id' => 8, 'employee_id' => 3, 'type_conge_id' => 3, 'annee' => 2026, 'jours_attribues' => 5, 'jours_pris' => 0],
            ['id' => 9, 'employee_id' => 4, 'type_conge_id' => 1, 'annee' => 2026, 'jours_attribues' => 30, 'jours_pris' => 5],
            ['id' => 10, 'employee_id' => 4, 'type_conge_id' => 2, 'annee' => 2026, 'jours_attribues' => 10, 'jours_pris' => 0],
            ['id' => 11, 'employee_id' => 2, 'type_conge_id' => 1, 'annee' => 2026, 'jours_attribues' => 30, 'jours_pris' => 3],
            ['id' => 12, 'employee_id' => 2, 'type_conge_id' => 3, 'annee' => 2026, 'jours_attribues' => 5, 'jours_pris' => 0],
        ]);

        $this->db->table('conges')->insertBatch([
            ['id' => 1, 'employee_id' => 3, 'type_conge_id' => 1, 'date_debut' => '2025-06-10', 'date_fin' => '2025-06-14', 'nb_jours' => 5, 'motif' => 'Conge familial', 'statut' => 'en_attente', 'commentaire_rh' => null, 'created_at' => '2025-05-30 09:10:00', 'traite_par' => null],
            ['id' => 2, 'employee_id' => 3, 'type_conge_id' => 2, 'date_debut' => '2025-04-08', 'date_fin' => '2025-04-09', 'nb_jours' => 2, 'motif' => 'Consultation medicale', 'statut' => 'approuve', 'commentaire_rh' => 'Justificatif fourni', 'created_at' => '2025-04-01 11:20:00', 'traite_par' => 2],
            ['id' => 3, 'employee_id' => 4, 'type_conge_id' => 3, 'date_debut' => '2025-07-01', 'date_fin' => '2025-07-01', 'nb_jours' => 1, 'motif' => 'Evenement personnel', 'statut' => 'refuse', 'commentaire_rh' => 'Periode de forte activite', 'created_at' => '2025-06-15 15:45:00', 'traite_par' => 2],
            ['id' => 4, 'employee_id' => 4, 'type_conge_id' => 1, 'date_debut' => '2025-08-18', 'date_fin' => '2025-08-22', 'nb_jours' => 5, 'motif' => 'Repos annuel', 'statut' => 'en_attente', 'commentaire_rh' => null, 'created_at' => '2025-07-26 08:30:00', 'traite_par' => null],
            ['id' => 5, 'employee_id' => 3, 'type_conge_id' => 1, 'date_debut' => '2026-01-12', 'date_fin' => '2026-01-16', 'nb_jours' => 5, 'motif' => 'Vacances hiver', 'statut' => 'en_attente', 'commentaire_rh' => null, 'created_at' => '2026-01-02 08:15:00', 'traite_par' => null],
            ['id' => 6, 'employee_id' => 3, 'type_conge_id' => 3, 'date_debut' => '2026-02-10', 'date_fin' => '2026-02-10', 'nb_jours' => 1, 'motif' => 'Demarche administrative', 'statut' => 'approuve', 'commentaire_rh' => 'Demande acceptee', 'created_at' => '2026-02-01 10:05:00', 'traite_par' => 2],
            ['id' => 7, 'employee_id' => 4, 'type_conge_id' => 2, 'date_debut' => '2026-03-03', 'date_fin' => '2026-03-04', 'nb_jours' => 2, 'motif' => 'Controle medical', 'statut' => 'approuve', 'commentaire_rh' => 'Justificatif valide', 'created_at' => '2026-02-25 14:40:00', 'traite_par' => 2],
            ['id' => 8, 'employee_id' => 4, 'type_conge_id' => 1, 'date_debut' => '2026-04-20', 'date_fin' => '2026-04-26', 'nb_jours' => 7, 'motif' => 'Conge annuel long', 'statut' => 'en_attente', 'commentaire_rh' => null, 'created_at' => '2026-04-05 09:00:00', 'traite_par' => null],
            ['id' => 9, 'employee_id' => 2, 'type_conge_id' => 3, 'date_debut' => '2026-05-06', 'date_fin' => '2026-05-06', 'nb_jours' => 1, 'motif' => 'Mission institutionnelle', 'statut' => 'approuve', 'commentaire_rh' => 'Autorise par la direction', 'created_at' => '2026-04-28 11:30:00', 'traite_par' => 1],
            ['id' => 10, 'employee_id' => 2, 'type_conge_id' => 1, 'date_debut' => '2026-08-17', 'date_fin' => '2026-08-21', 'nb_jours' => 5, 'motif' => 'Fermeture annuelle', 'statut' => 'refuse', 'commentaire_rh' => 'Periode critique de cloture', 'created_at' => '2026-08-01 16:20:00', 'traite_par' => 2],
        ]);
    }
}
