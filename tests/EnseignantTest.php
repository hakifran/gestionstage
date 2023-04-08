<?php
class EnseignantTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        include_once dirname(__DIR__) . "/app/controllers/enseignant.php";
        $result = $enseignant->create();

    }
}
