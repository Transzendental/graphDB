<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 09.12.2018
 * Time: 15:32
 */

class Mitglied
{
    private $ID;
    private $name;
    private $rolle;
    private $geburtsdatum;
    private $geschlecht;

    private $nodeID;

    public function __construct($ID)
    {
        $this->ID = $ID;

        $row = mysqlConnect::getInstance()->getMitglied($this->ID);

        $this->name         = $row['name'];
        $this->rolle        = $row['rolle'];
        $this->geburtsdatum = $row['geburtsdatum'];
        $this->geschlecht   = $row['geschlecht'];
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRolle()
    {
        return $this->rolle;
    }

    /**
     * @param mixed $rolle
     */
    public function setRolle($rolle): void
    {
        $this->rolle = $rolle;
    }

    /**
     * @return mixed
     */
    public function getGeburtsdatum()
    {
        return $this->geburtsdatum;
    }

    /**
     * @param mixed $geburtsdatum
     */
    public function setGeburtsdatum($geburtsdatum): void
    {
        $this->geburtsdatum = $geburtsdatum;
    }

    /**
     * @return mixed
     */
    public function getGeschlecht()
    {
        return $this->geschlecht;
    }

    /**
     * @param mixed $geschlecht
     */
    public function setGeschlecht($geschlecht): void
    {
        $this->geschlecht = $geschlecht;
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
            "ID"            => $this->ID,
            "name"          => $this->name,
            "rolle"         => $this->rolle,
            "geburtsdatum"  => $this->geburtsdatum,
            "geschlecht"    => $this->geschlecht,
            "nodeID"        => $this->nodeID
        );
    }
}