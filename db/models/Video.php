<?php

require_once("Model.php");

class Video extends Model {
    private $v_id;
    private $v_title;
    private $v_uploader_id;
    private $v_upload_date;
    private $v_duration;
    private $v_description;
    private $v_visibility;
    private $v_thumbnail;
    private $v_filename;

    public function getVId()
    {
        return $this->v_id;
    }

    public function setVId($v_id)
    {
        $this->v_id = $v_id;
    }

    public function getVTitle()
    {
        return $this->v_title;
    }

    public function setVTitle($v_title)
    {
        $this->v_title = $v_title;
    }

    public function getVUploaderId()
    {
        return $this->v_uploader_id;
    }

    public function setVUploaderId($v_uploader_id)
    {
        $this->v_uploader_id = $v_uploader_id;
    }

    public function getVUploadDate()
    {
        return $this->v_upload_date;
    }

    public function setVUploadDate($v_upload_date)
    {
        $this->v_upload_date = $v_upload_date;
    }

    public function getVDuration()
    {
        return $this->v_duration;
    }

    public function setVDuration($v_duration)
    {
        $this->v_duration = $v_duration;
    }

    public function getVDescription()
    {
        return $this->v_description;
    }

    public function setVDescription($v_description)
    {
        $this->v_description = $v_description;
    }

    public function getVVisibility()
    {
        return $this->v_visibility;
    }

    public function setVVisibility($v_visibility)
    {
        $this->v_visibility = $v_visibility;
    }

    public function getVThumbnail()
    {
        return $this->v_thumbnail;
    }

    public function setVThumbnail($v_thumbnail)
    {
        $this->v_thumbnail = $v_thumbnail;
    }

    public function getVFilename()
    {
        return $this->v_filename;
    }

    public function setVFilename($v_filename): void
    {
        $this->v_filename = $v_filename;
    }

    public static function findAll(array $data = null): array|null {
        $db = new DBConnector();

        $sqlCmd = self::composeQuery($data);
        $stid = oci_parse($db->getConn(), $sqlCmd);
        oci_execute($stid);

        if(oci_num_rows($stid) == 0)
            return null;

        $retArr = array();

        while($rows = oci_fetch_assoc($stid)) {
            $video = new Video();

            $video->setVId($rows["v_id"]);
            $video->setVTitle($rows["v_title"]);
            $video->setVUploaderId($rows["v_uploader_id"]);
            $video->setVUploadDate($rows["v_upload_date"]);
            $video->setVDuration($rows["v_duration"]);
            $video->setVDescription($rows["v_description"]);
            $video->setVVisibility($rows["v_visibility"]);
            $video->setVThumbnail($rows["v_thumbnail"]);
            $video->setVFilename($rows["v_filename"]);

            array_push($retArr, $video);
        }
        return $retArr;
    }

    public static function addNew(object $entity): bool {
        $db = new DBConnector();

        $queryStr = "INSERT INTO 
                        VIDEO(V_TITLE, V_UPLOADER_ID, V_DURATION, V_DESCRIPTION, V_VISIBILITY, V_THUMBNAIL, V_FILENAME)
                        VALUES(':v_title', ':uploader_id', ':duration', ':description', ':visibility', ':thumbnail', ':filename')";
        $stid = oci_parse($db->getConn(), $queryStr);

        oci_bind_by_name($stid, ":v_title", $entity->getVTitle());
        oci_bind_by_name($stid, ":uploader_id", $entity->getVUploaderId());
        oci_bind_by_name($stid, ":duration", $entity->getVDuration());
        oci_bind_by_name($stid, ":description", $entity->getVDescription());
        oci_bind_by_name($stid, ":visibility", $entity->getVVisibility());
        oci_bind_by_name($stid, ":thumbnail", $entity->getVThumbnail());
        oci_bind_by_name($stid, ":filename", $entity->getVFilename());

        return oci_execute($stid);
    }

    /**
     * Generates a unique identification string for a video
     * The method checks if the generated string is already in the database
     * @return string Unique identification string
     */
    public static function generateUniqueId(): string {
        static $MAX_SIZE = 32;
        static $abc = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        while(true) {
            $newId = substr(str_shuffle($abc), 0, $MAX_SIZE);

            if(!self::existsWith(array( "v_filename" => array( $newId, self::REL_EQUALS ) )))
                return $newId;
        }
    }
}