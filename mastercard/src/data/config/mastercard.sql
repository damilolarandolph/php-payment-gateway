CREATE TABLE `cards` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `number` text UNIQUE NOT NULL,
  `pin` text NOT NULL,
  `cvv` text NOT NULL,
  `expiry` text NOT NULL,
  `bank` varchar(255) NOT NULL,
  `account` varchar(255) NOT NULL
);

CREATE TABLE `consumers` (
  `apiKey` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `apiSecret` varchar(255) NOT NULL
);
