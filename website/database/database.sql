SET FOREIGN_KEY_CHECKS=0;

-- elimina le tabelle se gia' pesenti
DROP TABLE IF EXISTS Utente;
DROP TABLE IF EXISTS Prodotto;
DROP TABLE IF EXISTS Recensione;
DROP TABLE IF EXISTS News;

CREATE TABLE Utente (
	Email VARCHAR(50) PRIMARY KEY,
	Pwd VARCHAR(10) NOT NULL,
	Nome VARCHAR(50) NOT NULL,
	Cognome VARCHAR(50) NOT NULL,
	DataNascita DATE NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Prodotto (
	Codice INT(11) NOT NULL AUTO_INCREMENT,
	Nome VARCHAR(50) NOT NULL,
	TipoProdotto ENUM('Torta','Pasta') NOT NULL,
	Immagine VARCHAR(100),
	Descrizione VARCHAR(500),
	Ingredienti VARCHAR(500),
	PRIMARY KEY (Codice)
	
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Recensione (
	EmailUtente CHAR(50),
	CodiceProdotto INT(11),
	Testo VARCHAR(500),
	Valutazione TINYINT,
	PRIMARY KEY (EmailUtente, CodiceProdotto),
	FOREIGN KEY (CodiceProdotto) REFERENCES Prodotto(Codice) ON DELETE CASCADE,
	FOREIGN KEY (EmailUtente) REFERENCES Utente(Email) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE News (
	Codice INT(11) NOT NULL AUTO_INCREMENT,
	Titolo VARCHAR(50) NOT NULL,
	Contenuto VARCHAR(500) NOT NULL,
	PRIMARY KEY (Codice)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- Popola le tabelle 

LOAD DATA LOCAL INFILE 'Utente.txt' INTO TABLE Utente;
LOAD DATA LOCAL INFILE 'Prodotto.txt' INTO TABLE Prodotto;
LOAD DATA LOCAL INFILE 'News.txt' INTO TABLE News;

SET FOREIGN_KEY_CHECKS=1;
