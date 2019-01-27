tee oltp_create.log;

use dbs_forum;

drop table if exists tab_mitglied;
create table tab_mitglied (
	id_mitglied int not null,
	name varchar(50) not null,
	rolle varchar(50) not null,
	geburtsdatum date,
	geschlecht varchar(1),
	fk_node bigint unsigned not null,
	primary key(id_mitglied),
	index (fk_node)
);

drop table if exists tab_beitrag;
create table tab_beitrag (
	id_beitrag int not null,
	beitrag varchar(500),
	fk_node bigint unsigned not null,
	primary key(id_beitrag),
	index (fk_node)
);

drop table if exists tab_kommentar;
create table tab_kommentar (
	id_kommentar int not null auto_increment,
	nr int not null,
	kommentar varchar(200),
	fk_node bigint unsigned not null,
	primary key(id_kommentar),
	index (fk_node)
);

drop table if exists oq_backing;
create table oq_backing (
  origid int unsigned not null, 
  destid int unsigned not null,
  rtype int,
  primary key (origid, destid), 
  index (destid)
);

drop table if exists oq_graph;
create table oq_graph engine=OQGRAPH data_table='oq_backing' origid='origid' destid='destid';

create or replace sequence seq_nodeId;

-- import data
LOAD DATA LOCAL INFILE '/home/team21/scripts/Mitglieder.csv' INTO TABLE tab_mitglied FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES SET fk_node = NEXT VALUE FOR seq_nodeId;
LOAD DATA LOCAL INFILE '/home/team21/scripts/Beitraege.csv' INTO TABLE tab_beitrag FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 LINES (id_beitrag, @dummy, beitrag) SET fk_node = NEXT VALUE FOR seq_nodeId;
LOAD DATA LOCAL INFILE '/home/team21/scripts/Kommentare.csv' INTO TABLE tab_kommentar FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES (@dummy, @dummy, nr, kommentar) SET fk_node=NEXT VALUE FOR seq_nodeId;

-- create relations
-- relation type mappings
-- 1 = mitglied -> beitrag
-- 2 = mitglied -> kommentar
-- 3 = beitrag -> kommentar
LOAD DATA LOCAL INFILE '/home/team21/scripts/Beitraege.csv' INTO TABLE oq_backing FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES (destid, origid, @dummy) SET rtype = 1;
update oq_backing set destid = destid + 25 where rtype = 1;

create or replace sequence seq_tmp_kommentarId start with 76;
LOAD DATA LOCAL INFILE '/home/team21/scripts/Kommentare.csv' INTO TABLE oq_backing FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES (@dummy, origid, @dummy, @dummy) SET destid = NEXT VALUE FOR seq_tmp_kommentarId, rtype = 2;

alter sequence seq_tmp_kommentarId restart;
LOAD DATA LOCAL INFILE '/home/team21/scripts/Kommentare.csv' INTO TABLE oq_backing FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES (origid, @dummy, @dummy, @dummy) SET destid = NEXT VALUE FOR seq_tmp_kommentarId, rtype = 3;

update oq_backing set origid = origid + 25 where rtype = 3;
drop sequence seq_tmp_kommentarId;

notee;