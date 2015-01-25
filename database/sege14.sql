-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Värd: blu-ray.student.bth.se
-- Skapad: 25 jan 2015 kl 11:46
-- Serverversion: 5.5.40
-- PHP-version: 5.5.20-1~dotdeb.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `sege14`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `Content`
--

CREATE TABLE IF NOT EXISTS `Content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` char(80) DEFAULT NULL,
  `url` char(80) DEFAULT NULL,
  `type` char(80) DEFAULT NULL,
  `title` varchar(80) DEFAULT NULL,
  `data` text,
  `filter` char(80) DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumpning av Data i tabell `Content`
--

INSERT INTO `Content` (`id`, `slug`, `url`, `type`, `title`, `data`, `filter`, `published`, `created`, `updated`, `deleted`) VALUES
(2, 'om', 'om', 'page', 'Om Film & CO', 'Hej jag heter Sebastian Gerdin och jag har skapat Film & CO.\r\n\r\nHär på Film & CO gör vi allt för att du ska få en sån bra filmkväll som möjligt. Därför väljer vi att sälja allt ifrån filmer,godis,läsk till pizza och annan hämtmat!\r\n\r\nVårt motto lyder "Allt du behöver för fredagsmys!" och detta försöker vi eftersträva varje gång vi köper in nya prylar till affären.\r\n\r\nFilm & CO startades 2014 och har idag 4 olika affärer runt om i landet. Den första affären öppnade i Visby och där var trycket stort så vi öppnade även 3 affärer på fastlandet i de större städerna, Stockholm,Göteborg och Malmö.\r\n\r\nVi hoppas på att forsätta kunna erbjuda den högkvalitets service som vi erbjuder här på Film & CO. Har ni några förslag eller funderingar så tveka inte att höra av er till oss, klagomål får ni dock hålla till er själva.\r\n', 'markdown', '2014-10-27 12:47:17', '2014-10-27 12:47:17', '2014-11-05 18:10:18', NULL),
(3, 'blogpost-1', NULL, 'post', 'Välkommen till Film & CO', 'Vi är stolta att presentera Film & CO!\r\nHär kan du hitta den nyaste filmen och det godaste snacksen till det bästa priset!!\r\nVi har allt du behöver för fredagsmys!\r\n', 'markdown', '2014-10-27 12:47:17', '2014-10-27 12:47:17', '2014-11-04 20:07:03', NULL),
(9, 'nyhet1', NULL, 'post', '50% PÅ ALLT I AFFÄREN!', 'Vi har nu 50% nedsatt pris på allt i affären, missa inte denna chans att se alla filmer du någonsin velat se!', 'markdown', '0000-00-00 00:00:00', '2014-11-03 13:36:55', '2014-11-06 13:45:18', NULL),
(10, 'nyhet2', NULL, 'post', 'Kodare sökes', 'Vi söker nu en kodare för att bygga ut vår hemsida. \r\nVi letar efter någon som kan PHP och MYSQL!', 'markdown', '0000-00-00 00:00:00', '2014-11-03 13:37:03', '2014-11-06 13:45:32', NULL),
(11, 'nyhet3', NULL, 'Post', 'Ny design!', 'Vi kan äntligen släppa Film & CO v2.\r\nVi hoppas att ni uppskattar den nya designen och vi har gjort allt för att det ska vara så lätt som möjligt att se vad vi erbjuder!', 'markdown', '0000-00-00 00:00:00', '2014-11-03 13:37:07', '2014-11-04 20:04:42', NULL),
(12, 'nyhet4', NULL, 'Post', 'Prissänkning på allt!', 'Vi sänker nu priserna på allt i affären, INGET ÖVER 100 kr!!', 'markdown', '0000-00-00 00:00:00', '2014-11-03 13:37:12', '2014-11-04 20:05:34', NULL),
(13, 'nyhet5', NULL, 'Post', 'Ny logga!', 'Vi har äntligen haft tid att skaffa vår egen logga hoppas den inte gör så era ögon blöder!', 'markdown', '0000-00-00 00:00:00', '2014-11-03 13:37:16', '2014-11-04 20:06:07', NULL),
(14, 'test', NULL, 'post', 'TEST', 'HEJ', 'bbcode', '0000-00-00 00:00:00', '2014-11-14 14:10:36', '2014-11-14 14:18:57', '2014-11-14 14:11:20');

