CREATE TABLE fruits (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  weight REAL NOT NULL,
  quality INTEGER NOT NULL,
  updated_date TEXT DEFAULT NULL
);

INSERT INTO `fruits` (`id`, `name`, `weight`, `quality`, `updated_date`) VALUES
(1, 'orange', 0.3, 1, NULL),
(2, 'melon', 0.1, 5, NULL),
(3, 'kiwi', 0.1, 7, NULL),
(4, 'apple', 0.3, 10, NULL);
