CREATE TABLE USERS
(
  USERID INT PRIMARY KEY AUTO_INCREMENT,
  USERNAME VARCHAR(30) UNIQUE,
  PASSCODE VARCHAR(30)
);