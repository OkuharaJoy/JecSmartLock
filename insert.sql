UPDATE テーブル名
        SET 列名1 = 値1, 列名2 = 値2 …
        WHERE 条件式

DELETE FROM users;
DELETE FROM building;
DELETE FROM classroom;
DELETE FROM keys;
DELETE FROM KEYEVENT;

ALTER TABLE users MODIFY (userID GENERATED ALWAYS AS IDENTITY (START WITH 1)) ;
ALTER TABLE building MODIFY (buildingID GENERATED ALWAYS AS IDENTITY (START WITH 1)) ;
ALTER TABLE classroom MODIFY (classroomID GENERATED ALWAYS AS IDENTITY (START WITH 1)) ;
ALTER TABLE keys MODIFY (keyID GENERATED ALWAYS AS IDENTITY (START WITH 1)) ;
ALTER TABLE KEYEVENT MODIFY (KEYEVENTID GENERATED ALWAYS AS IDENTITY (START WITH 1)) ;

INSERT INTO users (NAME, MAIL, PASS, TEL, PERMISSION) 
                VALUES ('jz2024admin', 'jz2024admin@jec.ac.jp', '6ab568e7d45bbf06148236ea523e9f72eb8e2d505992feeb40231b3ff57e3a8c857b5a13b5c1e9d4b9ce3ef2f44fd2a154b35b216efdc38568f9cc4020443e6f', 
                        '09012341234', 1);
ユーザーID 
jz2024admin@jec.ac.jp
パスワード
jz2024admin

INSERT INTO users (NAME, MAIL, PASS, TEL, PERMISSION) 
                VALUES ('先生', 'teradmin@jec.ac.jp', '74fd6c6b5f6bd9155aff90fd50a86fd316c04d2bb7dc64972535cc0f4382c1824dfc065efda87cc3b525df0f8decd349f170d2a6415d932ec644cc0506a5c62e', 
                        '09046458984', 2);
ユーザーID 
teradmin@jec.ac.jp
パスワード
teradmin

INSERT INTO users (NAME, MAIL, PASS, TEL, PERMISSION) 
                VALUES ('リカルド・イゼクソン・ドス・サントス・レイチ', 'KAKA@ACMilan', '98df07d08beac4311f2a10674336b79177516209946bf0be573cee29c5132d0ae0681101afbef0605d7f0761a9661005d8ffee77d9b264e5dc0ac43ebde64d00', 
                        '09046458984', 3);
ユーザーID 
KAKA@ACMilan
パスワード
Milanista

INSERT INTO users (NAME, MAIL, PASS, TEL, PERMISSION) 
                VALUES ('pass', 'pass', '5b722b307fce6c944905d132691d5e4a2214b7fe92b738920eb3fce3a90420a19511c3010a0e7712b054daef5b57bad59ecbd93b3280f210578f547f4aed4d25', 1);
ユーザーID 
pass 
パスワード
PASS

INSERT INTO building (address, name) 
VALUES ('東京都新宿区百人町1丁目25-4', '本館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区百人町1丁目24-23', '2号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区西新宿7丁目25-18', '3号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区百人町1丁目25-4', '4号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区西新宿7丁目23-27', '5号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区西新宿7丁目24-1', '6号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区北新宿1丁目4-2', '7号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区西新宿7丁目6-3', '8号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区百人町1丁目24-20', '9号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区百人町1丁目24-18', '10号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区百人町1丁目17-18', '11号館');
INSERT INTO building (address, name) 
VALUES ('東京都新宿区西新宿7丁目2-13', '12号館');

