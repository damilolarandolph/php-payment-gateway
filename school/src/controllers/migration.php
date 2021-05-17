<?php

class MigrationController
{
    public function migrate()
    {
        require_once __DIR__ . "/../data/config/migrate.php";
    }
}
