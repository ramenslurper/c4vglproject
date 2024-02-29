-- create tables for database.
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `usertype` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `pin` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ngo_id` int NOT NULL,
  `vol_id` int NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ngos` (
  `ngo_id` int NOT NULL AUTO_INCREMENT,
  `orgname` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `areasofconcern` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`ngo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `volunteers` (
  `vol_id` int NOT NULL AUTO_INCREMENT,
  `fname` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `hrsperweek` int NOT NULL,
  `cbc` char(3) COLLATE utf8mb4_general_ci NOT NULL,
  `linkedin` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`vol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert some sample data
-- default password is set to "Password1", pin is 11111111
INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `usertype`, `pin`, `ngo_id`, `vol_id`) VALUES
(1, 'gladmin', 'gladmin@gl.com', '$2y$10$8hMyYyev3waRe1g7LzTRj.d.3.xK8WX3S3h2k9jgpxKmdNN7azhha', 'admin', '$2y$10$jqRE8uPN.iuMGr.ZMyke0.Bi1Wqk525bfd2e9yKXZqKdJN8T3lGlG', 0, 0),
(2, 'tom', 'tom@toms.com', '$2y$10$8hMyYyev3waRe1g7LzTRj.d.3.xK8WX3S3h2k9jgpxKmdNN7azhha', 'ngo', '$2y$10$0J78Y2iWQkQaP8uoHhcdVOcujqtBYFhty5a4KjisRmoiJSqiMB4ny', 1, 0),
(3, 'bbr', 'jim@bbr.com', '$2y$10$0HQjwCztiulFbPufFWQnQuhX30A.QR5QL034iKUO5EAsjoAsAiP2O', 'ngo', '$2y$10$dB7Ycq6Zvt8L9TrueN3.iudZq7jY1L3JdUY6L3811cMKsI9XcXkNG', 2, 0),
(4, 'dan', 'dan@dans.com', '$2y$10$8hMyYyev3waRe1g7LzTRj.d.3.xK8WX3S3h2k9jgpxKmdNN7azhha', 'volunteer', '$2y$10$0J78Y2iWQkQaP8uoHhcdVOcujqtBYFhty5a4KjisRmoiJSqiMB4ny', 0, 1),
(5, 'popo', 'popo@popo.com', '$2y$10$0HQjwCztiulFbPufFWQnQuhX30A.QR5QL034iKUO5EAsjoAsAiP2O', 'volunteer', '$2y$10$dB7Ycq6Zvt8L9TrueN3.iudZq7jY1L3JdUY6L3811cMKsI9XcXkNG', 0, 2),
(6, 'john', 'john@email.com', '$2y$10$0HQjwCztiulFbPufFWQnQuhX30A.QR5QL034iKUO5EAsjoAsAiP2O', 'basic', '$2y$10$dB7Ycq6Zvt8L9TrueN3.iudZq7jY1L3JdUY6L3811cMKsI9XcXkNG', 0, 0);

INSERT INTO `volunteers` (`vol_id`, `fname`, `lname`, `hrsperweek`, `cbc`, `linkedin`) VALUES
(1, 'Jim', 'Davis', 5, 'yes', 'https://www.linkedin.com/in/jimd123'),
(2, 'Todd', 'Jones', 11, 'yes', 'https://www.linkedin.com/in/tj456');

INSERT INTO `ngos` (`ngo_id`, `orgname`, `areasofconcern`) VALUES
(1, 'Toms Bakery', 'network'),
(2, 'Big Bowl Ramen', 'virus');
