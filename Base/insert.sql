PRAGMA foreign_keys = ON;

INSERT INTO departements (id, nom, description) VALUES
	(1, 'Informatique', 'Infrastructure, applications et support interne'),
	(2, 'Ressources humaines', 'Gestion des équipes et des congés'),
	(3, 'Direction', 'Pilotage opérationnel et administratif');

INSERT INTO employees (id, nom, prenom, email, password, role, departement_id, date_embauche, actif) VALUES
	(1, 'Mamy', 'Administrateur', 'admin@techmada.mg', '$2y$10$rR9fnHiiiQLDgqtDQWvBN.EwP.oqfSf54N4Jl9hIv.5lvs6L273Pi', 'admin', 3, '2023-01-10', 1),
	(2, 'Rabe', 'Marie', 'rh@techmada.mg', '$2y$10$l.ZHpNE6zFSrNlYrkocx0eOHnuGhkkxtJ7uHdtxvlMzdV0Ap0gxOq', 'rh', 2, '2023-03-15', 1),
	(3, 'Rakoto', 'Soa', 'employe@techmada.mg', '$2y$10$QwkqeJrB3BR5JP6/9EKfB.HGe1o3gNpV9GReSB0/Ii2IVZKrD6iq.', 'employee', 1, '2024-02-05', 1),
	(4, 'Rasoa', 'Fetra', 'fetra@techmada.mg', '$2y$10$QwkqeJrB3BR5JP6/9EKfB.HGe1o3gNpV9GReSB0/Ii2IVZKrD6iq.', 'employee', 1, '2024-05-20', 1);

INSERT INTO types_conge (id, libelle, jours_annuels, deductible) VALUES
	(1, 'Congé annuel', 30, 1),
	(2, 'Congé maladie', 10, 1),
	(3, 'Congé spécial', 5, 0),
	(4, 'Sans solde', 0, 0);

INSERT INTO soldes (id, employee_id, type_conge_id, annee, jours_attribues, jours_pris) VALUES
	(1, 3, 1, 2025, 30, 12),
	(2, 3, 2, 2025, 10, 2),
	(3, 4, 1, 2025, 30, 6),
	(4, 4, 3, 2025, 5, 1),
	(5, 2, 1, 2025, 30, 4),
	(6, 3, 1, 2026, 30, 8),
	(7, 3, 2, 2026, 10, 1),
	(8, 3, 3, 2026, 5, 0),
	(9, 4, 1, 2026, 30, 5),
	(10, 4, 2, 2026, 10, 0),
	(11, 2, 1, 2026, 30, 3),
	(12, 2, 3, 2026, 5, 0);

INSERT INTO conges (id, employee_id, type_conge_id, date_debut, date_fin, nb_jours, motif, statut, commentaire_rh, created_at, traite_par) VALUES
	(1, 3, 1, '2025-06-10', '2025-06-14', 5, 'Congé familial', 'en_attente', NULL, '2025-05-30 09:10:00', NULL),
	(2, 3, 2, '2025-04-08', '2025-04-09', 2, 'Consultation médicale', 'approuve', 'Justificatif fourni', '2025-04-01 11:20:00', 2),
	(3, 4, 3, '2025-07-01', '2025-07-01', 1, 'Évènement personnel', 'refuse', 'Période de forte activité', '2025-06-15 15:45:00', 2),
	(4, 4, 1, '2025-08-18', '2025-08-22', 5, 'Repos annuel', 'en_attente', NULL, '2025-07-26 08:30:00', NULL),
	(5, 3, 1, '2026-01-12', '2026-01-16', 5, 'Vacances d’hiver', 'en_attente', NULL, '2026-01-02 08:15:00', NULL),
	(6, 3, 3, '2026-02-10', '2026-02-10', 1, 'Démarche administrative', 'approuve', 'Demande acceptée', '2026-02-01 10:05:00', 2),
	(7, 4, 2, '2026-03-03', '2026-03-04', 2, 'Contrôle médical', 'approuve', 'Justificatif validé', '2026-02-25 14:40:00', 2),
	(8, 4, 1, '2026-04-20', '2026-04-26', 7, 'Congé annuel long', 'en_attente', NULL, '2026-04-05 09:00:00', NULL),
	(9, 2, 3, '2026-05-06', '2026-05-06', 1, 'Mission institutionnelle', 'approuve', 'Autorisé par la direction', '2026-04-28 11:30:00', 1),
	(10, 2, 1, '2026-08-17', '2026-08-21', 5, 'Fermeture annuelle', 'refuse', 'Période critique de clôture', '2026-08-01 16:20:00', 2);