insert into CLASSROOM (name, BUILDINGID) VALUES ('1B11', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1B12', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1B13', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('151', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('151', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('152', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('153', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('161', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('162', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('171', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('172', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('181', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('182', 1);
insert into CLASSROOM (name, BUILDINGID) VALUES ('2B1', 2);
insert into CLASSROOM (name, BUILDINGID) VALUES ('211', 2);
insert into CLASSROOM (name, BUILDINGID) VALUES ('221', 2);
insert into CLASSROOM (name, BUILDINGID) VALUES ('231', 2);
insert into CLASSROOM (name, BUILDINGID) VALUES ('241', 2);
insert into CLASSROOM (name, BUILDINGID) VALUES ('251', 2);
insert into CLASSROOM (name, BUILDINGID) VALUES ('3B1A', 3);
insert into CLASSROOM (name, BUILDINGID) VALUES ('3B1B', 3);
insert into CLASSROOM (name, BUILDINGID) VALUES ('321', 3);
insert into CLASSROOM (name, BUILDINGID) VALUES ('322', 3);
insert into CLASSROOM (name, BUILDINGID) VALUES ('331', 3);
insert into CLASSROOM (name, BUILDINGID) VALUES ('332', 3);
insert into CLASSROOM (name, BUILDINGID) VALUES ('341', 3);
insert into CLASSROOM (name, BUILDINGID) VALUES ('411', 4);
insert into CLASSROOM (name, BUILDINGID) VALUES ('412', 4);
insert into CLASSROOM (name, BUILDINGID) VALUES ('421', 4);
insert into CLASSROOM (name, BUILDINGID) VALUES ('422', 4);
insert into CLASSROOM (name, BUILDINGID) VALUES ('431', 4);
insert into CLASSROOM (name, BUILDINGID) VALUES ('432', 4);
insert into CLASSROOM (name, BUILDINGID) VALUES ('441', 4);
insert into CLASSROOM (name, BUILDINGID) VALUES ('511', 5);
insert into CLASSROOM (name, BUILDINGID) VALUES ('521', 5);
insert into CLASSROOM (name, BUILDINGID) VALUES ('531', 5);
insert into CLASSROOM (name, BUILDINGID) VALUES ('541', 5);
insert into CLASSROOM (name, BUILDINGID) VALUES ('621', 6);
insert into CLASSROOM (name, BUILDINGID) VALUES ('631', 6);
insert into CLASSROOM (name, BUILDINGID) VALUES ('641', 6);
insert into CLASSROOM (name, BUILDINGID) VALUES ('651', 6);
insert into CLASSROOM (name, BUILDINGID) VALUES ('661', 6);
insert into CLASSROOM (name, BUILDINGID) VALUES ('671', 6);
insert into CLASSROOM (name, BUILDINGID) VALUES ('681', 6);
insert into CLASSROOM (name, BUILDINGID) VALUES ('691', 6);
insert into CLASSROOM (name, BUILDINGID) VALUES ('7B21', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('7B22', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('7B11', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('7B12', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('7B13', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('7B14', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('711', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('712', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('731', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('732', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('733', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('741', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('742', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('743', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('751', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('752', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('753', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('761', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('762', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('763', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('771', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('772', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('773', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('781', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('782', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('783', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('791', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('792', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('701', 7);
insert into CLASSROOM (name, BUILDINGID) VALUES ('821', 8);
insert into CLASSROOM (name, BUILDINGID) VALUES ('831', 8);
insert into CLASSROOM (name, BUILDINGID) VALUES ('841', 8);
insert into CLASSROOM (name, BUILDINGID) VALUES ('851', 8);
insert into CLASSROOM (name, BUILDINGID) VALUES ('861', 8);
insert into CLASSROOM (name, BUILDINGID) VALUES ('871', 8);
insert into CLASSROOM (name, BUILDINGID) VALUES ('881', 8);
insert into CLASSROOM (name, BUILDINGID) VALUES ('891', 8);
insert into CLASSROOM (name, BUILDINGID) VALUES ('801', 8);
insert into CLASSROOM (name, BUILDINGID) VALUES ('921', 9);
insert into CLASSROOM (name, BUILDINGID) VALUES ('922', 9);
insert into CLASSROOM (name, BUILDINGID) VALUES ('931', 9);
insert into CLASSROOM (name, BUILDINGID) VALUES ('932', 9);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1011', 10);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1012', 10);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1021', 10);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1022', 10);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1031', 10);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1B11', 10);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1112', 11);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1111', 11);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1122', 11);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1121', 11);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1131', 11);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1132', 11);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1231', 12);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1232', 12);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1241', 12);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1242', 12);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1251', 12);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1252', 12);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1261', 12);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1262', 12);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1271', 12);
insert into CLASSROOM (name, BUILDINGID) VALUES ('1272', 12);


INSERT INTO keys (model_number, open_close_status, Battery_level, voltage, angle, door_status, CLASSROOMID) VALUES (2020, 2, 20, 20, 20.2, 2, 2);
INSERT INTO keys (model_number, open_close_status, Battery_level, voltage, angle, door_status, CLASSROOMID) VALUES (3030, 1, 30, 30, 30.3, 1, 3);
INSERT INTO keys (model_number, open_close_status, Battery_level, voltage, angle, door_status, CLASSROOMID) VALUES (4040, 2, 40, 40, 40.4, 2, 4);
INSERT INTO keys (model_number, open_close_status, Battery_level, voltage, angle, door_status, CLASSROOMID) VALUES (5050, 1, 50, 50, 50.5, 1, 5);
INSERT INTO keys (model_number, open_close_status, Battery_level, voltage, angle, door_status, CLASSROOMID) VALUES (6060, 2, 60, 60, 60.6, 2, 6);
INSERT INTO keys (model_number, open_close_status, Battery_level, voltage, angle, door_status, CLASSROOMID) VALUES (7070, 1, 70, 70, 70.7, 1, 7);
INSERT INTO keys (model_number, open_close_status, Battery_level, voltage, angle, door_status, CLASSROOMID) VALUES (8080, 2, 80, 80, 80.8, 2, 8);
INSERT INTO keys (model_number, open_close_status, Battery_level, voltage, angle, door_status, CLASSROOMID) VALUES (9090, 1, 90, 90, 90.9, 1, 9);

INSERT INTO keys (model_number, open_close_status, secret_key, CLASSROOMID) VALUES ('11200416-0103-0702-9000-E400FFFFFFFF', 1, '9228dd1dab927503a0efe99a7726da84', 1);
INSERT INTO keys (model_number, open_close_status, secret_key, CLASSROOMID) VALUES ('1120041E-0507-0609-9C00-EF00FFFFFFFF', 2, '786078c9510b90b14da72cffa57c3559', 2);


INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (2, 2);
INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (1, 3);
INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (2, 4);
INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (1, 5);
INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (2, 6);
INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (1, 7);
INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (2, 8);
INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (1, 9);

INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (1, 1);
INSERT INTO KEYEVENT (open_close_history, keyID) VALUES (2, 1);

