
CREATE TABLE `fruits_stock` (
  `shop_id` int(11) NOT NULL,
  `fruits_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `fruits_stock` (`shop_id`, `fruits_id`, `stock`) VALUES
(1, 1, 3),
(1, 2, 5),
(2, 2, 15),
(2, 3, 6),
(1, 4, 8),
(2, 4, 1);
COMMIT;
