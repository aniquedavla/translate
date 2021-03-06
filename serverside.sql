-- USERS Table
CREATE table USERS(
    id SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fullName varchar(255),
    email varchar(255) NOT NULL UNIQUE,
    username varchar(30) NOT NULL UNIQUE,
    hashedPw char(255) NOT NULL,
    salt1 varchar(32)  NOT NULL
    salt2 varchar(3 2) NOT NULL
);

CREATE table TranslationModels(
    username varchar(30) NOT NULL UNIQUE PRIMARY KEY,
    translationName varchar(255),
    fromLanguage varchar(32) NOT NULL, 
    toLanguage varchar(32) NOT NULL,
    translationModel json NOT NULL
);