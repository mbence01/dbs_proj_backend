<?php

class Mention
{
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


}