CREATE TABLE `consumers` (
  `apiKey` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `apiSecret` varchar(255) NOT NULL
);

CREATE TABLE `accounts` (
  `id` varchar(255) PRIMARY KEY NOT NULL DEFAULT (uuid()),
  `accountNumber` varchar(255) UNIQUE NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `phoneNumber` varchar(255) NOT NULL,
  `signingKey` text NOT NULL
);

CREATE TABLE `mandate` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `status` varchar(100) NOT NULL,
  `transactionId` varchar(255)
);

CREATE TABLE `transaction` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `amount` int(11) NOT NULL,
  `accountId` varchar(255),
  `status` varchar(100) NOT NULL
);

CREATE TABLE `revokedTokens` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `token` text NOT NULL,
  `revokedAt` int NOT NULL
);

ALTER TABLE `mandate` ADD FOREIGN KEY (`transactionId`) REFERENCES `transaction` (`id`);

ALTER TABLE `transaction` ADD FOREIGN KEY (`accountId`) REFERENCES `accounts` (`id`);
