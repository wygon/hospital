SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE `Hospital_test2`;

CREATE TABLE `diseases` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `IcdCode` varchar(10) DEFAULT NULL,
  `Category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `medicines` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `DosageForm` enum('tablets','syrup','inhaler','injection','powder','drops') NOT NULL,
  `ActiveSubstance` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `patientdiagnoses` (
  `Id` int(11) NOT NULL,
  `PatientId` int(11) NOT NULL,
  `DiseaseId` int(11) NOT NULL,
  `VisitId` int(11) NOT NULL,
  `DiagnosisDate` datetime DEFAULT current_timestamp(),
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `prescriptionitems` (
  `PrescriptionId` int(11) NOT NULL,
  `MedicineId` int(11) NOT NULL,
  `Instructions` varchar(255) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `prescriptions` (
  `Id` int(11) NOT NULL,
  `VisitId` int(11) NOT NULL,
  `IssueDate` datetime DEFAULT current_timestamp(),
  `ReciptCode` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `specializations` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

ALTER TABLE `diseases`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `medicines`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `patientdiagnoses`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_Patient` (`PatientId`),
  ADD KEY `FK_Visit` (`VisitId`),
  ADD KEY `FK_Disease` (`DiseaseId`);

ALTER TABLE `prescriptionitems`
  ADD PRIMARY KEY (`PrescriptionId`,`MedicineId`),
  ADD KEY `FK_Medicine` (`MedicineId`);

ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_VisitPrescription` (`VisitId`);

ALTER TABLE `specializations`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Name` (`Name`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`);

ALTER TABLE `visits`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_visit_patient` (`PatientId`),
  ADD KEY `fk_visit_doctor` (`DoctorId`);

ALTER TABLE `diseases`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `medicines`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

ALTER TABLE `patientdiagnoses`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `prescriptions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `specializations`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `visits`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `patientdiagnoses`
  ADD CONSTRAINT `FK_Disease` FOREIGN KEY (`DiseaseId`) REFERENCES `diseases` (`Id`),
  ADD CONSTRAINT `FK_Patient` FOREIGN KEY (`PatientId`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Visit` FOREIGN KEY (`VisitId`) REFERENCES `visits` (`Id`) ON DELETE CASCADE;

ALTER TABLE `prescriptionitems`
  ADD CONSTRAINT `FK_Medicine` FOREIGN KEY (`MedicineId`) REFERENCES `medicines` (`Id`),
  ADD CONSTRAINT `FK_Prescription` FOREIGN KEY (`PrescriptionId`) REFERENCES `prescriptions` (`Id`);

ALTER TABLE `prescriptions`
  ADD CONSTRAINT `FK_VisitPrescription` FOREIGN KEY (`VisitId`) REFERENCES `visits` (`Id`);

ALTER TABLE `visits`
  ADD CONSTRAINT `fk_visit_doctor` FOREIGN KEY (`DoctorId`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_visit_patient` FOREIGN KEY (`PatientId`) REFERENCES `users` (`Id`) ON DELETE CASCADE;
COMMIT;