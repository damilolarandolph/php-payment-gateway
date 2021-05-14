CREATE TABLE `consumers` (
  `id` varchar(225) DEFAULT (uuid()),
  `name` varchar(255) UNIQUE NOT NULL,
  `messengingEndpoint` varchar(255) NOT NULL,
  `secret` text NOT NULL,
  PRIMARY KEY (`id`, `name`)
);

