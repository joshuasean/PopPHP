<?php
/**
 * Pop PHP Framework Unit Tests (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Test
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace PopTest\I18n;

use Pop\Loader\Autoloader,
    Pop\I18n\I18n;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

class I18nTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        define('POP_DEFAULT_LANG', 'fr');
        $l = new I18n();
        $this->assertEquals('Ce champ est obligatoire.', $l->__('This field is required.'));
        $this->assertEquals('La valeur ne doit pas faire partie du 127.0.0 de sous-réseau.', $l->__('The value must not be part of the subnet %1.', '127.0.0'));
        $this->assertEquals('La valeur ne doit pas faire partie du 127.0.0 de sous-réseau.', $l->__('The value must not be part of the subnet %1.', array('127.0.0')));
        $this->assertEquals('fr', $l->getLanguage());
    }

    public function testFactory()
    {
        $l = I18n::factory('fr');
        $class = 'Pop\I18n\I18n';
        $this->assertInstanceOf('Pop\I18n\I18n', $l);
        $this->assertEquals('Ce champ est obligatoire.', $l->__('This field is required.'));
    }

    public function testLoadFileException()
    {
        $this->setExpectedException('Pop\I18n\Exception');
        $l = I18n::factory();
        $l->loadFile('bad.xml');
    }

    public function testE()
    {
        $l = new I18n();
        ob_start();
        $l->_e('This field is required.');
        $output = ob_get_clean();
        $this->assertEquals('Ce champ est obligatoire.', $output);
    }

    public function testGetLanguages()
    {
        $l = new I18n();
        $this->assertEquals(12, count($l->getLanguages()));
    }

}

