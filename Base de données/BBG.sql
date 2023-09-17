
CREATE DATABASE BBG;

CREATE TABLE visiteur(

	`id_visiteur` int(11) NOT NULL AUTO_INCREMENT,
	`nom` varchar(30) NOT NULL,
	`prenom` varchar(30) NOT NULL,
	`telephone` varchar(30) NOT NULL,
	`numero_rue` int(11),
	`adresse` varchar(30) NOT NULL,
	`ville` varchar(30) NOT NULL,
	`code_postal` int(11) NOT NULL,
	`mail` varchar(30) NOT NULL,
	`mot_de_passe` varchar(40) NOT NULL,
	`status` int(30) NOT NULL,
	`date_inscription` date NOT NULL,
	`profil_pic` varchar(50),
	PRIMARY KEY(`id_visiteur`)

)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE categorie(

	`id_categorie` int(11) NOT NULL AUTO_INCREMENT,
	`nom` varchar(30) NOT NULL,
	PRIMARY KEY(`id_categorie`)

)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE boisson(

	`id_boisson` int(11) NOT NULL AUTO_INCREMENT,
	`intitule` varchar(50) NOT NULL,
	`status` int(10) NOT NULL,
	PRIMARY KEY (`id_boisson`)
	
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE plat(

	`id_plat` int(11) NOT NULL AUTO_INCREMENT,
	`intitule` varchar(30) NOT NULL,
	`ingredient` varchar(50) NOT NULL,
	`prix` float(11) NOT NULL,
	`image` varchar(50),
	`id_categorie` int(11) NOT NULL,
	PRIMARY KEY(`id_plat`),
	CONSTRAINT FK_Plat_Categorie FOREIGN KEY(`id_categorie`)
    REFERENCES categorie(`id_categorie`)
    ON UPDATE CASCADE ON DELETE CASCADE
	
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE commande(

	`id_commande` int(11) NOT NULL AUTO_INCREMENT,
	`id_visiteur` int(11) NOT NULL,
	`prix` float(11) NOT NULL,
	`date` datetime NOT NULL,
	`adresse_livraison` varchar(150) NOT NULL,
	`telephone` varchar(30) NOT NULL,
	`type` varchar(20) NOT NULL,
	PRIMARY KEY(`id_commande`),
	CONSTRAINT FK_Commande_Visiteur FOREIGN KEY(`id_visiteur`)
    REFERENCES visiteur(`id_visiteur`)
    ON UPDATE CASCADE ON DELETE CASCADE 
	
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE sauce(

	`id_sauce` int(11) NOT NULL AUTO_INCREMENT,
	`intitule` varchar(30) NOT NULL,
	`status` int(10) NOT NULL,
	PRIMARY KEY(`id_sauce`)
	
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE commande_details(

	`id_commande` int(11) NOT NULL,
	`id_plat` int(11) NOT NULL,
	`id_boisson` int(11) NOT NULL,
	`id_sauce` int(11) NOT NULL,
	`quantite` int(11),
	`composition` varchar(100),
	`message` varchar(100),

	CONSTRAINT FK_Detail_Commande FOREIGN KEY(`id_commande`)
    REFERENCES commande(`id_commande`)
    ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT FK_Detail_Plat FOREIGN KEY (`id_plat`)
    REFERENCES plat (`id_plat`)
    ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT FK_Detail_Boisson FOREIGN KEY (`id_boisson`)
    REFERENCES boisson (`id_boisson`)
    ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT FK_Detail_Sauce FOREIGN KEY (`id_sauce`)
    REFERENCES sauce (`id_sauce`)
    ON UPDATE CASCADE ON DELETE CASCADE

	
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE reservation (

	`id_reservation` int(11) NOT NULL AUTO_INCREMENT,
	`id_visiteur` int(11) NOT NULL,
	`nbr_personne` int(11) NOT NULL,
	`date` date NOT NULL,
	`heure` time NOT NULL,
	`telephone` varchar(30) NOT NULL,
	PRIMARY KEY (`id_reservation`),
	CONSTRAINT FK_Reservation_Visiteur FOREIGN KEY (`id_visiteur`)
    REFERENCES visiteur (`id_visiteur`)
    ON UPDATE CASCADE ON DELETE CASCADE
	
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE avis(

	`id_avis` int(11) NOT NULL AUTO_INCREMENT,
	`id_visiteur` int(11) NOT NULL,
	`message` varchar(200) NOT NULL,
	`date` date NOT NULL,
	`note` int(11) NOT NULL,
	`status` int(11) NOT NULL,
	PRIMARY KEY(`id_avis`),
	CONSTRAINT FK_Avis_Visiteur FOREIGN KEY(`id_visiteur`)
    REFERENCES visiteur(`id_visiteur`)
    ON UPDATE CASCADE ON DELETE CASCADE
	
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `visiteur` 
(`id_visiteur`,`nom`,`prenom`,`telephone` ,`numero_rue` ,`adresse` ,
	`ville` ,`code_postal` ,`mail` ,`mot_de_passe` ,`status` ,`date_inscription`,`profil_pic`)
	VALUES (1, "Test","Test","XXXXXX", 0, "XXXXXX","XXXXXX", 0, "test@test.com",
					 "ef1621a09d0ce69ea842a00a7f447edd", 1, "2021-01-01","profilpic/no_profil.png");

INSERT INTO `categorie` (`id_categorie`,`nom`)
	VALUES 
	(1, "Sandwich"),
	(2, "Naan"),
	(3, "Assiette"),
	(4, "Burger");

INSERT INTO `plat` (`id_plat`,`intitule`,`ingredient`,`prix`,`image`,`id_categorie`)
	VALUES 
	(1, "Sandwich steack", "3 steack, fromage", 7, "img/s_steak.png", 1),
	(2, "Sandwich massala", "viande poulet curry", 7, "img/s_massala.png", 1),
	(3, "Sandwich tikka", "viande poulet paprika", 7, "img/s_tikka.png", 1),
	(4, "Sandwich american full", "3 steack, tranche de dinde, oeuf", 8, "img/s_american_full.png", 1),
	(5, "Sandwich mexicain", "poulet, sauce chili, poivrons", 7.5, "img/s_mexicain.png", 1),
	(6, "Sandwich tikka steack", "poulet paprika, steack, fromage", 8, "img/s_steak_tikka.png", 1),
	(7, "Sandwich massala steak", "steack, massala, fromage", 8, "img/s_steak_massala.png", 1),
	(8, "Sandwich escalope", "escalope", 7, "img/s_escalope.png", 1),
	(9, "Naan steack", "3 steack, fromage", 8, "img/n_steak.png", 2),
	(10, "Naan massala", "viande poulet curry", 8, "img/n_massala.png", 2),
	(11, "Naan tikka", "viande poulet paprika", 8, "img/n_tikka.png", 2),
	(12, "Naan american full", "3 steack, tranche de dinde, oeuf", 9, "img/n_american_full.png", 2),
	(13, "Naan mexicain", "poulet, sauce chili, poivrons", 8.5, "img/n_mexicain.png", 2),
	(14, "Naan tikka steack", "poulet paprika, steack, fromage", 9, "img/n_steak_tikka.png", 2),
	(15, "Naan massala steak", "steack, massala, fromage", 9, "img/n_massala_steak.png", 2),
	(16, "Naan grec", "viande de grec", 8, "img/n_grec.png", 2),
	(17, "Naan escalope", "escalope", 8, "img/n_escalope.png", 2),
	(18, "Assiette 1 viande", "1 viande aux choix", 8.5, "img/assiette-kebab.png", 3),
	(19, "Assiette 2 viande", "2 viandes aux choix", 9.5, "img/assiette-kebab.png", 3),
	(20, "Assiette 3 viande", "3 viandes aux choix", 10.5, "img/assiette-kebab.png", 3),
	(21, "Cheese", "steack fromage", 4.5, "img/cheese.png", 4),
	(22, "Triple cheese", "3 steack, fromage", 6.5, "img/triple_cheese.png", 4),
	(23, "BBG", "2 steack, fromage, sauce BBG", 6.5, "img/BBG.png", 4),
	(24, "Chicken", "poulet panet, sauce mayo spéciale", 6.5, "img/chicken.png", 4),
	(25, "Chèvre miel", "steack, sauce fromagère, chèvre, miel", 7, "img/chevre_miel.png", 4);

INSERT INTO `boisson` (`id_boisson`,`intitule`,`status`)
	VALUES 
	(1, "Coca",0),
	(2, "Ice tea",0),
	(3, "7up mojito",0),
	(5, "Fanta orange",0), 
	(6, "Fanta citron",0),
	(7, "Oasis tropical",0),
	(8, "Oasis pomme poire",0),
	(9, "Oasis pomme cassis framboise",0),
	(10, "Coca cherry",0),
	(11, "Perrier",0),
	(12, "Sans boisson",0);

INSERT INTO `sauce` (`id_sauce`,`intitule`,`status`)
	VALUES 
	(1, "Ketchup",0),
	(2, "Mayonnaise",0),
	(3, "Biggy",0),
	(4, "Barbecue",0),
	(5, "Algerienne",0),
	(6, "Harissa",0),
	(7, "Sauce maison",0),
	(8, "Sans sauce",0);

