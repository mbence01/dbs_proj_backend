<?php

require_once("Model.php");

class Account extends Model {
    private int     $u_id;
    private string  $u_username;
    private string  $u_email;
    private string  $u_password;
    private int     $u_public;
    private string  $u_born_date;
    private string  $u_profile;
    private string  $u_reg_date;

    /**
     * Creates an Account entity based on the array given.
     * @param $arr Array which contains informations about the entity
     * @return Account Account created with the given array
     */
    public static function fromArray($arr): Account {
        $newAcc = new Account();

        $newAcc->setUUsername($arr["u_username"]);
        $newAcc->setUEmail($arr["u_email"]);
        $newAcc->setUPassword($arr["u_password"]);
        $newAcc->setUPublic($arr["u_public"]);
        $newAcc->setUBornDate($arr["u_born_date"]);
        $newAcc->setUProfile($arr["u_profile"]);

        return $newAcc;
    }

    /**
     * @return mixed
     */
    public function getUId()
    {
        return $this->u_id;
    }

    /**
     * @param mixed $u_id
     */
    public function setUId($u_id): void
    {
        $this->u_id = $u_id;
    }

    /**
     * @return mixed
     */
    public function getUUsername()
    {
        return $this->u_username;
    }

    /**
     * @param mixed $u_username
     */
    public function setUUsername($u_username): void
    {
        $this->u_username = $u_username;
    }

    /**
     * @return mixed
     */
    public function getUEmail()
    {
        return $this->u_email;
    }

    /**
     * @param mixed $u_email
     */
    public function setUEmail($u_email): void
    {
        $this->u_email = $u_email;
    }

    /**
     * @return mixed
     */
    public function getUPassword()
    {
        return $this->u_password;
    }

    /**
     * @param mixed $u_password
     */
    public function setUPassword($u_password): void
    {
        $this->u_password = $u_password;
    }

    /**
     * @return mixed
     */
    public function getUPublic()
    {
        return $this->u_public;
    }

    /**
     * @param mixed $u_public
     */
    public function setUPublic($u_public): void
    {
        $this->u_public = $u_public;
    }

    /**
     * @return mixed
     */
    public function getUBornDate()
    {
        return $this->u_born_date;
    }

    /**
     * @param mixed $u_born_date
     */
    public function setUBornDate($u_born_date): void
    {
        $this->u_born_date = $u_born_date;
    }

    /**
     * @return mixed
     */
    public function getUProfile()
    {
        return $this->u_profile;
    }

    /**
     * @param mixed $u_profile
     */
    public function setUProfile($u_profile): void
    {
        $this->u_profile = $u_profile;
    }

    /**
     * @return mixed
     */
    public function getURegDate()
    {
        return $this->u_reg_date;
    }

    /**
     * @param mixed $u_reg_date
     */
    public function setURegDate($u_reg_date): void
    {
        $this->u_reg_date = $u_reg_date;
    }

    public static function findAll(array $data = null): array|null {
        $db = new DBConnector();

        $sqlCmd = self::composeQuery($data);
        $stid = oci_parse($db->getConn(), $sqlCmd);
        oci_execute($stid);

        $retArr = array();

        while($rows = oci_fetch_assoc($stid)) {
            $acc = new Account();

            $acc->setUId($rows["U_ID"]);
            $acc->setUUsername($rows["U_USERNAME"]);
            $acc->setUPassword($rows["U_PASSWORD"]);
            $acc->setUPublic($rows["U_PUBLIC"]);
            $acc->setUEmail($rows["U_EMAIL"]);
            $acc->setUBornDate($rows["U_BORN_DATE"]);
            $acc->setUProfile($rows["U_PROFILE"]);
            $acc->setURegDate($rows["U_REG_DATE"]);

            array_push($retArr, $acc);
        }
        return (count($retArr) == 0) ? null : $retArr;
    }

    public static function addNew(object $entity): bool {
        $db = new DBConnector();

        $queryStr = "INSERT INTO 
                        ACCOUNT(U_USERNAME, U_EMAIL, U_PASSWORD, U_PUBLIC, U_BORN_DATE, U_PROFILE)
                        VALUES(:username, :email, :password, :u_public, TO_DATE(:born_date, 'YYYY-MM-DD'), :profile)";
        $stid = oci_parse($db->getConn(), $queryStr);

        $u_username     = $entity->getUUsername();
        $u_email        = $entity->getUEmail();
        $u_password     = $entity->getUPassword();
        $u_public       = $entity->getUPublic();
        $u_born_date    = $entity->getUBornDate();
        $u_profile      = $entity->getUProfile();

        oci_bind_by_name($stid, ":username", $u_username);
        oci_bind_by_name($stid, ":email", $u_email);
        oci_bind_by_name($stid, ":password", $u_password);
        oci_bind_by_name($stid, ":u_public", $u_public);
        oci_bind_by_name($stid, ":born_date", $u_born_date);
        oci_bind_by_name($stid, ":profile", $u_profile);

        return oci_execute($stid);
    }

    public function update() {
        $db = new DBConnector();

        $queryStr = "UPDATE ACCOUNT SET " .
            "U_USERNAME = :u_username, U_EMAIL = :u_email, U_PASSWORD = :u_password, " .
            "U_PUBLIC = :u_public, U_BORN_DATE = :u_born_date, U_REG_DATE = :u_reg_date, U_PROFILE = :u_profile " .
            "WHERE U_ID = :u_id";
        $stid = oci_parse($db->getConn(), $queryStr);

        oci_bind_by_name($stid, ":u_username", $this->u_username);
        oci_bind_by_name($stid, ":u_email", $this->u_email);
        oci_bind_by_name($stid, ":u_password", $this->u_password);
        oci_bind_by_name($stid, ":u_public", $this->u_public);
        oci_bind_by_name($stid, ":u_born_date", $this->u_born_date);
        oci_bind_by_name($stid, ":u_reg_date", $this->u_reg_date);
        oci_bind_by_name($stid, ":u_profile", $this->u_profile);
        oci_bind_by_name($stid, ":u_id", $this->u_id);

        return oci_execute($stid);
    }
}

?>