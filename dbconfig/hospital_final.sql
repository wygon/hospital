-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sty 24, 2026 at 12:43 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_final`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `diseases`
--

CREATE TABLE `diseases` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `IcdCode` varchar(10) DEFAULT NULL,
  `Category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`Id`, `Name`, `IcdCode`, `Category`) VALUES
(11, 'Acute Myocardial Infarction', 'I21', 'Cardiovascular'),
(12, 'Atrial Fibrillation', 'I48', 'Cardiovascular'),
(13, 'Pneumonia, unspecified organism', 'J18.9', 'Respiratory'),
(14, 'Chronic Obstructive Pulmonary Disease', 'J44.9', 'Respiratory'),
(15, 'Multiple Sclerosis', 'G35', 'Neurological'),
(16, 'Alzheimers Disease', 'G30', 'Neurological'),
(17, 'Generalized Anxiety Disorder', 'F41.1', 'Mental Health'),
(18, 'Bipolar Affective Disorder', 'F31', 'Mental Health'),
(19, 'Crohns Disease', 'K50', 'Digestive'),
(20, 'Ulcerative Colitis', 'K51', 'Digestive'),
(21, 'Rheumatoid Arthritis', 'M06', 'Musculoskeletal'),
(22, 'Osteoporosis without pathological fracture', 'M81.0', 'Musculoskeletal'),
(23, 'Psoriasis Vulgaris', 'L40.0', 'Dermatological'),
(24, 'Atopic Dermatitis', 'L20', 'Dermatological'),
(25, 'Iron Deficiency Anemia', 'D50', 'Hematological'),
(26, 'Vitamin D Deficiency', 'E55.9', 'Metabolic'),
(27, 'Acute Myocardial Infarction', 'I21', 'Cardiovascular'),
(28, 'Atrial Fibrillation', 'I48', 'Cardiovascular'),
(29, 'Pneumonia, unspecified organism', 'J18.9', 'Respiratory'),
(30, 'Chronic Obstructive Pulmonary Disease', 'J44.9', 'Respiratory'),
(31, 'Multiple Sclerosis', 'G35', 'Neurological'),
(32, 'Alzheimers Disease', 'G30', 'Neurological'),
(33, 'Generalized Anxiety Disorder', 'F41.1', 'Mental Health'),
(34, 'Bipolar Affective Disorder', 'F31', 'Mental Health'),
(35, 'Crohns Disease', 'K50', 'Digestive'),
(36, 'Ulcerative Colitis', 'K51', 'Digestive'),
(37, 'Rheumatoid Arthritis', 'M06', 'Musculoskeletal'),
(38, 'Osteoporosis without pathological fracture', 'M81.0', 'Musculoskeletal'),
(39, 'Psoriasis Vulgaris', 'L40.0', 'Dermatological'),
(40, 'Atopic Dermatitis', 'L20', 'Dermatological'),
(41, 'Iron Deficiency Anemia', 'D50', 'Hematological'),
(42, 'Vitamin D Deficiency', 'E55.9', 'Metabolic');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `medicines`
--

CREATE TABLE `medicines` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `DosageForm` enum('tablets','syrup','inhaler','injection','powder','drops') NOT NULL,
  `ActiveSubstance` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`Id`, `Name`, `DosageForm`, `ActiveSubstance`) VALUES
