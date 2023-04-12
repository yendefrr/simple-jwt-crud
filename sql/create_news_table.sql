USE test;

DROP TABLE IF EXISTS news;

CREATE TABLE news (
  id INT(11) NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO news (title, content) VALUES ('First news', 'This is the first news');
INSERT INTO news (title, content) VALUES ('Second news', 'This is the second news');
INSERT INTO news (title, content) VALUES ('Third news', 'This is the third news');