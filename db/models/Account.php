<?php

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
     * Creates an Account entity with the array given.
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

    /**
     * Gets the records matching the given conditions or null if no records are found
     * @param $data array An associative array with format: { "field_name" => { "value_name", RELATION } }
     * ... or null if you want to list all records
     * @return array|null List of Account entities or null if no records found
     */
    public static function findAll($data = null): array|null {
        $db = new DBConnector();

        $sqlCmd = self::composeQuery($data);
        $stid = oci_parse($db->getConn(), $sqlCmd);
        oci_execute($stid);

        if(oci_num_rows($stid) == 0)
            return null;

        $retArr = array();

        while($rows = oci_fetch_assoc($stid)) {
            $acc = new Account();

            $acc->setUId($rows["u_id"]);
            $acc->setUUsername($rows["u_username"]);
            $acc->setUPassword($rows["u_password"]);
            $acc->setUPublic($rows["u_public"]);
            $acc->setUEmail($rows["u_email"]);
            $acc->setUBornDate($rows["u_born_date"]);
            $acc->setUProfile($rows["u_profile"]);
            $acc->setURegDate($rows["u_reg_date"]);

            array_push($retArr, $acc);
        }
        return $retArr;
    }

    /**
     * Insert a new record to the database
     * @param $entity object An Account object with all required information set
     * @return bool True if insertion was successfull, otherwise returns false
     */
    public static function addNew(object $entity): bool {
        $db = new DBConnector();

        $queryStr = "INSERT INTO 
                        ACCOUNT(U_USERNAME, U_EMAIL, U_PASSWORD, U_PUBLIC, U_BORN_DATE, U_PROFILE, U_REG_DATE)
                        VALUES(':username', ':email', ':password', ':public', ':born_date', ':profile')";
        $stid = oci_parse($db->getConn(), $queryStr);

        oci_bind_by_name($stid, ":username", $entity->getUUsername());
        oci_bind_by_name($stid, ":email", $entity->getUEmail());
        oci_bind_by_name($stid, ":password", $entity->getUPassword());
        oci_bind_by_name($stid, ":public", $entity->getUPublic());
        oci_bind_by_name($stid, ":born_date", $entity->getUBornDate());
        oci_bind_by_name($stid, ":profile", $entity->getUProfile());

        return oci_execute($stid);
    }
}

?>