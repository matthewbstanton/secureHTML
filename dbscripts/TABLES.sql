DROP TABLE USERS;
DROP TABLE DOCUMENTDEFINITION;
DROP TABLE DOCUMENTDATA;

CREATE TABLE USERS
(
  USERID INT PRIMARY KEY AUTO_INCREMENT,
  USERNAME VARCHAR(30) UNIQUE,
  PASSCODE VARCHAR(128)
);

CREATE TABLE DOCUMENTDEFINITION
(
  DOCUMENTID INT PRIMARY KEY AUTO_INCREMENT,
  DESCRIPTION VARCHAR(60) UNIQUE
);

CREATE TABLE DOCUMENTDATA
(
  DOCUMENTID INT,
  SECTIONID INT,
  SECURITYGROUP INT,
  SECTIONTEXT TEXT,
  PRIMARY KEY (DOCUMENTID, SECTIONID)
);

CREATE TABLE PERMISSIONDEFINITION
(
  PERMID INT PRIMARY KEY AUTO_INCREMENT,
  PERMNAME VARCHAR(30) UNIQUE
);

CREATE TABLE GROUPPERMISSIONS
(
  GROUPID INT PRIMARY KEY AUTO_INCREMENT,
  GROUPNAME VARCHAR(30) UNIQUE
);

CREATE TABLE GROUPS
(
  GROUPID INT FOREIGN KEY (parent_id) REFERENCES parent(id)
    ON DELETE CASCADE,
  PERMID INT,
  PRIMARY KEY (GROUPID, PERMID)
);