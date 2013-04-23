-- MySQL DDL

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `name` varchar(400) NOT NULL,
  `url` varchar(400) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `user_2` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(400) NOT NULL,
  `token` varchar(400) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user`) REFERENCES `android-backend-db`.users (`id`);