CREATE TABLE `consumers` (
  `apiKey` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `apiSecret` varchar(255) NOT NULL,
  `bankAccount` varchar(255) NOT NULL,
  `bankBIC` varchar(255) NOT NULL,
  `token` text NOT NULL,
  `refreshToken` text NOT NULL,
  `name` varchar(255) NOT NULL
);

CREATE TABLE `cardDetails` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `cardNumber` varchar(255) NOT NULL,
  `pin` int NOT NULL,
  `expiry` varchar(40) NOT NULL,
  `cvv` int NOT NULL,
  `cardCompany` varchar(255) NOT NULL
);

CREATE TABLE `payments` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `payerPhone` varchar(255) NOT NULL,
  `payerName` varchar(255) NOT NULL,
  `consumerId` varchar(255) NOT NULL,
  `data` text DEFAULT "",
  `state` varchar(10),
  `amount` int(11),
  `cardDetailsId` varchar(255)
);

ALTER TABLE `payments` ADD FOREIGN KEY (`consumerId`) REFERENCES `consumers` (`apiKey`);

ALTER TABLE `payments` ADD FOREIGN KEY (`cardDetailsId`) REFERENCES `cardDetails` (`id`);
