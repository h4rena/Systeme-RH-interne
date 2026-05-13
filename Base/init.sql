PRAGMA foreign_keys = ON;

CREATE TABLE departements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE employees (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('admin', 'rh', 'employee')) NOT NULL DEFAULT 'employee',
    departement_id INTEGER,
    date_embauche DATE,
    actif INTEGER CHECK(actif IN (0, 1)) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (departement_id) REFERENCES departements(id)
);

CREATE TABLE types_conge (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL,
    jours_annuels INTEGER NOT NULL DEFAULT 0,
    deductible INTEGER CHECK(deductible IN (0, 1)) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE soldes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id INTEGER,
    type_conge_id INTEGER,
    annee INTEGER,
    jours_attribues INTEGER NOT NULL DEFAULT 0,
    jours_pris INTEGER NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id)
);

CREATE TABLE conges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id INTEGER,
    type_conge_id INTEGER,
    date_debut DATE,
    date_fin DATE,
    nb_jours INTEGER NOT NULL DEFAULT 0,
    motif TEXT NOT NULL,
    statut TEXT CHECK(statut IN ('en_attente', 'approuve', 'refuse', 'annulee')) NOT NULL DEFAULT 'en_attente',
    commentaire_rh TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    traite_par INTEGER,

    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id),
    FOREIGN KEY (traite_par) REFERENCES employees(id)
);