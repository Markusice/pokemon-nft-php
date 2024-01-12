<?php

require_once 'storage/Storage.php';

class UserStorage extends Storage
{
    public function __construct()
    {
        parent::__construct(new JsonIO('storage/users.json'));
    }
}
