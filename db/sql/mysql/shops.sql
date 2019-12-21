
CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `shops` (`id`, `shop_name`) VALUES
(1, 'Super Fruits Market'),
(2, 'Mega Fruits');

ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;
