CREATE TABLE `consumers` (
  `apiKey` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `apiSecret` varchar(255) NOT NULL
);

CREATE TABLE `accounts` (
  `id` varchar(255) NOT NULL DEFAULT (uuid()),
  `accountNumber` varchar(255) UNIQUE NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `phoneNumber` varchar(255) NOT NULL,
  `signingKey` text NOT NULL,
  `balance` float(15) NOT NULL,
  PRIMARY KEY (`id`, `accountNumber`)
);

CREATE TABLE `revokedTokens` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `token` text NOT NULL,
  `revokedAt` int NOT NULL
);

CREATE TABLE `tokens` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `consumerId` varchar(255),
  `claimed` bool DEFAULT 0,
  `token` text NOT NULL,
  `refreshToken` text NOT NULL
);

ALTER TABLE `tokens` ADD FOREIGN KEY (`consumerId`) REFERENCES `consumers` (`apiKey`);

