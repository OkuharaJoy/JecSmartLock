DROP TABLE users CASCADE CONSTRAINTS;
DROP TABLE building CASCADE CONSTRAINTS;
DROP TABLE classroom CASCADE CONSTRAINTS;
DROP TABLE keys CASCADE CONSTRAINTS;
DROP TABLE history CASCADE CONSTRAINTS;


CREATE TABLE users (
    userID INT GENERATED ALWAYS AS IDENTITY NOT NULL,
    name VARCHAR2(255) NOT NULL,
    mail VARCHAR2(255) NOT NULL,
    pass VARCHAR2(255) NOT NULL,
    TEL VARCHAR2(13) NOT NULL,
    permission INT NOT NULL,
    use_period DATE,
    created_at TIMESTAMP default sysdate NOT NULL,
    update_at TIMESTAMP default sysdate NOT NULL
);

ALTER TABLE users ADD CONSTRAINT PK_users PRIMARY KEY (userID);


CREATE TABLE building (
    buildingID INT GENERATED ALWAYS AS IDENTITY NOT NULL,
    address VARCHAR2(255) NOT NULL,
    created_at TIMESTAMP default sysdate NOT NULL,
    update_at TIMESTAMP default sysdate NOT NULL,
    name VARCHAR2(255)
);

ALTER TABLE building ADD CONSTRAINT PK_building PRIMARY KEY (buildingID);


CREATE TABLE classroom (
    classroomID INT GENERATED ALWAYS AS IDENTITY NOT NULL,
    name VARCHAR2(255) NOT NULL,
    buildingID INT,
    userID INT,
    created_at TIMESTAMP default sysdate NOT NULL,
    update_at TIMESTAMP default sysdate NOT NULL
);

ALTER TABLE classroom ADD CONSTRAINT PK_classroom PRIMARY KEY (classroomID);


CREATE TABLE keys (
    keyID INT GENERATED ALWAYS AS IDENTITY NOT NULL,
    model_number VARCHAR2(255) NOT NULL,
    secret_key VARCHAR2(255),
    open_close_status INT NOT NULL,
    classroomID INT,
    Battery_level INT,
    voltage INT,
    angle NUMBER(10, 2),
    door_status INT,
    created_at TIMESTAMP default sysdate NOT NULL,
    update_at TIMESTAMP default sysdate NOT NULL
);

ALTER TABLE keys ADD CONSTRAINT PK_keys PRIMARY KEY (keyID);


CREATE TABLE history (
    historyID NUMBER GENERATED ALWAYS AS IDENTITY NOT NULL,
    open_close_history INT,
    created_at TIMESTAMP default sysdate NOT NULL,
    keyID INT,
    userID INT
);

ALTER TABLE history ADD CONSTRAINT PK_history PRIMARY KEY (historyID);

ALTER TABLE classroom ADD CONSTRAINT FK_classroom_0 FOREIGN KEY (buildingID) REFERENCES building (buildingID);
ALTER TABLE classroom ADD CONSTRAINT FK_classroom_1 FOREIGN KEY (userID) REFERENCES users (userID);

ALTER TABLE keys ADD CONSTRAINT FK_keys_0 FOREIGN KEY (classroomID) REFERENCES classroom (classroomID);

ALTER TABLE history ADD CONSTRAINT FK_history_0 FOREIGN KEY (userID) REFERENCES users (userID);
ALTER TABLE history ADD CONSTRAINT FK_history_1 FOREIGN KEY (keyID) REFERENCES keys (keyID);


