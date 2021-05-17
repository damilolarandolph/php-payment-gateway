CREATE TABLE `products` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` float(23) NOT NULL
);

CREATE TABLE `students` (
  `id` varchar(255) PRIMARY KEY DEFAULT (uuid()),
  `name` varchar(255) NOT NULL,
  `owedFees` float(23) NOT NULL
);
