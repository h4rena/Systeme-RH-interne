PRAGMA foreign_keys = ON;

CREATE TABLE departements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    description TEXT
);

CREATE TABLE employees (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    prenom TEXT,
    email TEXT UNIQUE,
    password TEXT,
    role TEXT CHECK(role IN ('admin', 'employee')) NOT NULL,
    departement_id INTEGER,
    date_embauche DATE,
    actif INTEGER CHECK(actif IN (0, 1)),
    
    FOREIGN KEY (departement_id) REFERENCES departements(id)
);

CREATE TABLE types_conge (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT,
    jour_annuels INTEGER,
    deductible INTEGER CHECK(deductible IN (0, 1))
);

CREATE TABLE solde (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id INTEGER,
    type_conge_id INTEGER,
    annee INTEGER,
    jours_attribues INTEGER,
    jours_pris INTEGER,

    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id)
);

CREATE TABLE conges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id INTEGER,
    type_conge_id INTEGER,
    date_debut DATE,
    date_fin DATE,
    nb_jours INTEGER,
    motif TEXT,
    statut TEXT CHECK(statut IN ('en_attente', 'approuve', 'refuse')),
    commentaire_rh TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    traite_par INTEGER,

    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id),
    FOREIGN KEY (traite_par) REFERENCES employees(id)
);