<?php
require __DIR__ . "app/controllers/enseignant.php";
class EnseignantTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $enseignant = new app\controllers\Enseignant;
        $result = $enseignant->create();

    }
}
