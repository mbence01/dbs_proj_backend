<?php

require_once("Model.php");

class UserLogin extends Model {
    private $ul_id;
    private $u_id;
    private $ul_ipaddr;
    private $ul_agent;
    private $ul_datetime;

    public function getUlId()
    {
        return $this->ul_id;
    }

    public function setUlId($ul_id)
    {
        $this->ul_id = $ul_id;
    }

    public function getUId()
    {
        return $this->u_id;
    }

    public function setUId($u_id)
    {
        $this->u_id = $u_id;
    }

    public function getUlIpaddr()
    {
        return $this->ul_ipaddr;
    }

    public function setUlIpaddr($ul_ipaddr)
    {
        $this->ul_ipaddr = $ul_ipaddr;
    }

    public function getUlAgent()
    {
        return $this->ul_agent;
    }

    public function setUlAgent($ul_agent)
    {
        $this->ul_agent = $ul_agent;
    }

    public function getUlDatetime()
    {
        return $this->ul_datetime;
    }

    public function setUlDatetime($ul_datetime)
    {
        $this->ul_datetime = $ul_datetime;
    }


    public static function findAll(array $data = null): array|null {
        $db = new DBConnector();

        $sqlCmd = self::composeQuery($data);
        $stid = oci_parse($db->getConn(), $sqlCmd);
        oci_execute($stid);

        $retArr = array();

        while($rows = oci_fetch_assoc($stid)) {
            $login = new UserLogin();

            $login->setUlId($rows["ul_id"]);
            $login->setUId($rows["u_id"]);
            $login->setUlIpaddr($rows["ul_ipaddr"]);
            $login->setUlAgent($rows["ul_agent"]);
            $login->setUlDatetime($rows["ul_datetime"]);

            array_push($retArr, $login);
        }
        return (count($retArr) == 0) ? null : $retArr;
    }

    public static function addNew(object $entity): bool {
        $db = new DBConnector();

        $queryStr = "INSERT INTO 
                        USERLOGIN(U_ID, UL_IPADDR, UL_AGENT)
                        VALUES(:u_id, :ul_ipaddr, :ul_agent)";
        $stid = oci_parse($db->getConn(), $queryStr);

        $u_id       = $entity->getUId();
        $ul_ipaddr  = $entity->getUlIpAddr();
        $ul_agent   = $entity->getUlAgent();

        oci_bind_by_name($stid, ":u_id", $u_id);
        oci_bind_by_name($stid, ":ul_ipaddr", $ul_ipaddr);
        oci_bind_by_name($stid, ":ul_agent", $ul_agent);

        return oci_execute($stid);
    }
}