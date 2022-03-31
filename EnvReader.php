<?php

class EnvReader {
    private $arr = null;

    public function __construct($fname = ".env") {
        $handler = fopen($fname, "r");

        if(!$handler)
            return;

        while($buff = fgets($handler)) {
            $splitted = explode("->", $buff);

            $key   = $this->returnStrippedStr($splitted[0]);
            $value = $this->returnStrippedStr($splitted[1]);

            $this->arr[$key] = $value;
        }

        fclose($handler);
    }

    private function returnStrippedStr($str) {
        $str_ids = array( "\"", "'" );

        if(in_array($str[0], $str_ids) or in_array($str[ strlen($str) - 1 ], $str_ids))
            return substr(trim($str), 1, -1);
        return $str;
    }

    public function keyExists($key) {
        return array_key_exists($key, $this->arr);
    }

    public function readValue($key) {
        return $this->keyExists($key) ? $this->arr[$key] : null;
    }
}

?>