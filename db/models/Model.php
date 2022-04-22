<?php

require_once("DBConnector.php"); // TODO: Change path

abstract class Model {
    public const REL_EQUALS     = 0;
    public const REL_GREATER    = 1;
    public const REL_LOWER      = 2;
    public const REL_GREATEREQ  = 3;
    public const REL_LOWEREQ    = 4;
    public const REL_NOTEQUALS  = 5;

    /**
     * Gets the records matching the given conditions or null if no records are found
     * @param array|null $data array An associative array with format: { "field_name" => { "value_name", RELATION } }
     * ... or null if you want to list all records
     * @return array|null List of Model entities or null if no records found
     */
    abstract public static function findAll(array $data = null);

    /**
     * Insert a new record to the database
     * @param $entity object A Model object with all the required information set
     * @return bool True if insertion was successfull, otherwise returns false
     */
    abstract public static function addNew(object $entity);

    /**
     * Updates this entity in the database by the data stored in object variables
     * @return bool True if update was successfull, otherwise returns false
     */
    abstract public function update();

    /**
     * Returns true if the given entity exists in the database, false if not, null on oci_error
     * @param $data array An associative array with format: { "field_name" => { "value_name", RELATION } }
     */
    public static function existsWith(array $data): bool|null {
        $db = new DBConnector();

        $sqlCmd = self::composeQuery($data);
        $stid = oci_parse($db->getConn(), $sqlCmd);
        $res = oci_execute($stid);

        if(!$res)
            return null;
        return oci_fetch($stid);
    }

    /**
     * @param $data array An associative array with format: { "field_name" => { "value_name", RELATION } }
     * @return string An SQL query string based on the given array
     */
    protected static function composeQuery($data): string {
        $class = strtoupper(static::class);

        if($data == null)
            return "SELECT * FROM " . $class;

        $relation_signs = array(
            self::REL_EQUALS     => "=",
            self::REL_GREATER    => ">",
            self::REL_LOWER      => "<",
            self::REL_GREATEREQ  => ">=",
            self::REL_LOWEREQ    => "<=",
            self::REL_NOTEQUALS  => "!="
        );

        $sqlCmd = "SELECT * FROM " . $class . " WHERE ";
        $c = 0;

        foreach($data as $key => $value) {
            // Format: field_name RELATIONSIGN value_name, example: password = 'pwd'
            $sqlCmd .= $key . " " . $relation_signs[ $value[1] ] . " '" . $value[0] . "'";

            if(++$c != count($data))
                $sqlCmd .= " AND ";
        }
        return $sqlCmd;
    }
}

?>