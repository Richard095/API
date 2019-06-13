
 CREATE TABLE IF NOT EXISTS users(
    userId INT  AUTO_INCREMENT,
    user_type ENUM('User','Asociacion')NOT NULL DEFAULT 'User',
    fullname VARCHAR(255)NOT NULL,
    user VARCHAR(255)NOT NULL,
    pass VARCHAR(255)NOT NULL,
    phone VARCHAR(255)NULL,
    email VARCHAR(255)NULL,
    addresses VARCHAR(255)NULL,
    descriptions TEXT NULL,
    thumb TEXT NULL,
    created_at TIMESTAMP,
    UNIQUE(user),
    PRIMARY KEY(userId) 
   )ENGINE = InnoDB;


 CREATE TABLE IF NOT EXISTS petpost(
    petpostId INT  AUTO_INCREMENT,
    petname VARCHAR(255)NOT NULL,
    pet_type ENUM('Perro','Gato')NOT NULL DEFAULT 'Perro',
    pet_weight  int NOT NULL,
    gender_pet ENUM('Macho','Hembra')NOT NULL DEFAULT 'Macho',
    vaccinated boolean NOT NULL,
    dewormed boolean NOT NULL,
    healthy boolean NOT NULL,
    sterilized boolean NOT NULL,
    descriptions TEXT NULL,
    post_status boolean NOT NULL,
    postedUserId int NOT NULL,
    created_at TIMESTAMP,
    PRIMARY KEY(petpostId),
    INDEX (postedUserId),
    FOREIGN KEY (postedUserId) REFERENCES users(userId)
   )
   ENGINE = InnoDB;




CREATE TABLE IF NOT EXISTS thumbs(
  thumbId INT AUTO_INCREMENT,
  fromPostId INT NOT NULL,
  urlThumb TEXT NULL,
  thumbNum INT,
  created_at TIMESTAMP,
  PRIMARY KEY(thumbId),
  INDEX (fromPostId),
  FOREIGN KEY (fromPostId) REFERENCES petpost(petpostId)
)ENGINE = InnoDB;


/*Query from serching post with specific Image One*/
/* select p.petname,p.pet_type,p.descriptions,t.urlThumb from petpost p inner join thumbs t ON p.petpostId=t.fromPostId where t.thumbNum=1;
select p.petname,p.descriptions,p.postedUserId,t.fromPostId,t.urlThumb from petpost p inner join thumbs t on p.petpostId=t.fromPostId where t.thumbNum=1 and p.postedUserId=16;
select p.petname,p.descriptions,p.postedUserId,t.fromPostId,t.urlThumb from petpost p inner join thumbs t on p.petpostId=t.fromPostId where t.thumbNum=1 and fromPostId=5;*/
   /*        //mysql> select p.petname,p.descriptions,p.postedUserId,t.fromPostId,t.urlThumb from petpost p inner join thumbs t on p.petpostId=t.fromPostId
*/
   