-- --------------------------------------------------------

--
-- Tabellstruktur `csgo_question`
--

CREATE TABLE IF NOT EXISTS `csgo_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `type` varchar(80) NOT NULL,
  `belongTo` int(11) DEFAULT NULL,
  `commentTo` int(11) DEFAULT NULL,
  `title` varchar(300) DEFAULT NULL,
  `content` text,
  `accepted` tinyint(1) DEFAULT NULL,
  `posted` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumpning av Data i tabell `csgo_question`
--

INSERT INTO `csgo_question` (`id`, `userId`, `type`, `belongTo`, `commentTo`, `title`, `content`, `accepted`, `posted`, `modified`) VALUES
(12, 1, 'q', 12, NULL, 'Test Title', '<p>This is a test question.</p>\n', NULL, '2015-01-23 17:07:23', NULL),
(13, 1, 'c', 12, 12, NULL, 'Test comment', NULL, '2015-01-23 17:07:37', NULL),
(14, 3, 'q', 14, NULL, 'another test question', '<p>this is just another test.</p>\n', NULL, '2015-01-24 16:06:01', NULL),
(15, 1, 'a', 14, NULL, NULL, 'test answer!', NULL, '2015-01-24 16:06:26', NULL);

-- --------------------------------------------------------

--
-- Tabellstruktur `csgo_tag`
--

CREATE TABLE IF NOT EXISTS `csgo_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `tag` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumpning av Data i tabell `csgo_tag`
--

INSERT INTO `csgo_tag` (`id`, `userId`, `postId`, `tag`) VALUES
(15, 1, 12, 'test'),
(16, 1, 12, 'tag'),
(17, 1, 12, 'csgo'),
(18, 1, 12, ''),
(19, 3, 14, 'test'),
(20, 3, 14, 'question'),
(21, 3, 14, 'csgo');

-- --------------------------------------------------------

--
-- Tabellstruktur `csgo_user`
--

CREATE TABLE IF NOT EXISTS `csgo_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `registered` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumpning av Data i tabell `csgo_user`
--

INSERT INTO `csgo_user` (`id`, `username`, `email`, `password`, `registered`) VALUES
(1, 'Sebastian', 'sebastian.gerdin@gmail.com', 'cc03e747a6afbbcbf8be7668acfebee5', '2015-01-18 14:54:40'),
(2, 'test123', 'test@test.com', 'cc03e747a6afbbcbf8be7668acfebee5', '2015-01-22 22:49:41'),
(3, 'test1234', 'test@test.com', '16d7a4fca7442dda3ad93c9a726597e4', '2015-01-23 17:08:13');

-- --------------------------------------------------------

--
-- Tabellstruktur `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumpning av Data i tabell `genre`
--

INSERT INTO `genre` (`id`, `name`) VALUES
(1, 'comedy'),
(2, 'romance'),
(3, 'college'),
(4, 'crime'),
(5, 'drama'),
(6, 'thriller'),
(7, 'animation'),
(8, 'adventure'),
(9, 'family'),
(11, 'action'),
(12, 'horror');

-- --------------------------------------------------------

--
-- Tabellstruktur `kmom04_comment`
--

CREATE TABLE IF NOT EXISTS `kmom04_comment` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `page` varchar(200) NOT NULL,
  `content` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `web` varchar(200) NOT NULL,
  `mail` varchar(200) NOT NULL,
  `timestamp` varchar(200) NOT NULL,
  `ip` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumpning av Data i tabell `kmom04_comment`
--

INSERT INTO `kmom04_comment` (`id`, `page`, `content`, `name`, `web`, `mail`, `timestamp`, `ip`) VALUES
(13, '', 'Test', 'random', 'test.com', 'test@gmail.com', '1418201178', '194.47.129.126'),
(14, '', 'asd', 'asd', 'asd', 'asd@kamsdl.se', '1419261720', '194.47.129.126');

-- --------------------------------------------------------

--
-- Tabellstruktur `kmom04_user`
--

