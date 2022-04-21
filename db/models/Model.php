<?php

require_once("DBConnector.php"); // TODO: Change path

abstract class Model {
    public const REL_EQUALS     = 0;
    public const REL_GREATER    = 1;
    public const REL_LOWER      = 2;
    public const REL_GREATEREQ  = 3;
    public const REL_LOWEREQ    = 4;
    public const REL_NOTEQUALS  = 5;

    abstract public static function findAll(array $data = null);
    abstract public static function addNew(object $entity);

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
        return oci_num_rows($stid) > 0;
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