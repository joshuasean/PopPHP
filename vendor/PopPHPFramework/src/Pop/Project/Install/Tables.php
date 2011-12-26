<?php
/**
 * Pop PHP Framework
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
 * @category   Pop
 * @package    Pop_Project
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Project\Install;

use Pop\Code\Generator,
    Pop\Code\PropertyGenerator,
    Pop\Code\NamespaceGenerator,
    Pop\Filter\String,
    Pop\Locale\Locale;

/**
 * @category   Pop
 * @package    Pop_Project
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class Tables
{

    /**
     * Install the table class files
     *
     * @param Pop\Config $install
     * @param array  $dbTables
     * @return void
     */
    public static function install($install, $dbTables)
    {
        echo Locale::factory()->__('Creating database table class files...') . PHP_EOL;

        // Create table class folder
        $tableDir = $install->project->base . '/module/' . $install->project->name . '/src/' . $install->project->name . '/Table';
        if (!file_exists($tableDir)) {
            mkdir($tableDir);
        }

        // Loop through the tables, creating the classes
        foreach ($dbTables as $table => $value) {
            $tableName = String::factory($table)->underscoreToCamelcase()->upperFirst();

            $ns = new NamespaceGenerator($install->project->name . '\\Table');
            $ns->setUse('Pop\\Record\\Record');

            $prefix = new PropertyGenerator('_prefix', 'string', $value['prefix'], 'protected');
            $propId = new PropertyGenerator('_primaryId', 'string', $value['primaryId'], 'protected');
            $propAuto = new PropertyGenerator('_auto', 'boolean', $value['auto'], 'protected');

            // Create and save table class file
            $tableCls = new Generator($tableDir . '/' . $tableName . '.php', Generator::CREATE_CLASS);
            $tableCls->setNamespace($ns);
            $tableCls->code()->setParent('Record')
                             ->addProperty($prefix)
                             ->addProperty($propId)
                             ->addProperty($propAuto);

            $tableCls->save();
        }
    }

}