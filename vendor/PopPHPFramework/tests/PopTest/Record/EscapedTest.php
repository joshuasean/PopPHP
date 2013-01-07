<?php
/**
 * Pop PHP Framework Unit Tests
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.TXT.
 * It is also available through the world-wide-web at this URL:
 * http://www.popphp.org/LICENSE.TXT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@popphp.org so we can send you a copy immediately.
 *
 */

namespace PopTest\Record;

use Pop\Loader\Autoloader,
    Pop\Db\Db,
    Pop\Record\Record;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

class Users extends Record {
    protected $usePrepared = false;
}

class UserData extends Record {
    protected $prefix = 'pop_';
    protected $tableName = 'user_data';
    protected $primaryId = array('user_id', 'data_id');
    protected $usePrepared = false;
}

class EscapedTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $r = new Record(array('column' => 'value'), Db::factory('Sqlite', array('database' => __DIR__ . '/../tmp/test.sqlite')));
        $this->assertInstanceOf('Pop\Record\Record', $r);
    }

    public function testGetDefaultDb()
    {
        Record::setDb(Db::factory('Sqlite', array('database' => __DIR__ . '/../tmp/test.sqlite')), true);
        $this->assertInstanceOf('Pop\Db\Db', Record::getDb());
    }

    public function testFindById()
    {
        $r = Users::findById(1, 1);
        $this->assertEquals(1, $r->id);
        $this->assertEquals(0, $r->lastId());
    }

    public function testFindByIdException()
    {
        $this->setExpectedException('Pop\Record\Adapter\Exception');
        $r = UserData::findById(array(1));
    }

    public function testFindBy()
    {
        $r = Users::findBy(array('email' => 'test1@test.com'), 1);
        $r->test = $r->escape('test');
        $this->assertEquals('test', $r->test);
        unset($r->test);
        $this->assertNull($r->test);
        $this->assertEquals(1, $r->id);
    }

    public function testFindAll()
    {
        $r = Users::findAll('id RAND()', array('email' => 'test1@test.com'));
        $r = Users::findAll('id, username DESC', null, 4);
        $r = Users::findAll('id ASC');
        $this->assertEquals(8, count($r->rows));
        $this->assertEquals(8, $r->numRows());
        $this->assertEquals(5, $r->numFields());
    }

    public function testExecute()
    {
        $r = Users::execute('SELECT * FROM users');
        $this->assertEquals(8, count($r->rows));
    }

    public function testQuery()
    {
        $r = Users::query('SELECT * FROM users');
        $this->assertEquals(8, count($r->rows));
    }

    public function testGetTableName()
    {
        $r = Users::findById(1);
        $this->assertEquals('users', $r->getTableName());
        $r = UserData::findById(array(1, 1));
        $this->assertEquals('pop_user_data', $r->getFullTablename());
        $this->assertEquals('user_data', $r->getTableName());
    }

    public function testSaveUpdateDelete()
    {
        $r = new Users(array(
            'username' => 'newuser',
            'password' => 'newpassword',
            'email'    => 'new@email.com',
            'access'   => 'reader'
        ));
        $r->save();
        $r = Users::findBy(array('username' => 'newuser'));
        $this->assertEquals('newpassword', $r->password);
        $r->password = 'newpassword1';
        $r->update();
        $r = Users::findBy(array('username' => 'newuser'));
        $this->assertEquals('newpassword1', $r->password);
        $r->delete();
        $this->assertNull($r->username);
        $r = Users::findBy(array('username' => 'newuser'));
        $this->assertNull($r->username);
    }

}

