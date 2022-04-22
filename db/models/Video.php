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

        $retArr = array();

        while($rows = oci_fetch_assoc($stid)) {
            $video = new Video();

            $video->setVId($rows["V_ID"]);
            $video->setVTitle($rows["V_TITLE"]);
            $video->setVUploaderId($rows["V_UPLOADER_ID"]);
            $video->setVUploadDate($rows["V_UPLOAD_DATE"]);
            $video->setVDuration($rows["V_DURATION"]);
            $video->setVDescription($rows["V_DESCRIPTION"]);
            $video->setVVisibility($rows["V_VISIBILITY"]);
            $video->setVThumbnail($rows["V_THUMBNAIL"]);
            $video->setVFilename($rows["V_FILENAME"]);

            array_push($retArr, $video);
        }
        return (count($retArr) == 0) ? null : $retArr;
    }

    public static function addNew(object $entity): bool {
        $db = new DBConnector();

        $queryStr = "INSERT INTO 
                        VIDEO(V_TITLE, V_UPLOADER_ID, V_DURATION, V_DESCRIPTION, V_VISIBILITY, V_FILENAME, V_THUMBNAIL)
                        VALUES(:v_title, :uploader_id, :duration, :description, :visibility, :filename, :thumbnail)";
        $stid = oci_parse($db->getConn(), $queryStr);

        $v_title        = $entity->getVTitle();
        $v_uploader_id  = $entity->getVUploaderId();
        $v_duration     = $entity->getVDuration();
        $v_description  = $entity->getVDescription();
        $v_visibility   = $entity->getVVisibility();
        $v_filename     = $entity->getVFilename();
        $v_thumbnail    = $entity->getVThumbnail();

        oci_bind_by_name($stid, ":v_title", $v_title);
        oci_bind_by_name($stid, ":uploader_id", $v_uploader_id);
        oci_bind_by_name($stid, ":duration", $v_duration);
        oci_bind_by_name($stid, ":description", $v_description);
        oci_bind_by_name($stid, ":visibility", $v_visibility);
        oci_bind_by_name($stid, ":filename", $v_filename);
        oci_bind_by_name($stid, ":thumbnail", $v_thumbnail);

        return oci_execute($stid);
    }

    public function update() {
        $db = new DBConnector();

        $queryStr = "UPDATE VIDEO SET " .
            "V_TITLE = :v_title, V_UPLOADER_ID = :v_uploader_id, V_UPLOAD_DATE = :v_upload_date, " .
            "V_DURATION = :v_duration, V_VISIBILITY = :v_visibility, V_FILENAME = :v_filename, " .
            "V_DESCRIPTION = :v_description, V_THUMBNAIL = :v_thumbnail " .
            "WHERE V_ID = :v_id";
        $stid = oci_parse($db->getConn(), $queryStr);

        oci_bind_by_name($stid, ":v_title", $this->v_title);
        oci_bind_by_name($stid, ":v_uploader_id", $this->v_uploader_id);
        oci_bind_by_name($stid, ":v_upload_date", $this->v_upload_date);
        oci_bind_by_name($stid, ":v_duration", $this->v_duration);
        oci_bind_by_name($stid, ":v_visibility", $this->v_visibility);
        oci_bind_by_name($stid, ":v_filename", $this->v_filename);
        oci_bind_by_name($stid, ":v_description", $this->v_description);
        oci_bind_by_name($stid, ":v_thumbnail", $this->v_thumbnail);
        oci_bind_by_name($stid, ":v_id", $this->v_id);

        return oci_execute($stid);
    }

    /**
     * Generates a unique identification string for a video
     * The method checks if the generated string is already in the database
     * @return string Unique identification string
     */
    public static function generateUniqueId(): string {
        static $MAX_SIZE = 16;
        static $abc = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        while(true) {
            $newId = substr(str_shuffle($abc), 0, $MAX_SIZE);

            if(!self::existsWith(array( "v_filename" => array( $newId, self::REL_EQUALS ) )))
                return $newId;
        }
    }
}