<?php


namespace App\EntityListener;


use DateTime;

class CreatedAtListener
{
    public function prePersist($e)
    {
        $e->setUpdatedAt(new DateTime("NOW"));
        $e->setCreatedAt(new DateTime("NOW"));
    }

    public function preUpdate($e)
    {
        $e->setUpdatedAt(new DateTime("NOW"));
    }
}
