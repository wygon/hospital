START TRANSACTION;

USE `Hospital_test2`;

SET FOREIGN_KEY_CHECKS = 0;

-- DANE BAZOWE

INSERT INTO `diseases` (`Id`, `Name`, `IcdCode`, `Category`) VALUES
(1, 'Essential (primary) hypertension', 'I10', 'Cardiovascular'),
(2, 'Type 2 diabetes mellitus', 'E11', 'Endocrine'),
(3, 'Acute upper respiratory infection', 'J06', 'Respiratory'),
(4, 'Asthma', 'J45', 'Respiratory'),
(5, 'Migraine without aura', 'G43.0', 'Neurological'),
(6, 'Major depressive disorder', 'F32', 'Mental Health'),
(7, 'Chronic kidney disease, stage 3', 'N18.3', 'Renal'),
(8, 'Gastro-oesophageal reflux disease', 'K21', 'Digestive'),
(9, 'Hyperlipidaemia, unspecified', 'E78.5', 'Metabolic'),
(10, 'Hypothyroidism, unspecified', 'E03.9', 'Endocrine');

INSERT INTO `medicines` (`Id`, `Name`, `DosageForm`, `ActiveSubstance`) VALUES
(11, 'Amotaks', 'tablets', 'Amoxicillin'),
(12, 'Paracetamol Biofarm', 'tablets', 'Paracetamol'),
(13, 'Ventolin', 'inhaler', 'Salbutamol'),
(14, 'Encorton', 'tablets', 'Prednisone'),
(15, 'Augmentin', 'powder', 'Amoxicillin + Clavulanic acid'),
(16, 'Metformax', 'tablets', 'Metformin hydrochloride'),
(17, 'Ketonal Forte', 'tablets', 'Ketoprofen'),
(18, 'Clemastinum', 'syrup', 'Clemastine'),
(19, 'Fraxiparine', 'injection', 'Nadroparin calcium'),
(20, 'Fenistil', 'drops', 'Dimetindene maleate');

INSERT INTO `specializations` (`Id`, `Name`, `CreatedAt`) VALUES
(1, 'Internista', '2025-12-28 16:08:38'),
(2, 'Kardiolog', '2025-12-28 16:08:38'),
(3, 'Pediatra', '2025-12-28 16:08:38'),
(4, 'Neurolog', '2025-12-28 16:08:38'),
(5, 'Dermatolog', '2025-12-28 16:08:38'),
(6, 'Ortopeda', '2025-12-28 16:08:38'),
(7, 'Ginekolog', '2025-12-28 16:08:38'),
(8, 'Chirurg', '2025-12-28 16:08:38'),
(9, 'Okulista', '2025-12-28 16:08:38'),
(10, 'Psychiatra', '2025-12-28 16:08:38');

-- REKORDY
INSERT INTO `users` (`Id`, `Username`, `Password`, `Name`, `Surname`, `Role`, `Specialization`, `CreatedAt`, `height`, `weight`, `Pesel`) VALUES
(1, 'doctor', 'haslo', 'Jan', 'Kowalski', 'doctor', 2, '2025-12-25 16:27:38', 205, 67, ''),
(2, 'user', 'haslo', 'Adam', 'Nowak', 'patient', NULL, '2025-12-25 16:27:38', 65, 12, '6767676767');

INSERT INTO `visits` (`Id`, `PatientId`, `DoctorId`, `VisitDate`, `Summary`, `Status`, `LongDescription`, `PatientDescription`) VALUES
(1, 2, 1, '2025-12-27 15:13:28', 'koniec', 'completed', 'qweeqw', ''),
(2, 2, 1, '2026-01-06 20:14:00', 'Konsultacja', 'scheduled', '', 'qwe'),
(3, 2, 1, '2025-12-27 15:13:16', 'Wstępne', 'completed', '', ''),
(4, 2, 1, '2025-12-28 15:25:00', 'qwe', 'scheduled', '', 'qwe'),
(5, 2, 1, '2025-12-28 19:25:00', 'qwe', 'scheduled', '', 'dasdsa'),
(6, 2, 1, '2025-12-28 20:51:00', 'Wizyta bol dupy', 'scheduled', '', 'Boli mnie dupa, szczegolnie prawy puldupek :P'),
(7, 2, 1, '2025-12-31 18:09:00', 'wizyta', 'scheduled', '', 'skibidi sigma dop dop jes jes\r\n'),
(8, 2, 1, '2025-12-31 19:15:00', 'Bolo mnie jaja', 'scheduled', '', 'w sumie to bola mnie jaj nie wiem co zrobic');

INSERT INTO `patientdiagnoses` (`Id`, `PatientId`, `DiseaseId`, `VisitId`, `DiagnosisDate`, `Description`) VALUES
(1, 2, 2, 1, '2025-11-20 10:30:00', 'Patient shows elevated blood sugar levels. Recommended diet and further tests.'),
(2, 2, 1, 1, '2025-12-05 09:15:00', 'Persistent high blood pressure (150/95). Prescribed first-line medication.'),
(3, 2, 8, 1, '2025-12-15 14:00:00', 'Symptoms of heartburn and acid reflux reported after meals.'),
(4, 2, 5, 1, '2025-12-26 13:45:00', 'Recurring headaches, photophobia present. Patient advised to keep a headache diary.'),
(5, 2, 5, 1, '2025-12-26 17:04:45', 'fsdsfdsfdsfd'),
(6, 2, 3, 1, '2025-12-26 17:04:45', 'sgdfgfds'),
(7, 2, 3, 1, '2025-12-26 17:04:45', 'sgdfgfds'),
(8, 2, 5, 4, '2025-12-26 17:07:30', 'fsdsfdsfdsfd'),
(9, 2, 3, 4, '2025-12-26 17:07:30', 'sgdfgfds'),
(10, 2, 3, 4, '2025-12-26 17:07:30', 'sgdfgfds'),
(11, 2, 7, 2, '2025-12-26 17:09:55', 'asdads'),
(12, 2, 3, 1, '2025-12-27 12:40:36', 'das'),
(13, 2, 8, 1, '2025-12-27 12:40:36', 'das'),
(14, 2, 2, 2, '2025-12-27 14:45:24', 'asd'),
(15, 2, 4, 2, '2025-12-27 14:45:24', 'adsffdas'),
(16, 2, 3, 4, '2025-12-27 15:17:43', 'asddas');

INSERT INTO `prescriptions` (`Id`, `VisitId`, `IssueDate`, `ReciptCode`) VALUES
(1, 4, '2026-01-10 00:00:00', '1790'),
(5, 2, '2026-01-10 00:00:00', '1680'),
(6, 8, '2026-01-11 00:00:00', '2955');

INSERT INTO `prescriptionitems` (`PrescriptionId`, `MedicineId`, `Instructions`, `Quantity`) VALUES
(1, 14, '', 1),
(5, 18, '', 2),
(5, 20, 'fdsafasd', 1),
(6, 12, '1 raz na walenie konia', 2),
(6, 18, 'raz na 3 dni', 1);


SET FOREIGN_KEY_CHECKS = 1;
COMMIT;