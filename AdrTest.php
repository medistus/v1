<?php

namespace common\tests;

use common\models\Adr;

class AdrTest extends \Codeception\Test\Unit {

    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    protected function _before() {
        
    }

    protected function _after() {
        
    }

    // tests
    public function testSaveadrifnotexistNonExistingAdr() {
        $desc = 'teszt';
        $returnValue = Adr::saveAdrIfNotExist($desc, 1);
        expect("beszúr egy Adr-t");

        $this->assertNotNull(Adr::getAdrFromDescription($desc));
    }

    public function testSaveadrifnotexistExistingAdr() {
        $desc = "1000";
        $id1 = Adr::saveAdrIfNotExist($desc, 1);
        $id2 = Adr::saveAdrIfNotExist($desc, 1);
        expect("Visszatér ugyanazzal az id-val");

        $this->assertTrue($id1 == $id2 && Adr::getAdrFromDescription($desc) != null);
    }

    public function testSaveadrifnotexistWrongAdrtype() {
        $desc = "60";
        $returnValue = Adr::saveAdrIfNotExist($desc, 0);
        expect("Null-al tér vissza");

        $this->assertNull($returnValue);
    }

    public function testSaveadrifnotexistNullAdrtype() {
        $desc = "60";
        $returnValue = Adr::saveAdrIfNotExist($desc, null);
        expect("Null-al tér vissza");

        $this->assertNull($returnValue);
    }

    public function testSaveadrifnotexistEmptyAdrName() {
        $returnValue = Adr::saveAdrIfNotExist('', 1);
        expect("Null-al tér vissza");

        $this->assertNull($returnValue);
    }

    public function testSaveadrifnotexistNullAdrName() {
        $returnValue = Adr::saveAdrIfNotExist(null, 1);
        expect("Null-al tér vissza");

        $this->assertNull($returnValue);
    }

    public function testSaveadrifnotexistBothParametersNull() {
        $returnValue = Adr::saveAdrIfNotExist(null, null);
        expect("Null-al tér vissza");

        $this->assertNull($returnValue);
    }

}
