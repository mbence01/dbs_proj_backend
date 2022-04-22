<?php

require_once("Model.php");

class Mention extends Model {
    private $m_id;
    private $v_id;
    private $u_id;
    private $m_text;
    private $m_like;
    private $m_dislike;
    private $m_parent;
    private $m_pinned;

    public function getMId()
    {
        return $this->m_id;
    }

    public function setMId($m_id)
    {
        $this->m_id = $m_id;
    }

    public function getVId()
    {
        return $this->v_id;
    }

    public function setVId($v_id)
    {
        $this->v_id = $v_id;
    }

    public function getUId()
    {
        return $this->u_id;
    }

    public function setUId($u_id)
    {
        $this->u_id = $u_id;
    }

    public function getMText()
    {
        return $this->m_text;
    }

    public function setMText($m_text)
    {
        $this->m_text = $m_text;
    }

    public function getMLike()
    {
        return $this->m_like;
    }

    public function setMLike($m_like)
    {
        $this->m_like = $m_like;
    }

    public function getMDislike()
    {
        return $this->m_dislike;
    }

    public function setMDislike($m_dislike)
    {
        $this->m_dislike = $m_dislike;
    }

    public function getMParent()
    {
        return $this->m_parent;
    }

    public function setMParent($m_parent)
    {
        $this->m_parent = $m_parent;
    }

    public function getMPinned()
    {
        return $this->m_pinned;
    }

    public function setMPinned($m_pinned)
    {
        $this->m_pinned = $m_pinned;
    }


    public static function findAll(array $data = null): array|null {
        $db = new DBConnector();

        $sqlCmd = self::composeQuery($data);
        $stid = oci_parse($db->getConn(), $sqlCmd);
        oci_execute($stid);

        $retArr = array();

        while($rows = oci_fetch_assoc($stid)) {
            $mention = new Mention();

            $mention->setMId($rows["M_ID"]);
            $mention->setVId($rows["V_ID"]);
            $mention->setUId($rows["U_ID"]);
            $mention->setMText($rows["M_TEXT"]);
            $mention->setMLike($rows["M_LIKE"]);
            $mention->setMDislike($rows["M_DISLIKE"]);
            $mention->setMParent($rows["M_PARENT"]);
            $mention->setMPinned($rows["M_PINNED"]);

            array_push($retArr, $mention);
        }
        return (count($retArr) == 0) ? null : $retArr;
    }

    public static function addNew(object $entity): bool {
        $db = new DBConnector();

        $queryStr = "INSERT INTO 
                        MENTION(V_ID, U_ID, M_TEXT, M_PARENT)
                        VALUES(:v_id, :u_id, :m_text, :m_parent)";
        $stid = oci_parse($db->getConn(), $queryStr);

        $v_id       = $entity->getVId();
        $u_id       = $entity->getUId();
        $m_text     = $entity->getMText();
        $m_parent   = $entity->getMParent();

        oci_bind_by_name($stid, ":v_id", $v_id);
        oci_bind_by_name($stid, ":u_id", $u_id);
        oci_bind_by_name($stid, ":m_text", $m_text);
        oci_bind_by_name($stid, ":m_parent", $m_parent);

        return oci_execute($stid);
    }

    public function update() {
        $db = new DBConnector();

        $queryStr = "UPDATE MENTION SET " .
                    "V_ID = :v_id, U_ID = :u_id, M_TEXT = :m_text, M_LIKE = :m_like, " .
                    "M_DISLIKE = :m_dislike, M_PARENT = :m_parent, M_PINNED = :m_pinned " .
                    "WHERE M_ID = :m_id";
        $stid = oci_parse($db->getConn(), $queryStr);

        oci_bind_by_name($stid, ":v_id", $this->v_id);
        oci_bind_by_name($stid, ":u_id", $this->u_id);
        oci_bind_by_name($stid, ":m_text", $this->m_text);
        oci_bind_by_name($stid, ":m_like", $this->m_like);
        oci_bind_by_name($stid, ":m_dislike", $this->m_dislike);
        oci_bind_by_name($stid, ":m_parent", $this->m_parent);
        oci_bind_by_name($stid, ":m_pinned", $this->m_pinned);
        oci_bind_by_name($stid, ":m_id", $this->m_id);

        return oci_execute($stid);
    }
}