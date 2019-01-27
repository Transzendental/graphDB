create procedure AnzahlBeziehungen()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  WHILE (i < 5) DO
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;
      SELECT COUNT(*) FROM oq_backing;
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
    SET i = i+1;
  END WHILE;

  INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), 'Anzahl der Beziehungen', NOW(), (SELECT COUNT(*) FROM measurements));
END;

create procedure DurchschnittlicheDauerBeziehungen()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  WHILE (i < 5) DO
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;
      SELECT AVG(DATEDIFF(CURRENT_DATE, date)) FROM oq_backing;
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
    SET i = i+1;
  END WHILE;

  INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), 'Durchschnittliche Dauer der Beziehungen', NOW(), (SELECT COUNT(*) FROM measurements));
END;

create procedure DurchschnittlichesAlter()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  WHILE (i < 5) DO
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;
      SELECT AVG(YEAR(NOW()) - YEAR(birth)) FROM profil;
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
    SET i = i+1;
  END WHILE;

  INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), 'Durchschnittliches Alter', NOW(), (SELECT COUNT(*) FROM measurements));
END;

create procedure RelAnzahlNutzer()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  WHILE (i < 5) DO
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;
      SELECT COUNT(id) FROM profil;
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
    SET i = i+1;
  END WHILE;

  INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), 'Relational: Anzahl Nutzer', NOW(), (SELECT COUNT(*) FROM measurements));
END;

create procedure RelNutzerPk()
BEGIN
  DECLARE c INT;
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE x INT;
  DECLARE i INT DEFAULT 0;
  DECLARE a INT;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  SELECT count(*) INTO c FROM profil;

  WHILE (i < 5) DO
    SET x = 1;
    WHILE (x < c) DO
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;
      SELECT id FROM profil WHERE id = x;
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);

      SET x = x+1000;
    END WHILE;

    SET i = i+1;
  END WHILE;

  SELECT AVG(time) INTO a FROM measurements;
  INSERT INTO analysis VALUES (NULL, a, 'Relational: Nutzer anhand des Primärschlüssels', NOW(), (select count(*) from measurements));
END;

create procedure RelVollNutzerNPk()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE ta INT;
  DECLARE i INT DEFAULT 0;
  DECLARE v VARCHAR(20);
  DECLARE done INT DEFAULT FALSE;
  DECLARE c CURSOR FOR SELECT DISTINCT last FROM profil WHERE RAND()<=0.001;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done=TRUE;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  WHILE (i < 5) DO
    OPEN c;
    country_loop: LOOP
      FETCH c INTO v;
      IF done THEN
        LEAVE country_loop;
      END IF;

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;
      SELECT * FROM profil WHERE last = v LIMIT 1;
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
    END LOOP;
    SET i=i+1;
    CLOSE c;
  END WHILE;

  SELECT AVG(time) INTO ta FROM measurements;
  INSERT INTO analysis VALUES (NULL, ta, 'Relational: Vollständiger Nutzer anhand eines Nicht-Primärschlüssels', NOW(), (select count(*) from measurements));
END;

create procedure RelVollNutzerNPkI()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE ta INT;
  DECLARE i INT DEFAULT 0;
  DECLARE v VARCHAR(2);
  DECLARE done INT DEFAULT FALSE;
  DECLARE c CURSOR FOR SELECT DISTINCT country FROM profil;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done=TRUE;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  WHILE (i < 5) DO
    OPEN c;
    country_loop: LOOP
      FETCH c INTO v;
      IF done THEN
        LEAVE country_loop;
      END IF;

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;
      SELECT * FROM profil WHERE country = v LIMIT 1;
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
    END LOOP;
    SET i=i+1;
    CLOSE c;
  END WHILE;

  SELECT AVG(time) INTO ta FROM measurements;
  INSERT INTO analysis VALUES (NULL, ta, 'Relational: Vollständiger Nutzer anhand eines indexierten Nicht-Primärschlüssels', NOW(), (select count(*) from measurements));
END;

create procedure RelVollNutzerPk()
BEGIN
  DECLARE c INT;
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE x INT;
  DECLARE i INT DEFAULT 0;
  DECLARE a INT;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  SELECT count(*) INTO c FROM profil;

  WHILE (i < 5) DO
    SET x = 1;
    WHILE (x < c) DO
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;
      SELECT * FROM profil WHERE id = x;
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);

      SET x = x+1000;
    END WHILE;

    SET i = i+1;
  END WHILE;

  SELECT AVG(time) INTO a FROM measurements;
  INSERT INTO analysis VALUES (NULL, a, 'Relational: Vollständiger Nutzer anhand des Primärschlüssels', NOW(), (select count(*) from measurements));
END;

create procedure Transitiv()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;
  DECLARE w INT DEFAULT 1;

  WHILE (w < 6) DO
    DROP TEMPORARY TABLE IF EXISTS measurements;
    CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);
    SET i = 0;

    WHILE (i < 5) DO
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;

      SELECT * FROM oq_graph WHERE latch='breadth_first' AND origid=510760 AND weight <= w AND weight>0;

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
      SET i = i+1;
    END WHILE;

    INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), CONCAT('Traversierung Rekursionsstufe ',w), NOW(), (SELECT COUNT(*) FROM measurements));
    SET w = w+1;
  END WHILE;
END;

create procedure TransitivAusland()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;
  DECLARE w INT DEFAULT 1;

  WHILE (w < 6) DO
    DROP TEMPORARY TABLE IF EXISTS measurements;
    CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);
    SET i = 0;

    WHILE (i < 5) DO
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;

      SELECT * FROM oq_graph_ausland WHERE latch='breadth_first' AND origid=6 AND weight <= w AND weight>0;

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
      SET i = i+1;
    END WHILE;

    INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), CONCAT('Traversierung Ausland Rekursionsstufe ',w), NOW(), (SELECT COUNT(*) FROM measurements));
    SET w = w+1;
  END WHILE;
