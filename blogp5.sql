-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 13 sep. 2022 à 17:24
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blogp5`
--

-- --------------------------------------------------------

--
-- Structure de la table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `chapo` varchar(200) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `img_src` varchar(500) DEFAULT NULL,
  `status` enum('published','hidden') DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blog_posts_ibfk_1` (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `idUser`, `title`, `chapo`, `content`, `img_src`, `status`, `created_at`, `updated_at`) VALUES
(3, 2, 'JAGUAR : UN PRÉDATEUR REDOUTABLE', 'Aussi à l’aise dans l’eau que sur la terre ferme ou dans les arbres, le jaguar est le plus grand félin du continent américain.', 'Troisième plus grand félin au monde, derrière le tigre et le lion, le jaguar fascine. Agile et puissant, son nom signifie « celui qui tue en un bond ». Sa corpulence imposante lui permet de s’attaquer à des proies qui font parfois deux fois sa taille ! Mais cela ne fait pas tout, c’est un excellent nageur et un grimpeur virtuose.\r\n\r\nOutre les menaces créées par l\'Homme, le jaguar ne craint aucun prédateur sur l’ensemble de son aire de répartition. Il se nourrit d’une large variété d’animaux : mammifères (pécaris, tatous, singes, fourmiliers, petits félins, etc.), oiseaux, reptiles et poissons.\r\n\r\nDe l’extrême sud des Etats-Unis au nord de l’Argentine, le jaguar fréquente des milieux diversifiés, entre forêts humides amazoniennes, hauts-plateaux andins, savanes, mangroves...', 'images/posts/a9ad3c08a8acc4bdaea518b30111b500.jpg', 'published', '2022-08-25 15:16:53', '2022-08-25 15:16:53'),
(12, 2, 'Le Crocodile', 'Le crocodile est un reptile qui possède une grande gueule et de puissantes mâchoires. Il peut atteindre plus de 7 mètres de longueur.', 'Description physique\r\nLe crocodile est un reptile qui possède une grande gueule et de puissantes mâchoires. C’est un redoutable animal qui peut atteindre plus de 7 mètres de longueur. Son corps est moins court que sa queue. Chacune de ses 4 pattes comporte 4 doigts, et ceux de ses pattes arrière sont réunis par une membrane facilitant la nage (comme ceux des canards). Son corps est recouvert d’écailles cornées sur son dos.\r\n\r\nSon lieu de vie\r\nLes crocodiles vivent dans les marais et dans les cours d’eau des différentes régions intertropicales. Ils savent bien nager. Mais ils peuvent aussi marcher sur terre et courir très vite (mais seulement à courte distance).\r\n\r\nOn trouve des crocodiles un peu partout dans le monde, selon les espèces : en Afrique subsaharienne, en Amérique centrale (Mexique, Guatemala, Floride), en Amérique du sud (Colombie, Venezuela), aux Antilles, à Cuba, en Inde, en Iran, au Pakistan, aux Philippines, en Australie du nord, en Asie du sud...\r\n\r\nSon alimentation\r\nLe crocodile est un carnivore. Il se nourrit des divers animaux tels que des poissons, des oiseaux, des serpents… Il peut s’attaquer aussi aux grands mammifères qui viennent s’abreuver au bord des rivières comme les zébus, les zèbres, les girafes… Pour tuer ses proies, il les noie puis il avale ses victimes sans les mâcher.\r\n\r\nSa reproduction\r\nLe crocodile est classé parmi les ovipares. Il doit donc pondre des œufs pour donner naissance à des petits crocodiles (appelés parfois crocodilous). La femelle pond ses œufs dans un trou qu’elle creuse dans le sable. Elle pond en moyenne 20 à 30 œufs, et parfois même jusqu\'à 90 œufs.\r\n\r\nSon espérance de vie\r\nLe crocodile peut vivre de nombreuses années, de 7O à 100 ans. Pendant sa durée de vie, il peut donner naissance à plusieurs crocodilous.\r\n\r\nSon cri\r\nLes crocodiles communiquent entre eux par des signaux sonores. Considéré à tort comme muet, le crocodile se met en contact avec ses voisins par des bruits divers.\r\n\r\nSignes particuliers\r\nLes crocodiles chassent à l\'affût et sont très rapides sur de courtes distances, même hors de l\'eau. Leur vitesse moyenne de déplacement est de 3 km/h mais elle peut atteindre 18 km/h lors d\'une attaque.\r\nIls ont des mâchoires très puissantes et des dents coupantes adaptées au découpage de la viande. Les grandes espèces de crocodiles sont d\'ailleurs très dangereuses pour les humains.\r\nLes crocodiles peuvent rester de longues périodes sans manger.', 'images/posts/6a3a664c129a2b3c503a0fb8c112ab61.jpg', 'published', '2022-09-12 08:30:43', '2022-09-12 08:30:43'),
(13, 2, 'L\'éléphant', 'Dotés d’une trompe utilisée pour attraper des objets, ingérer de l’eau et de la nourriture, les éléphants sont les plus gros animaux terrestres.', 'Les éléphants sont les plus grands animaux terrestres au monde. Avec un poids allant jusqu’à 6 tonnes, l’éléphant d’Afrique est le plus imposant tandis que l’éléphant d’Asie, plus petit, peut peser jusqu’à 5 tonnes.\r\n\r\nLeur trompe, une extension de leur lèvre supérieure et de leur nez, est utilisée pour communiquer et attraper divers objets, au-delà de leur permettre de s’abreuver et de s’alimenter.\r\n\r\nL’autre caractéristique notable des éléphants se situe au niveau de leurs larges oreilles qui, en plus de leur fonction auditive, permettent de refroidir le corps.\r\n\r\nQuant aux défenses, larges incisives modifiées se développant tout au long de la vie de l’éléphant, elles sont utilisées pour se battre, creuser, se nourrir ou bien se repérer. Hélas, celles-ci attirent la convoitise des braconniers pour alimenter un insatiable appétit d’ivoire causant la mort de 20 000 à 30 000 éléphants chaque année…\r\n\r\nLes populations d’éléphants ont chuté dramatiquement aux 19ème et 20ème siècles. Sur le continent africain, l’espèce compte aujourd’hui environ 415 000 individus (contre 3 à 5 millions au début du 20ème siècle). Quant à l’éléphant d’Asie, il est inscrit sur la liste rouge des espèces en danger d’extinction de l’UICN, ses effectifs ayant diminué d’au moins 50% au cours des trois dernières générations. Aujourd’hui, il en resterait moins de 50 000 à l’état sauvage.', NULL, 'published', '2022-09-12 08:33:56', '2022-09-12 16:33:03');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `status` enum('published','pending','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `comments_ibfk_1` (`id_user`),
  KEY `comments_ibfk_2` (`id_post`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `id_post`, `id_user`, `content`, `status`, `created_at`, `updated_at`) VALUES
(3, 3, 2, 'Les jaguars c\'est vraiment dangeureux !', 'rejected', '2022-08-25 15:17:26', '2022-09-08 07:46:01'),
(4, 3, 3, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'published', '2022-08-25 20:25:38', '2022-09-08 07:46:04');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `role`) VALUES
(2, 'Valentin', 'Admin', 'vmoreau@mail.fr', '$2y$10$kGdNY340wPljvtbAag2Spu16uLeiZNuWGjCxZRO2FSUkCZMv2dfWW', 'admin'),
(3, 'Valentin', 'User', 'vmoreau@user.fr', '$2y$10$ARUK7oQT0DGQ9cjB8ymUm.tRSPPhhlRDcIxq0jaPEZn8breuVCIEu', 'user'),
(4, 'Admin', '2', 'admin@admin.fr', '$2y$10$0hLNDXkfLxV9iH.ilp6Zg.EBDoC7e4Ndhf0SqKcqZksHxkLMr5M1W', 'admin'),
(5, 'Valentin', 'Moreau', 'eee.eee@eee.com', '$2y$10$.j/2/yXkRY4QUqMzZOD9iudd0CsTtFDqxt8H3TjDVuQkCnJWJuVxa', 'user'),
(6, 'Valentin', 'Moreau', 'aaa@aaa.aaa', '$2y$10$A7KzObTrvBgTeDrZDy1Udu/RFF9Gw3YTydGJKxPxoLl32pmgiHlKO', 'user'),
(7, 'Jérémie', 'Ossorguine', 'vmoreau@user2.fr', '$2y$10$5ZVdg0ijfNR.WMo7bdX.0e2SuoPTJ/N8Th7z.mB3grPjmgyYUz.E.', 'user'),
(26, 'Val', 'Mor', 'sdfqsdf@qsdsqd.com', '$2y$10$OjHcDQkcE39PThb3SE.N8.f4QkSl7wPbd4mtyP.itRSXshLMIJs.a', 'user'),
(27, 'Vale', 'Valee', 'qsdfdqs@qsd.com', '$2y$10$a5m7Yz6FV.ovixgZ3ZCPYeneT4sMR7srI5V3xv5CqWlgF0tTJ8pf.', 'user'),
(28, 'sdfsdfdf', 'qsdqsdsqd', 'aaaaaaa@aaaaaaa.com', '$2y$10$CDJE3LR3QY.LuTexkKcokunOsGo.o64ElrPLZn8mXqCtOYZS3JO/a', 'user');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
