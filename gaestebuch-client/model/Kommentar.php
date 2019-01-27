<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 09.12.2018
 * Time: 15:33
 */

class Kommentar
{
    private $ID;
    private $nr;
    private $kommentar;

    private $nodeID;

    public function __construct($ID)
    {
        $this->ID = $ID;

        $row = mysqlConnect::getInstance()->getKommentar($this->ID);

        $this->nr           = $row['nr'];
        $this->kommentar    = $row['kommentar'];
        $this->nodeID       = $row['fk_node'];
    }

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param mixed $ID
     */
    public function setID($ID): void
    {
        $this->ID = $ID;
    }

    /**
     * @return mixed
     */
    public function getNr()
    {
        return $this->nr;
    }

    /**
     * @param mixed $nr
     */
    public function setNr($nr): void
    {
        $this->nr = $nr;
    }

    /**
     * @return mixed
     */
    public function getKommentar()
    {
        return $this->kommentar;
    }

    /**
     * @param mixed $kommentar
     */
    public function setKommentar($kommentar): void
    {
        $this->kommentar = $kommentar;
    }

    /**
     * @return mixed
     */
    public function getNodeID()
    {
        return $this->nodeID;
    }

    /**
     * @param mixed $nodeID
     */
    public function setNodeID($nodeID): void
    {
        $this->nodeID = $nodeID;
    }

    public function toArray() {
        return array(
            "ID"        => $this->ID,
            "nr"        => $this->nr,
            "kommentar" => $this->kommentar,
            "nodeID"    => $this->nodeID
        );
    }


}