(21, 'Lisinopril', 'tablets', 'Lisinopril'),
(22, 'Atorvastatin', 'tablets', 'Atorvastatin Calcium'),
(23, 'Azithromycin', 'tablets', 'Azithromycin'),
(24, 'Albuterol', 'inhaler', 'Salbutamol Sulfate'),
(25, 'Lantus Solostar', 'injection', 'Insulin Glargine'),
(26, 'Zoloft', 'tablets', 'Sertraline Hydrochloride'),
(27, 'Xanax', 'tablets', 'Alprazolam'),
(28, 'Nexium', 'tablets', 'Esomeprazole'),
(29, 'Advil', 'tablets', 'Ibuprofen'),
(30, 'Tylenol Sinus', 'syrup', 'Acetaminophen + Phenylephrine'),
(31, 'Budesonide', 'inhaler', 'Budesonide'),
(32, 'Hydrochlorothiazide', 'tablets', 'Hydrochlorothiazide'),
(33, 'Metoprolol Succinate', 'tablets', 'Metoprolol'),
(34, 'Furosemide', 'injection', 'Furosemide'),
(35, 'Opatanol', 'drops', 'Olopatadine hydrochloride'),
(36, 'Diclofenac Gel', 'powder', 'Diclofenac Sodium'),
(37, 'Aspirin Cardio', 'tablets', 'Acetylsalicylic acid'),
(38, 'Clopidogrel', 'tablets', 'Clopidogrel bisulfate'),
(39, 'Symbicort Turbohaler', 'inhaler', 'Budesonide + Formoterol'),
(40, 'Spiriva HandiHaler', 'inhaler', 'Tiotropium bromide'),
(41, 'Robitussin', 'syrup', 'Dextromethorphan'),
(42, 'Lactulose Syrup', 'syrup', 'Lactulose'),
(43, 'Clexane', 'injection', 'Enoxaparin sodium'),
(44, 'Humalog', 'injection', 'Insulin Lispro'),
(45, 'Neomycin', 'drops', 'Neomycin sulfate'),
(46, 'Visine', 'drops', 'Tetryzoline hydrochloride'),
(47, 'Otrivin', 'drops', 'Xylometazoline hydrochloride'),
(48, 'Smecta', 'powder', 'Diosmectite'),
(49, 'Magnesium Citrate', 'powder', 'Magnesium ions'),
(50, 'Warfarin', 'tablets', 'Warfarin sodium'),
(51, 'Amlodipine', 'tablets', 'Amlodipine besylate'),
(52, 'Prednisolone Oral', 'syrup', 'Prednisolone'),
(53, 'Flixotide', 'inhaler', 'Fluticasone propionate'),
(54, 'Vibramycin', 'tablets', 'Doxycycline'),
(55, 'Glucophage XR', 'tablets', 'Metformin hydrochloride'),
(56, 'Tramadol', 'drops', 'Tramadol hydrochloride'),
(57, 'Bactrim', 'tablets', 'Sulfamethoxazole + Trimethoprim'),
(58, 'Epanutin', 'injection', 'Phenytoin sodium'),
(59, 'Ospamox', 'powder', 'Amoxicillin'),
(60, 'Tobrex', 'drops', 'Tobramycin'),
(61, 'Mucosolvan', 'syrup', 'Ambroxol hydrochloride'),
(62, 'Prozac', 'tablets', 'Fluoxetine'),
(63, 'Diazepam', 'tablets', 'Diazepam'),
(64, 'Imodium', 'tablets', 'Loperamide hydrochloride'),
(65, 'Mometasone', 'drops', 'Mometasone furoate'),
(66, 'Solu-Medrol', 'injection', 'Methylprednisolone');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `patientdiagnoses`
--