CREATE TABLE IF NOT EXISTS `kmom04_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acronym` varchar(20) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `active` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `acronym` (`acronym`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumpning av Data i tabell `kmom04_user`
--

INSERT INTO `kmom04_user` (`id`, `acronym`, `email`, `name`, `password`, `created`, `updated`, `deleted`, `active`) VALUES
(1, 'sebastian', 'sebastian.gerdin@gmail.com', 'Sebastian Gerdin', '$2y$10$LBe5E/s0rlqk5Cm1XFCigeMdQPdq2sJv03MkL5J8ruR4r.oz3/EDe', '2014-12-03 11:06:44', NULL, NULL, '2014-12-22 16:24:14'),
(3, 'foiki', 'me@jonatankarlsson.se', 'Jonatan', '$2y$10$dn1NSzfez6/A/mcb5.GzceVrYoasyDsufta7JbAGDaszOsejBzJSW', '2014-12-22 16:23:54', '2014-12-22 16:23:59', NULL, '2014-12-22 16:24:11');

-- --------------------------------------------------------

--
-- Tabellstruktur `Movie`
--

CREATE TABLE IF NOT EXISTS `Movie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `director` varchar(100) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `year` int(11) NOT NULL DEFAULT '1900',
  `plot` text,
  `image` varchar(100) DEFAULT NULL,
  `pris` int(2) DEFAULT NULL,
  `slug` char(80) NOT NULL,
  `url` char(80) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `type` char(5) NOT NULL DEFAULT 'movie',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Dumpning av Data i tabell `Movie`
--

INSERT INTO `Movie` (`id`, `title`, `director`, `length`, `year`, `plot`, `image`, `pris`, `slug`, `url`, `updated`, `type`) VALUES
(1, 'Pulp fiction', 'Admin', 154, 1994, 'Jules Winnfield and Vincent Vega are two hitmen who are out to retrieve a suitcase stolen from their employer, mob boss Marsellus Wallace. Wallace has also asked Vincent to take his wife Mia out a few days later when Wallace himself will be out of town. Butch Coolidge is an aging boxer who is paid by Wallace to lose his next fight. The lives of these seemingly unrelated people are woven together comprising of a series of funny, bizarre and uncalled-for incidents. ', 'img/movie/pulp-fiction.jpg', 35, 'movie1', '', '2014-11-14 14:15:16', 'movie'),
(25, 'Tron', 'Joseph Kosinski', 125, 2010, 'Sam Flynn, the tech-savvy 27-year-old son of Kevin Flynn, looks into his father''s disappearance and finds himself pulled into the same world of fierce programs and gladiatorial games where his father has been living for 20 years. Along with Kevin''s loyal confidant Quorra, father and son embark on a life-and-death journey across a visually-stunning cyber universe that has become far more advanced and exceedingly dangerous. Meanwhile, the malevolent program CLU, who dominates the digital world, plans to invade the real world and will stop at nothing to prevent their escape.', 'img/movie/tron_legacy.jpg', 55, '', '', '2014-11-05 17:52:48', 'movie'),
(26, 'Cloverfield', 'Matt Reeves', 85, 2008, 'Cloverfield follows five New Yorkers from the perspective of a hand-held video camera. The movie is exactly the length of a DV Tape and a sub-plot is established by showing bits and pieces of video previously recorded on the tape that is being recorded over. The movie starts as a monster of unknown origin destroys a building. As they go to investigate, parts of the building and the head of the Statue of Liberty come raining down. The movie follows their adventure trying to escape and save a friend, a love interest of the main character.', 'img/movie/cloverfield.jpg', 67, '', '', '2014-11-05 17:53:35', 'movie'),
(27, 'Interstellar', 'Christopher Nolan', 169, 2014, 'In the near future Earth has been devastated by drought and famine, causing a scarcity in food and extreme changes in climate. When humanity is facing extinction, a mysterious rip in the space-time continuum is discovered, giving mankind the opportunity to widen their lifespan. A group of explorers must travel beyond our solar system in search of a planet that can sustain life. The crew of the Endurance are required to think bigger and go further than any human in history as they embark on an interstellar voyage, into the unknown. However, through the wormhole, one hour is the equivalent of seven years back on Earth, so the mission won''t work if the people on Earth are dead by the time they pull it off. And Coop, the pilot of the Endurance, must decide between seeing his children again and the future of the human race. ', 'img/movie/interstellar.jpg', 80, '', '', NULL, 'movie'),
(28, 'Fury', 'David Ayer', 134, 2014, 'April, 1945. As the Allies make their final push in the European Theatre, a battle-hardened army sergeant named Wardaddy (Brad Pitt) commands a Sherman tank and her five-man crew on a deadly mission behind enemy lines. Outnumbered and outgunned, Wardaddy and his men face overwhelming odds in their heroic attempts to strike at the heart of Nazi Germany.', 'img/movie/fury.jpg', 55, '', '', NULL, 'movie'),
(29, 'Intouchables', 'Olivier Nakache, Eric Toledano', 112, 2011, 'In Paris, the aristocratic and intellectual Philippe is a quadriplegic millionaire who is interviewing candidates for the position of his carer, with his red-haired secretary Magalie. Out of the blue, the rude African Driss cuts the line of candidates and brings a document from the Social Security and asks Phillipe to sign it to prove that he is seeking a job position so he can receive his unemployment benefit. Philippe challenges Driss, offering him a trial period of one month to gain experience helping him. Then Driss can decide whether he would like to stay with him or not. Driss accepts the challenge and moves to the mansion, changing the boring life of Phillipe and his employees. ', 'img/movie/intouchables.jpg', 55, '', '', '2014-11-05 17:54:10', 'movie'),
(30, 'The Imitation Game', 'Morten Tyldum', 114, 2014, 'Based on the real life story of legendary cryptanalyst Alan Turing, the film portrays the nail-biting race against time by Turing and his brilliant team of code-breakers at Britain''s top-secret Government Code and Cypher School at Bletchley Park, during the darkest days of World War II.', 'img/movie/imitation_game.jpg', 45, '', '', '2014-11-06 14:30:38', 'movie'),
(31, 'The Dark Knight', 'Christopher Nolan', 152, 2008, 'Batman raises the stakes in his war on crime. With the help of Lieutenant Jim Gordon and District Attorney Harvey Dent, Batman sets out to dismantle the remaining criminal organizations that plague the city streets. The partnership proves to be effective, but they soon find themselves prey to a reign of chaos unleashed by a rising criminal mastermind known to the terrified citizens of Gotham as The Joker.', 'img/movie/dark_knight.jpg', 60, '', '', NULL, 'movie'),
(32, 'Dark Knight Rises', 'Christopher Nolan', 165, 2012, 'Despite his tarnished reputation after the events of The Dark Knight, in which he took the rap for Dent''s crimes, Batman feels compelled to intervene to assist the city and its police force which is struggling to cope with Bane''s plans to destroy the city.', 'img/movie/dark_knight_rises.jpg', 60, '', '', NULL, 'movie'),
(33, 'Guardians of the Galaxy', 'James Gunn', 121, 2014, 'After stealing a mysterious orb in the far reaches of outer space, Peter Quill from Earth, is now the main target of a manhunt led by the villain known as Ronan the Accuser. To help fight Ronan and his team and save the galaxy from his power, Quill creates a team of space heroes known as the "Guardians of the Galaxy" to save the world.', 'img/movie/guardians.jpg', 50, '', '', NULL, 'movie'),
(34, 'Mamma', NULL, NULL, 1900, NULL, NULL, NULL, '', '', '2014-12-28 21:15:41', 'movie');

-- --------------------------------------------------------

--
-- Tabellstruktur `Movie2Genre`
--

CREATE TABLE IF NOT EXISTS `Movie2Genre` (
  `idMovie` int(11) NOT NULL,
  `idGenre` int(11) NOT NULL,
  PRIMARY KEY (`idMovie`,`idGenre`),
  KEY `idGenre` (`idGenre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `Movie2Genre`
--

INSERT INTO `Movie2Genre` (`idMovie`, `idGenre`) VALUES
(1, 1),
(29, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(28, 5),
(30, 5),
(1, 6),
(30, 6),
(25, 8),
(27, 8),
(31, 8),
(32, 8),
(33, 8),
(1, 9),
(29, 9),
(25, 11),
(26, 11),
(28, 11),
(31, 11),
(32, 11),
(33, 11),
(26, 12);

-- --------------------------------------------------------

--
-- Tabellstruktur `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acronym` char(12) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `salt` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `acronym` (`acronym`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumpning av Data i tabell `User`
--

INSERT INTO `User` (`id`, `acronym`, `name`, `password`, `salt`) VALUES
(1, 'doe', 'John/Jane Doe', '0bec47d8cbec2a6b5696c22fb45daf84', 1413713617),
(2, 'admin', 'Administrator', '88f3032c278b4e82e842cb97d400834e', 1413713617);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
