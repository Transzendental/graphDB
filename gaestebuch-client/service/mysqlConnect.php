<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 09.12.2018
 * Time: 09:53
 */

class mysqlConnect {
    private $mysqli;
    private static $instance;

    private static $TAB_MITGLIED    = "tab_mitglied";
    private static $TAB_BEITRAG     = "tab_beitrag";
    private static $TAB_KOMMENTAR   = "tab_kommentar";

    private static $ID_MITGLIED     = "id_mitglied";
    private static $ID_BEITRAG      = "id_beitrag";
    private static $ID_KOMMENTAR    = "id_kommentar";

    private static $REL_MITGLIED_VERFASST_BEITRAG   = 1;
    private static $REL_MITGLIED_VERFASST_KOMMENTAR = 2;
    private static $REL_BEITRAG_WIRD_KOMMENTIERT    = 3;

    private function __construct()
    {
        //php.net/manual/de/function.mysqli-connect.php
        $this->mysqli = new mysqli("localhost", "team21", "team21.c017", "dbs_forum");
    }

    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new mysqlConnect();
        }
        return self::$instance;
    }

    private function get($tab, $tabid, $id) {
        $result = $this->mysqli->query("SELECT * FROM ".$tab." WHERE ".$tabid." = ".$id);

        return $result->fetch_assoc();
    }

    public function getMitglied($id) {
        return $this->get(self::$TAB_MITGLIED, self::$ID_MITGLIED, $id);
    }

    public function getBeitrag($id) {
        return $this->get(self::$TAB_BEITRAG, self::$ID_BEITRAG, $id);
    }

    public function getKommentar($id) {
        return $this->get(self::$TAB_KOMMENTAR, self::$ID_KOMMENTAR, $id);
    }

    private function getID($tab, $nodeID) {
        $result = $this->mysqli->query("SELECT * FROM ".$tab." WHERE fk_node = ".$nodeID);

        $row = $result->fetch_assoc();
        if($row == null) {
            return null;
        }
        return array_shift($row);
    }

    public function getMitgliedID($nodeID) {
        return $this->getID(self::$TAB_MITGLIED, $nodeID);
    }

    public function getBeitragID($nodeID) {
        return $this->getID(self::$TAB_BEITRAG, $nodeID);
    }

    public function getKommentarID($nodeID) {
        return $this->getID(self::$TAB_KOMMENTAR, $nodeID);
    }

    public function getNodeType($nodeID) {
        if($this->getMitgliedID($nodeID) != null) {
            return "Mitglied";
        }

        if($this->getBeitragID($nodeID) != null) {
            return "Beitrag";
        }

        if($this->getKommentarID($nodeID) != null) {
            return "Kommentar";
        }
        return null;
    }

    private function getKanten($origID, $typ) {
        $result = $this->mysqli->query("SELECT * FROM oq_backing WHERE origid = ".$origID." AND rtype = ".$typ);

        $return = array();
        while ($row = $result->fetch_assoc()) {
            $return[] = $row["destid"];
        }
        return $return;
    }

    private function getKantenBackward($destID, $typ) {
        $result = $this->mysqli->query("SELECT * FROM oq_backing WHERE destid = ".$destID." AND rtype = ".$typ);

        $return = array();
        while ($row = $result->fetch_assoc()) {
            $return[] = $row["origid"];
        }
        return $return;
    }

    public function getBeitraegeByMitglied($mitglied) {
        $nodeIDs = $this->getKanten($mitglied, self::$REL_MITGLIED_VERFASST_BEITRAG);
        $return = array();
        foreach($nodeIDs AS $nodeID) {
            //Hier findet ein Anwendungs-Join statt
            $return[] = $this->getBeitragID($nodeID);
        }
        return $return;
    }

    public function getKommentareByMitglied($mitglied) {
        $nodeIDs = $this->getKantenBackward($mitglied, self::$REL_MITGLIED_VERFASST_KOMMENTAR);
        $return = array();
        foreach($nodeIDs AS $nodeID) {
            //Hier findet ein Anwendungs-Join statt
            $kommentarID = $this->getKommentarID($nodeID);
            if($kommentarID != null && $kommentarID != "") {
                $return[] = $kommentarID;
            }
        }
        return $return;
    }

    public function getKommentareByBeitrag($beitrag) {
        $nodeIDs = $this->getKanten($beitrag, self::$REL_BEITRAG_WIRD_KOMMENTIERT);
        $return = array();
        foreach($nodeIDs AS $nodeID) {
            //Hier findet ein Anwendungs-Join statt
            $return[] = $this->getKommentarID($nodeID);
        }
        return $return;
    }

    public function getMitgliedByBeitrag($beitrag) {
        $nodeID = $this->getKantenBackward($beitrag, self::$REL_MITGLIED_VERFASST_BEITRAG);
        //Hier findet ein Anwendungs-Join statt
        return $this->getMitgliedID($nodeID[0]);
    }

    public function getMitgliedByKommentar($kommentar) {
        $nodeID = $this->getKanten($kommentar, self::$REL_MITGLIED_VERFASST_KOMMENTAR);
        //Hier findet ein Anwendungs-Join statt
        return $this->getMitgliedID($nodeID[0]);
    }

    public function getBeitragByKommentar($kommentar) {
        $nodeID = $this->getKantenBackward($kommentar, self::$REL_BEITRAG_WIRD_KOMMENTIERT);
        //Hier findet ein Anwendungs-Join statt
        return $this->getBeitragID($nodeID[0]);
    }

    private function getAll($tab, $tabid) {
        $result = $this->mysqli->query("SELECT ".$tabid." FROM ".$tab);

        $return = array();
        while($row = $result->fetch_assoc()) {
            $return[] = $row[$tabid];
        }
        return $return;
    }

    public function getBeitraege() {
        return $this->getAll(self::$TAB_BEITRAG, self::$ID_BEITRAG);
    }

    public function getMitglieder() {
        return $this->getAll(self::$TAB_MITGLIED, self::$ID_MITGLIED);
    }

    public function getKantenTyp($typ) {
        switch ($typ) {
            case self::$REL_BEITRAG_WIRD_KOMMENTIERT:
                return "Beitrag wird kommentiert";
            case self::$REL_MITGLIED_VERFASST_BEITRAG:
                return "Mitglied verfasst Beitrag";
            case self::$REL_MITGLIED_VERFASST_KOMMENTAR:
                return "Mitglied verfasst Kommentar";
        }
        return null;
    }

    public function kantenCheck($origid, $destid, $typ) {
        $origid = $this->getNodeType($origid);
        $destid = $this->getNodeType($destid);

        return (
            ($typ == self::$REL_MITGLIED_VERFASST_KOMMENTAR && $origid == "Kommentar" && $destid == "Mitglied") ||
            ($typ == self::$REL_MITGLIED_VERFASST_BEITRAG && $origid == "Mitglied" && $destid == "Beitrag") ||
            ($typ == self::$REL_BEITRAG_WIRD_KOMMENTIERT && $origid == "Beitrag" && $destid == "Kommentar")
        );
    }

    public function getAllKanten() {
        $result = $this->mysqli->query("SELECT * FROM oq_backing");

        while($row = $result->fetch_assoc()) {
            if(!$this->kantenCheck($row['origid'], $row['destid'], $row['rtype'])) {
                echo $this->getNodeType($row['origid']) . " (" . $row['origid'] . ")";
                echo " zu ";
                echo $this->getNodeType($row['destid']) . " (" . $row['destid'] . ")";
                echo " " . $this->getKantenTyp($row['rtype']);
                echo "<br>

";
            }
        }
    }

    public function createBeitrag($mitgliedNodeID, $beitrag) {
        $result = $this->mysqli->query("SELECT NEXT VALUE FOR seq_nodeId");
        $beitragNodeID = array_shift($result->fetch_assoc());

        $this->mysqli->query("INSERT INTO ".self::$TAB_BEITRAG." (beitrag, fk_node) VALUES ('".$beitrag."', ".$beitragNodeID.")");
        $this->mysqli->query("INSERT INTO oq_backing (origid, destid, rtype) VALUES (".$mitgliedNodeID.",".$beitragNodeID.",".self::$REL_MITGLIED_VERFASST_BEITRAG.")");
    }

    public function createKommentar($mitgliedNodeID, $kommentar, $nr, $beitragNodeID) {
        $result = $this->mysqli->query("SELECT NEXT VALUE FOR seq_nodeId");
        $kommentarNodeID = array_shift($result->fetch_assoc());

        $this->mysqli->query("INSERT INTO ".self::$TAB_KOMMENTAR." (nr, kommentar, fk_node) VALUES (".$nr.",'".$kommentar."',".$kommentarNodeID.")");
        $this->mysqli->query("INSERT INTO oq_backing (origid, destid, rtype) VALUES (".$beitragNodeID.",".$kommentarNodeID.",".self::$REL_BEITRAG_WIRD_KOMMENTIERT.")");
        $this->mysqli->query("INSERT INTO oq_backing (origid, destid, rtype) VALUES (".$kommentarNodeID.",".$mitgliedNodeID.",".self::$REL_MITGLIED_VERFASST_KOMMENTAR.")");
    }

    public function createMitglied($name, $rolle, $geburtsdatum, $geschlecht) {
        $result = $this->mysqli->query("SELECT NEXT VALUE FOR seq_nodeId");
        $mitgliedsNodeID = array_shift($result->fetch_assoc());

        $this->mysqli->query("INSERT INTO ".self::$TAB_MITGLIED." (name, rolle, geburtsdatum, geschlecht, fk_node) VALUES ('".$name."', '".$rolle."', '".$geburtsdatum."', '".$geschlecht."', ".$mitgliedsNodeID.")");
    }

    private function delete($tab, $nodeID) {
        $this->mysqli->query("DELETE FROM ".$tab." WHERE fk_node = ".$nodeID);
    }

    public function deleteBeitrag($nodeID) {
        $kommentarNodeIDs = $this->getKanten($nodeID, self::$REL_BEITRAG_WIRD_KOMMENTIERT);
        $this->mysqli->query("DELETE FROM oq_backing WHERE origid = ".$nodeID." OR destid = ".$nodeID);
        foreach($kommentarNodeIDs AS $kommentarNodeID) {
            $this->deleteKommentar($kommentarNodeID);
        }
        $this->delete(self::$TAB_BEITRAG, $nodeID);
    }

    public function deleteKommentar($nodeID) {
        $this->mysqli->query("DELETE FROM oq_backing WHERE destid = ".$nodeID." OR origid = ".$nodeID);
        $this->delete(self::$TAB_KOMMENTAR, $nodeID);
    }

    public function deleteMitglied($nodeID) {
        $beitragNodeIDs = $this->getKanten($nodeID, self::$REL_MITGLIED_VERFASST_BEITRAG);
        foreach($beitragNodeIDs AS $beitragNodeID) {
            $this->deleteBeitrag($beitragNodeID);
        }

        $kommentarNodeIDs = $this->getKanten($nodeID, self::$REL_MITGLIED_VERFASST_KOMMENTAR);
        foreach($kommentarNodeIDs AS $kommentarNodeID) {
            $this->deleteKommentar($kommentarNodeID);
        }

        $this->delete(self::$TAB_MITGLIED, $nodeID);
    }

    public function updateMitglied($nodeID, $name, $rolle, $geburtsdatum, $geschlecht) {
        if($geburtsdatum == "") {$geburtsdatum = "null";} else {$geburtsdatum = "'".$geburtsdatum."'";}
        $this->mysqli->query("UPDATE ".self::$TAB_MITGLIED." SET name = '".$name."', rolle = '".$rolle."', geburtsdatum = ".$geburtsdatum.", geschlecht = '".$geschlecht."' WHERE fk_node = ".$nodeID);
    }

    public function updateBeitrag($nodeID, $mitgliedNodeID, $beitrag) {
        $this->mysqli->query("UPDATE oq_backing SET origid = ".$mitgliedNodeID." WHERE destid = ".$nodeID." AND rtype = ".self::$REL_MITGLIED_VERFASST_BEITRAG);
        $this->mysqli->query("UPDATE ".self::$TAB_BEITRAG." SET beitrag = '".$beitrag."' WHERE fk_node = ".$nodeID);
    }

    public function updateKommentar($nodeID, $mitgliedNodeID, $nr, $kommentar) {
        $this->mysqli->query("UPDATE oq_backing SET origid = ".$nodeID." WHERE destid = ".$mitgliedNodeID." AND rtype = ".self::$REL_MITGLIED_VERFASST_KOMMENTAR);
        $this->mysqli->query("UPDATE ".self::$TAB_KOMMENTAR." SET nr = ".$nr.", kommentar = '".$kommentar."' WHERE fk_node = ".$nodeID);
    }

    public function getKommentierteMitglieder($nodeID) {
        $result = $this->mysqli->query("SELECT b3.destid AS 'mitgliedNodeID', count(*) AS 'count' FROM oq_backing b1
INNER JOIN oq_backing b2
ON b1.destid = b2.origid
INNER JOIN oq_backing b3
ON b2.destid = b3.origid
WHERE b1.rtype = 1 AND b2.rtype = 3 AND b3.rtype = 2 AND b1.origid = ".$nodeID."
GROUP BY b3.destid");

        $return = array();
        while($row = $result->fetch_assoc()) {
            $return[] = array(
                "mitgliedNodeID"    => $row["mitgliedNodeID"],
                "count"             => $row["count"]
            );
        }

        return $return;
    }

    public function getMitgliederKommentiert($nodeID) {
        $result = $this->mysqli->query("SELECT b1.origid AS 'mitgliedNodeID', count(*) AS 'count' FROM oq_backing b1
INNER JOIN oq_backing b2
ON b1.destid = b2.origid
INNER JOIN oq_backing b3
ON b2.destid = b3.origid
WHERE b1.rtype = 1 AND b2.rtype = 3 AND b3.rtype = 2 AND b3.destid = ".$nodeID."
GROUP BY b1.origid");

        $return = array();
        while($row = $result->fetch_assoc()) {
            $return[] = array(
                "mitgliedNodeID"    => $row["mitgliedNodeID"],
                "count"             => $row["count"]
            );
        }

        return $return;
    }

    /*
     * //Bekomme von Mitglied kommentierte Mitglieder
SELECT * FROM oq_graph WHERE latch='breadth_first' AND origid=3 AND weight = 3;
//Entsprechende Gesamtanzahl Kommentare
SELECT * FROM oq_graph WHERE latch='breadth_first' AND origid=3 AND weight = 2;


//Bekomme von Mitglieder, die Mitglied kommentiert haben
SELECT * FROM oq_graph WHERE latch='breadth_first' AND destid=3 AND weight = 3;
//Entsprechende Gesamtanzahl Kommentare
SELECT count(*) AS 'count' FROM oq_graph WHERE latch='breadth_first' AND destid=3 AND weight = 2;
     *
     *
     */

    private function __destruct()
    {
        $this->mysqli->close();
    }
}