CREATE TABLE `patientdiagnoses` (
  `Id` int(11) NOT NULL,
  `PatientId` int(11) NOT NULL,
  `DiseaseId` int(11) NOT NULL,
  `VisitId` int(11) NOT NULL,
  `DiagnosisDate` datetime DEFAULT current_timestamp(),
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patientdiagnoses`
--

INSERT INTO `patientdiagnoses` (`Id`, `PatientId`, `DiseaseId`, `VisitId`, `DiagnosisDate`, `Description`) VALUES
(24, 21, 16, 20, '2026-01-24 12:11:01', 'Alzheimer symptomps'),
(25, 21, 26, 20, '2026-01-24 12:11:01', 'Go for a walk young man');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `prescriptionitems`
--

CREATE TABLE `prescriptionitems` (
  `PrescriptionId` int(11) NOT NULL,
  `MedicineId` int(11) NOT NULL,
  `Instructions` varchar(255) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptionitems`
--

INSERT INTO `prescriptionitems` (`PrescriptionId`, `MedicineId`, `Instructions`, `Quantity`) VALUES
(11, 21, '1 times per day until pack empty', 1),
(11, 63, '3 times per day for 3 weeks', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `prescriptions`
--

CREATE TABLE `prescriptions` (
  `Id` int(11) NOT NULL,
  `VisitId` int(11) NOT NULL,
  `IssueDate` datetime DEFAULT current_timestamp(),
  `ReciptCode` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`Id`, `VisitId`, `IssueDate`, `ReciptCode`) VALUES
(11, 20, '2026-02-07 00:00:00', '9563');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `specializations`
--

CREATE TABLE `specializations` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`Id`, `Name`, `CreatedAt`) VALUES
(11, 'Internal Medicine', '2026-01-24 11:08:03'),
(12, 'Cardiology', '2026-01-24 11:08:03'),
(13, 'Pediatrics', '2026-01-24 11:08:03'),
(14, 'Neurology', '2026-01-24 11:08:03'),
(15, 'Dermatology', '2026-01-24 11:08:03'),
(16, 'Orthopedics', '2026-01-24 11:08:03'),
(17, 'Gynecology and Obstetrics', '2026-01-24 11:08:03'),
(18, 'General Surgery', '2026-01-24 11:08:03'),
(19, 'Ophthalmology', '2026-01-24 11:08:03'),
(20, 'Psychiatry', '2026-01-24 11:08:03'),
(21, 'Endocrinology', '2026-01-24 11:08:03'),
(22, 'Gastroenterology', '2026-01-24 11:08:03'),
(23, 'Oncology', '2026-01-24 11:08:03'),
(24, 'Urology', '2026-01-24 11:08:03'),
(25, 'Pulmonology', '2026-01-24 11:08:03'),
(26, 'Rheumatology', '2026-01-24 11:08:03'),
(27, 'Radiology', '2026-01-24 11:08:03'),
(28, 'Anesthesiology', '2026-01-24 11:08:03'),
(29, 'Nephrology', '2026-01-24 11:08:03'),
(30, 'Hematology', '2026-01-24 11:08:03');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `Role` enum('patient','doctor') NOT NULL,
  `Specialization` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `height` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `Pesel` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Username`, `Password`, `Name`, `Surname`, `Role`, `Specialization`, `CreatedAt`, `height`, `weight`, `Pesel`) VALUES
(11, 'j.smith', '$2y$10$2QHZ6pryO81EG3VF0gmAOe2nfclpE7vAQJ9glktm1rTdZD1HePFe2', 'John', 'Smith', 'doctor', 11, '2026-01-24 10:15:16', 0, 0, '80010112345'),
(12, 'm.garcia', '$2y$10$Z7NipIiNDpS6MpLeGLoPGeUzrIJDIAs8ZtbaYjZmwKo2vodEURxXG', 'Maria', 'Garcia', 'doctor', 12, '2026-01-24 10:16:22', 0, 0, '85020223456'),
(13, 'r.wilson', '$2y$10$Aq./ms/Lgac34kOZP0TRleP00cCMYepqjnfpg1oLSzEkiXwWjtvhC', 'Robert', 'Wilson', 'doctor', 15, '2026-01-24 10:17:06', 0, 0, '78030334567'),
(14, 'l.brown', '$2y$10$bgfr/qBISyj60if230jzUufTsnv77tIVuj4uvL6OKuy.vfbuszk8.', 'Linda', 'Brown', 'doctor', 21, '2026-01-24 10:17:29', 0, 0, '90040445678'),
(15, 'd.jones', '$2y$10$wCh1.G2MXTCUxKk0I6Dw3uTVpdu9f/hhD99jyLMG/j8AFGJuetNh.', 'David', 'Jones', 'doctor', 22, '2026-01-24 10:17:53', 0, 0, '82050556789'),
(16, 'k.miller', '$2y$10$CowsLsuAoSPF7kOP46tfIOqRjuHP2o2.edSXO5cflgEVyZ.m8UEby', 'Kevin', 'Miller', 'doctor', 18, '2026-01-24 10:18:35', 0, 0, '75060667890'),
(17, 's.davis', '$2y$10$aFSU33RC9WA5hNpiJplsJet6TOyy4t1c.58/W2HIkPfUAUt043J5S', 'Sarah', 'Davis', 'doctor', 17, '2026-01-24 10:19:56', 0, 0, '88070778901'),
(18, 'j.taylor', '$2y$10$WQSXEyiD1N1L1quzN/.GcOhEgy5YJi5XbhBWY4dbe7vPDDAtpzUIG', 'James', 'Taylor', 'patient', NULL, '2026-01-24 10:20:18', 0, 0, '81080889012'),
(19, 'e.white', '$2y$10$fNDJ3BTaL741lC2xgt4TeOoguk7VLJvyH.QvCraWxJtGXS65VYfAS', 'Emily', 'White', 'doctor', 30, '2026-01-24 10:20:52', 0, 0, '92090990123'),
(20, 'm.harris', '$2y$10$eHLRXyy71nZQI4NNrl8LIeulM0fyApstZIWNO1Tksc7KAuAjqkZ9O', 'Michael', 'Harris', 'doctor', 24, '2026-01-24 10:22:41', 0, 0, '79101001234'),
(21, 's.wygonski', '$2y$10$3CbF7vTvfPBAOvG.7WvBROZsvABXcku5JlVGEOwwid0uYIt0Xd/Fy', 'Szymon', 'Wygoński', 'patient', NULL, '2026-01-24 10:23:07', 178, 72, '1234567890'),
(22, 't.turner', '$2y$10$g9hJXGhuJu/H1gjxtsrGCeCDTcD4iRF6RHv5k.KkHI2zSrNtNjnpy', 'Timmy', 'Turner', 'patient', NULL, '2026-01-24 11:28:03', 0, 0, '1234567890');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `visits`
--

CREATE TABLE `visits` (
  `Id` int(11) NOT NULL,
  `PatientId` int(11) NOT NULL,
  `DoctorId` int(11) NOT NULL,
  `VisitDate` datetime NOT NULL,
  `Summary` text DEFAULT NULL,
  `Status` enum('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `LongDescription` varchar(500) NOT NULL DEFAULT '',
  `PatientDescription` varchar(500) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`Id`, `PatientId`, `DoctorId`, `VisitDate`, `Summary`, `Status`, `LongDescription`, `PatientDescription`) VALUES
(18, 21, 11, '2026-01-24 13:05:00', 'Head ache', 'scheduled', '', 'Please i need medicine'),
(19, 21, 12, '2026-01-28 06:11:00', 'Heart pain', 'scheduled', '', 'I need surgery!!!'),
(20, 21, 16, '2026-01-24 12:18:11', 'Broken', 'completed', 'For sure your arm is broken, make an appointment to surgery doctor', 'I have broken arm... please repair me!!!'),
(21, 22, 11, '2026-01-24 16:28:00', 'Sore troath', 'scheduled', '', 'Hilfe'),
(22, 22, 17, '2026-01-24 16:29:00', 'I dont know', 'scheduled', '', 'Help me doctor please'),
(23, 22, 12, '2026-02-24 17:30:00', 'Heart pain', 'scheduled', '', 'My hear rate is 211/50');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`Id`);

--
-- Indeksy dla tabeli `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`Id`);

