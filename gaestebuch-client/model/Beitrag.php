<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 09.12.2018
 * Time: 15:30
 */

class Beitrag
{
    private $ID;
    private $beitrag;

    private $nodeID;

    private $verfasser;

    public function __construct($ID)
    {
        $this->ID = $ID;

        $row = mysqlConnect::getInstance()->getBeitrag($ID);

        $this->beitrag  = $row['beitrag'];
        $this->nodeID   = $row['fk_node'];
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
    public function getBeitrag()
    {
        return $this->beitrag;
    }

    /**
     * @param mixed $beitrag
     */
    public function setBeitrag($beitrag): void
    {
        $this->beitrag = $beitrag;
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

    /**
     * @return mixed
     */
    public function getVerfasser()
    {
        return $this->verfasser;
    }

    /**
     * @param mixed $verfasser
     */
    public function setVerfasser($verfasser): void
    {
        $this->verfasser = $verfasser;
    }

    public function toArray() {
        return array(
            "ID"            => $this->ID,
            "beitrag"       => $this->beitrag,
            "nodeID"        => $this->nodeID
        );
    }
}