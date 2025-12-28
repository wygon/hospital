-- 1. Tworzenie bazy danych (opcjonalnie, jeśli jeszcze nie masz)
CREATE DATABASE IF NOT EXISTS hospital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hospital;

-- 2. Tabela Użytkowników (Pacjenci i Lekarze w jednej tabeli)
CREATE TABLE Users (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Name VARCHAR(30) NOT NULL,
    Surname VARCHAR(50) NOT NULL,
    Role ENUM('patient', 'doctor') NOT NULL,
    Specialization VARCHAR(100) DEFAULT NULL, -- Tylko dla lekarzy
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Tabela Wizyt
CREATE TABLE Visits (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    PatientId INT NOT NULL,
    DoctorId INT NOT NULL,
    VisitDate DATETIME NOT NULL,
    Description TEXT DEFAULT NULL,           -- Opis uzupełniany przez lekarza po wizycie
    Status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    
    -- Relacje
    CONSTRAINT fk_visit_patient FOREIGN KEY (PatientId) REFERENCES Users(Id) ON DELETE CASCADE,
    CONSTRAINT fk_visit_doctor FOREIGN KEY (DoctorId) REFERENCES Users(Id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Tabela Recept
CREATE TABLE Prescriptions (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    VisitId INT NOT NULL,                     -- Recepta jest powiązana z konkretną wizytą
    MedicineName VARCHAR(255) NOT NULL,
    Dosage TEXT NOT NULL,                     -- Dawkowanie
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Relacja
    CONSTRAINT fk_prescription_visit FOREIGN KEY (VisitId) REFERENCES Visits(Id) ON DELETE CASCADE
) ENGINE=InnoDB;



INSERT INTO Users 
(Username, Password, Name, Surname, Role, Specialization)
VALUES
-- Lekarz
(
  'dr.kowalski',
  '$2y$10$9eHk7yYy8dFZkqYH5ZPpQO5k9Zx2d3pGxX5R8vZK5PZzKpU9yJ1yC',
  'Jan',
  'Kowalski',
  'doctor',
  'Kardiolog'
),

-- Pacjent
(
  'adam.nowak',
  '$2y$10$F6Xy9k8ZyY5QZ3EJ6Fz3uOZrR3V0QH5Yk9xQm2TzB5zL6KpJQZ9o2',
  'Adam',
  'Nowak',
  'patient',
  NULL
);




INSERT INTO Diseases (Name, IcdCode, Category) VALUES 
('Essential (primary) hypertension', 'I10', 'Cardiovascular'),
('Type 2 diabetes mellitus', 'E11', 'Endocrine'),
('Acute upper respiratory infection', 'J06', 'Respiratory'),
('Asthma', 'J45', 'Respiratory'),
('Migraine without aura', 'G43.0', 'Neurological'),
('Major depressive disorder', 'F32', 'Mental Health'),
('Chronic kidney disease, stage 3', 'N18.3', 'Renal'),
('Gastro-oesophageal reflux disease', 'K21', 'Digestive'),
('Hyperlipidaemia, unspecified', 'E78.5', 'Metabolic'),
('Hypothyroidism, unspecified', 'E03.9', 'Endocrine');


INSERT INTO PatientDiagnoses (PatientId, DiseaseId, DiagnosedByDoctorId, DiagnosisDate, Description) VALUES 
(2, 2, 1, '2025-11-20 10:30:00', 'Patient shows elevated blood sugar levels. Recommended diet and further tests.'),
(2, 1, 1, '2025-12-05 09:15:00', 'Persistent high blood pressure (150/95). Prescribed first-line medication.'),
(2, 8, 1, '2025-12-15 14:00:00', 'Symptoms of heartburn and acid reflux reported after meals.'),
(2, 5, 1, '2025-12-26 13:45:00', 'Recurring headaches, photophobia present. Patient advised to keep a headache diary.');



INSERT INTO Medicines (Name, DosageForm, ActiveSubstance) VALUES 
('Amotaks', 'tablets', 'Amoxicillin'),
('Paracetamol Biofarm', 'tablets', 'Paracetamol'),
('Ventolin', 'inhaler', 'Salbutamol'),
('Encorton', 'tablets', 'Prednisone'),
('Augmentin', 'powder', 'Amoxicillin + Clavulanic acid'),
('Metformax', 'tablets', 'Metformin hydrochloride'),
('Ketonal Forte', 'tablets', 'Ketoprofen'),
('Clemastinum', 'syrup', 'Clemastine'),
('Fraxiparine', 'injection', 'Nadroparin calcium'),
('Fenistil', 'drops', 'Dimetindene maleate');