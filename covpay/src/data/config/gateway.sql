CREATE TABLE `consumers` (
  `apiKey` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `apiSecret` varchar(255) NOT NULL,
  `bankAccount` varchar(255) NOT NULL,
  `bankBIC` varchar(255) NOT NULL,
  `token` text NOT NULL,
  `refreshToken` text NOT NULL,
  `name` varchar(255) NOT NULL
);

CREATE TABLE `session` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `payerEmail` varchar(255) NOT NULL,
  `channels` text NOT NULL,
  `amount` integer NOT NULL,
  `payment` varchar(255)
);

CREATE TABLE `cardDetails` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `cardNumber` varchar(255) NOT NULL,
  `pin` int NOT NULL,
  `expiry` varchar(40) NOT NULL,
  `cvv` int NOT NULL,
  `cardCompany` varchar(255) NOT NULL
);

CREATE TABLE `bankDetails` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `accountNumber` varchar(255) NOT NULL,
  `bankBIC` varchar(255) NOT NULL
);

CREATE TABLE `payer` (
  `payerEmail` varchar(255) PRIMARY KEY NOT NULL
);

CREATE TABLE `payment` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `payer` varchar(255) NOT NULL,
  `paymentMethod` varchar(255),
  `cardDetailsId` varchar(255) NOT NULL,
  `bankDetailsId` varchar(255) NOT NULL
);

CREATE TABLE `refund` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `payment` varchar(255)
);

CREATE TABLE `transaction` (
  `paymentId` varchar(255)
);

ALTER TABLE `session` ADD FOREIGN KEY (`payment`) REFERENCES `payment` (`id`);

ALTER TABLE `payment` ADD FOREIGN KEY (`payer`) REFERENCES `payer` (`payerEmail`);

ALTER TABLE `payment` ADD FOREIGN KEY (`cardDetailsId`) REFERENCES `cardDetails` (`id`);

ALTER TABLE `payment` ADD FOREIGN KEY (`bankDetailsId`) REFERENCES `bankDetails` (`id`);

ALTER TABLE `refund` ADD FOREIGN KEY (`payment`) REFERENCES `payment` (`id`);

ALTER TABLE `transaction` ADD FOREIGN KEY (`paymentId`) REFERENCES `payment` (`id`);
