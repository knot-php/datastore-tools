
CREATE TABLE `fruits` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `weight` float NOT NULL,
  `quality` int(11) NOT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `fruits` (`id`, `name`, `weight`, `quality`, `updated_date`) VALUES
(1, 'orange', 0.3, 1, NULL),
(2, 'melon', 0.1, 5, NULL),
(3, 'kiwi', 0.1, 7, NULL),
(4, 'apple', 0.3, 10, NULL);

ALTER TABLE `fruits`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `fruits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;