END;

create procedure TransitivAuslandVorbereitung()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;
  DECLARE sID INT DEFAULT 6;
  DECLARE land VARCHAR(2);
  SET @land = 'BE';

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  WHILE (i < 5) DO
      DROP TABLE IF EXISTS oq_backing_ausland;
      DROP TABLE IF EXISTS oq_graph_ausland;

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;

      CREATE TABLE oq_backing_ausland (
        origid INT UNSIGNED NOT NULL,
        destid INT UNSIGNED NOT NULL,
        PRIMARY KEY (origid, destid),
        KEY (destid)
      );

      INSERT INTO oq_backing_ausland (origid, destid)
      SELECT DISTINCT * FROM
        (SELECT b1.origid, b1.destid
        FROM oq_backing b1
        INNER JOIN profil p1 ON b1.destid = p1.id
        WHERE p1.country <> @land
        UNION
        SELECT b2.origid, b2.destid
        FROM oq_backing b2
        INNER JOIN profil p2 ON b2.origid = p2.id
        WHERE p2.country <> @land
        UNION
        SELECT b3.origid, b3.destid
        FROM oq_backing b3
        INNER JOIN profil p3 ON b3.origid = p3.id
        WHERE p3.id = sID
        UNION
        SELECT b4.origid, b4.destid
        FROM oq_backing b4
        INNER JOIN profil p4 ON b4.destid = p4.id
        WHERE p4.id = sID) s;

      CREATE TABLE oq_graph_ausland (
        latch VARCHAR(32) NULL,
        origid BIGINT UNSIGNED NULL,
        destid BIGINT UNSIGNED NULL,
        weight DOUBLE NULL,
        seq BIGINT UNSIGNED NULL,
        linkid BIGINT UNSIGNED NULL,
        KEY (latch, origid, destid) USING HASH,
        KEY (latch, destid, origid) USING HASH
      )
      ENGINE=OQGRAPH
      data_table='oq_backing_ausland' origid='origid' destid='destid';

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
    SET i = i+1;
  END WHILE;

  INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), 'Traversierung Ausland Vorbereitung', NOW(), (SELECT COUNT(*) FROM measurements));
END;

create procedure TransitivBusiness()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;
  DECLARE w INT DEFAULT 1;

  WHILE (w < 6) DO
    DROP TEMPORARY TABLE IF EXISTS measurements;
    CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);
    SET i = 0;
    
    WHILE (i < 5) DO
      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;

      SELECT * FROM oq_graph_business WHERE latch='breadth_first' AND origid=6 AND weight <= w AND weight>0;

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
      SET i = i+1;
    END WHILE;

    INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), CONCAT('Traversierung Business Rekursionsstufe ',w), NOW(), (SELECT COUNT(*) FROM measurements));
    SET w = w+1;
  END WHILE;
END;

create procedure TransitivBusinessVorbereitung()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  WHILE (i < 5) DO
      DROP TABLE IF EXISTS oq_backing_business;
      DROP TABLE IF EXISTS oq_graph_business;

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;

      CREATE TABLE oq_backing_business (
        origid INT UNSIGNED NOT NULL,
        destid INT UNSIGNED NOT NULL,
        PRIMARY KEY (origid, destid),
        KEY (destid)
      ) AS SELECT * FROM oq_backing WHERE rtype = "BUSINE";

      CREATE TABLE oq_graph_business (
        latch VARCHAR(32) NULL,
        origid BIGINT UNSIGNED NULL,
        destid BIGINT UNSIGNED NULL,
        weight DOUBLE NULL,
        seq BIGINT UNSIGNED NULL,
        linkid BIGINT UNSIGNED NULL,
        KEY (latch, origid, destid) USING HASH,
        KEY (latch, destid, origid) USING HASH
      )
      ENGINE=OQGRAPH
      data_table='oq_backing_business' origid='origid' destid='destid';

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
    SET i = i+1;
  END WHILE;

  INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), 'Traversierung Business Vorbereitung', NOW(), (SELECT COUNT(*) FROM measurements));
END;

create procedure TransitivVorbereitung()
BEGIN
  DECLARE t1 BIGINT;
  DECLARE t2 BIGINT;
  DECLARE td INT;
  DECLARE i INT DEFAULT 0;

  DROP TEMPORARY TABLE IF EXISTS measurements;
  CREATE TEMPORARY TABLE measurements (id INT PRIMARY KEY AUTO_INCREMENT, time INT);

  WHILE (i < 5) DO
      DROP TABLE IF EXISTS oq_graph;

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t1;

      CREATE TABLE oq_graph (
        latch VARCHAR(32) NULL,
        origid BIGINT UNSIGNED NULL,
        destid BIGINT UNSIGNED NULL,
        weight DOUBLE NULL,
        seq BIGINT UNSIGNED NULL,
        linkid BIGINT UNSIGNED NULL,
        KEY (latch, origid, destid) USING HASH,
        KEY (latch, destid, origid) USING HASH
      )
      ENGINE=OQGRAPH
      data_table='oq_backing' origid='origid' destid='destid';

      SELECT UNIX_TIMESTAMP(CURTIME(6))*1000000 INTO t2;

      SELECT t2-t1 INTO td;

      INSERT INTO measurements VALUES (NULL, td);
    SET i = i+1;
  END WHILE;

  INSERT INTO analysis VALUES (NULL, (SELECT AVG(time) FROM measurements), 'Traversierung Vorbereitung', NOW(), (SELECT COUNT(*) FROM measurements));
END;

