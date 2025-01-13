CREATE DATABASE IF NOT EXISTS cli;

USE cli;

-- Создание таблицы `user`
CREATE TABLE IF NOT EXISTS `users` (
                                      `id` INT NOT NULL AUTO_INCREMENT,
                                      `name` VARCHAR(100) NOT NULL,
                                      `email` VARCHAR(100) NOT NULL UNIQUE,
                                      PRIMARY KEY (`id`)
);