CREATE DATABASE vroca_cokolada;
USE vroca_cokolada;
CREATE TABLE Uporabnik (
    UID INT PRIMARY KEY AUTO_INCREMENT,
    Ime VARCHAR(20) NOT NULL UNIQUE,
    Geslo VARCHAR(255) NOT NULL,
    Admin INT NOT NULL
);

CREATE TABLE se_udelezi (
    UID INT NOT NULL,
    Datum DATE NOT NULL,
    Ura INT NOT NULL,
    PRIMARY KEY(UID,Datum,Ura),
    FOREIGN KEY (UID) REFERENCES Uporabnik(UID)
);

CREATE VIEW udelezba AS SELECT Ime, Datum, Ura FROM Uporabnik INNER JOIN se_udelezi USING(UID);