--
-- Indeksy dla tabeli `patientdiagnoses`
--
ALTER TABLE `patientdiagnoses`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_Patient` (`PatientId`),
  ADD KEY `FK_Visit` (`VisitId`),
  ADD KEY `FK_Disease` (`DiseaseId`);

--
-- Indeksy dla tabeli `prescriptionitems`
--
ALTER TABLE `prescriptionitems`
  ADD PRIMARY KEY (`PrescriptionId`,`MedicineId`),
  ADD KEY `FK_Medicine` (`MedicineId`);

--
-- Indeksy dla tabeli `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_VisitPrescription` (`VisitId`);

--
-- Indeksy dla tabeli `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indeksy dla tabeli `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_visit_patient` (`PatientId`),
  ADD KEY `fk_visit_doctor` (`DoctorId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diseases`
--
ALTER TABLE `diseases`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `patientdiagnoses`
--
ALTER TABLE `patientdiagnoses`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `patientdiagnoses`
--
ALTER TABLE `patientdiagnoses`
  ADD CONSTRAINT `FK_Disease` FOREIGN KEY (`DiseaseId`) REFERENCES `diseases` (`Id`),
  ADD CONSTRAINT `FK_Patient` FOREIGN KEY (`PatientId`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Visit` FOREIGN KEY (`VisitId`) REFERENCES `visits` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptionitems`
--
ALTER TABLE `prescriptionitems`
  ADD CONSTRAINT `FK_Medicine` FOREIGN KEY (`MedicineId`) REFERENCES `medicines` (`Id`),
  ADD CONSTRAINT `FK_Prescription` FOREIGN KEY (`PrescriptionId`) REFERENCES `prescriptions` (`Id`);

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `FK_VisitPrescription` FOREIGN KEY (`VisitId`) REFERENCES `visits` (`Id`);

--
-- Constraints for table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `fk_visit_doctor` FOREIGN KEY (`DoctorId`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_visit_patient` FOREIGN KEY (`PatientId`) REFERENCES `users` (`Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
