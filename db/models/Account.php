<?php

class Account {
    public const REL_EQUALS     = 0;
    public const REL_GREATER    = 1;
    public const REL_LOWER      = 2;
    public const REL_GREATEREQ  = 3;
    public const REL_LOWEREQ    = 4;
    public const REL_NOTEQUALS  = 5;

    private $u_id;
    private $u_username;
    private $u_email;
    private $u_password;
    private $u_public;
    private $u_born_date;
    private $u_profile;
    private $u_reg_date;

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
     * Returns true if the given user exists in the database, false if doesn't, null on oci_error
     */
    public static function existsWith($field, $value, $relation = Account::REL_EQUALS) {
        $db = new DBConnector();

        $relation_signs = array(
            Account::REL_EQUALS     => "=",
            Account::REL_GREATER    => ">",
            Account::REL_LOWER      => "<",
            Account::REL_GREATEREQ  => ">=",
            Account::REL_LOWEREQ    => "<=",
            Account::REL_NOTEQUALS  => "!="
        );

        $stid = oci_parse($db->getConn(), "SELECT U_ID FROM ACCOUNT WHERE :field :sign :value");

        oci_bind_by_name($stid, ":field", $field);
        oci_bind_by_name($stid, ":sign", $relation_signs[$relation]);
        oci_bind_by_name($stid, ":value", $value);

        $res = oci_execute($stid);

        if(!$res)
            return null;
        return oci_num_rows($stid) > 0;
    }

    public static function addNew($username, $email, $password, $public, $born_date, $profile) {
        $db = new DBConnector();

        $queryStr = "INSERT INTO 
                        ACCOUNT(U_USERNAME, U_EMAIL, U_PASSWORD, U_PUBLIC, U_BORN_DATE, U_PROFILE, U_REG_DATE)
                        VALUES(':username', ':email', ':password', ':public', ':born_date', ':profile')";
        $stid = oci_parse($db->getConn(), $queryStr);

        oci_bind_by_name($stid, ":username", $username);
        oci_bind_by_name($stid, ":email", $email);
        oci_bind_by_name($stid, ":password", $password);
        oci_bind_by_name($stid, ":public", $public);
        oci_bind_by_name($stid, ":born_date", $born_date);
        oci_bind_by_name($stid, ":profile", $profile);

        return oci_execute($stid);
    }
}

?>