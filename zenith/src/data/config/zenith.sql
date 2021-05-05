CREATE TABLE `consumers` (
  `apiKey` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `apiSecret` varchar(255) NOT NULL
);

CREATE TABLE `accounts` (
  `accountNumber` varchar(255) PRIMARY KEY NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `phoneNumber` varchar(255) NOT NULL
);

CREATE TABLE `mandate` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `status` varchar(100) NOT NULL,
  `transactionId` varchar(255)
);

CREATE TABLE `transaction` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `amount` int(11) NOT NULL,
  `accountNumber` varchar(255),
  `status` varchar(100) NOT NULL
);

ALTER TABLE `mandate` ADD FOREIGN KEY (`transactionId`) REFERENCES `transaction` (`id`);

ALTER TABLE `transaction` ADD FOREIGN KEY (`accountNumber`) REFERENCES `accounts` (`accountNumber`);
