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
namespace Pop\Project;

use Pop\File\File,
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
class Project
{

    /**
     * CLI error codes & messages
     * @var array
     */
    protected static $_cliErrorCodes = array(
                                           0 => 'Unknown error.',
                                           1 => 'You must pass a source folder and a output file to generate a class map file.',
                                           2 => 'The source folder passed does not exist.',
                                           3 => 'The output file passed must be a PHP file.',
                                           4 => 'You must pass a name for the project.',
                                           5 => 'Unknown option: ',
                                           6 => 'You must pass at least one argument.',
                                           7 => 'That folder does not exist.',
                                           8 => 'The folder argument is not a folder.'
                                       );

    /**
     * Build the project based on the available config files
     *
     * @param string $name
     * @return void
     */
    public static function build($name)
    {
        $dbTables = array();
        self::instructions();

        $input = self::cliInput();
        if ($input == 'n') {
            echo Locale::factory()->__('Aborted.') . PHP_EOL . PHP_EOL;
            exit(0);
        }

        // Check for the project build file.
        if (!file_exists(__DIR__ . '/../../../../../config/project.build.php')) {
            echo Locale::factory()->__('The project build file, \'config/project.build.php\', was not found.') . PHP_EOL;
            echo Locale::factory()->__('Aborted.') . PHP_EOL . PHP_EOL;
            exit(0);
        }

        $build = include __DIR__ . '/../../../../../config/project.build.php';

        // Check if a project folder already exists.
        if (file_exists(__DIR__ . '/../../../../../module/' . $name)) {
            echo Locale::factory()->__('This may overwrite any project files you may already have.') . PHP_EOL;
            echo 'under the \'module/' . $name . '/src\' folder.' . PHP_EOL;
            $input = self::cliInput();
        } else {
            $input = 'y';
        }

        // If 'No', abort
        if ($input == 'n') {
            echo Locale::factory()->__('Aborted.') . PHP_EOL . PHP_EOL;
            exit(0);
        // Else, continue
        } else {
            $db = false;

            // Test for a database creds and schema, and ask to install the database.
            if (isset($build['databases']) && (count($build['databases']) > 0)) {
                $keys = array_keys($build['databases']);
                if (isset($keys[0]) && (file_exists(__DIR__ . '/../../../../../config/' . $keys[0]))) {
                    echo Locale::factory()->__('Database credentials and schema detected.') . PHP_EOL;
                    $input = self::cliInput(Locale::factory()->__('Test and install the database(s)?') . ' (Y/N) ');
                    $db = ($input == 'n') ? false : true;
                }
            }

            // Handle any databases
            if ($db) {
                // Get current error reporting setting and set
                // error reporting to E_ERROR to suppress warnings
                $oldError = ini_get('error_reporting');
                error_reporting(E_ERROR);

                // Test the databases
                echo Locale::factory()->__('Testing the database(s)...') . PHP_EOL;

                foreach ($build['databases'] as $dbname => $db) {
                    echo Locale::factory()->__('Testing') . ' \'' . $dbname . '\'...' . PHP_EOL;
                    if (!isset($db['type']) || !isset($db['database'])) {
                        echo Locale::factory()->__('The database type and database name must be set for the database ') . '\'' . $dbname . '\'.' . PHP_EOL . PHP_EOL;
                        exit(0);
                    }
                    $check = Db::check($db);
                    if (null !== $check) {
                        echo $check . PHP_EOL . PHP_EOL;
                        exit(0);
                    } else {
                        echo Locale::factory()->__('Database') . ' \'' . $dbname . '\' passed.' . PHP_EOL;
                        echo Locale::factory()->__('Installing database') .' \'' . $dbname . '\'...' . PHP_EOL;
                        $tables = Db::install($db);
                        if (null !== $tables) {
                            $dbTables = array_merge($dbTables, $tables);
                        }
                    }
                }
                // Return error reporting to its original state
                error_reporting($oldError);
            }

            // Create base folder and file structure
            self::_create($name, $build);

            // Create tables
            if (count($dbTables) > 0) {
                self::_createTables($name, $dbTables);
            }

            // Add project to the bootstrap file
            $input = self::cliInput(Locale::factory()->__('Add project to the bootstrap file?') . ' (Y/N) ');
            if ($input == 'y') {
                $location = self::getBootstrap();
                $bootstrap = new File($location . '/bootstrap.php');
                $bootstrap->write("require_once '" . realpath(__DIR__ . '/../../../../../config/project.config.php') . "';" . PHP_EOL, true)
                          ->write("require_once \$autoloader->loadModule('{$name}');" . PHP_EOL . PHP_EOL, true)
                          ->save();
            }

            echo Locale::factory()->__('Project build complete.') . PHP_EOL . PHP_EOL;
        }
    }

    /**
     * Display CLI instructions
     *
     * @return string
     */
    public static function instructions()
    {
        $msg = "This process will build a lightweight framework for your project under the 'module/ProjectName' folder. Minimally, you should have the 'config/project.build.php' file present, which should return an array containing your project build settings, such as your database information and credentials. Besides creating the folders and files for you, one of the main benefits is ability to test and install the database and the corresponding configuration and class files. You can do this by having the SQL files in the 'config/dbname' folder. The following folder structure is required for the database installation to work properly:";
        echo wordwrap(Locale::factory()->__($msg), 70, PHP_EOL) . PHP_EOL . PHP_EOL;
        echo '\'config/dbname/create/*.sql\'' . PHP_EOL;
        echo '\'config/dbname/insert/*.sql\'' . PHP_EOL;
        echo '\'config/dbname/*.sql\'' . PHP_EOL . PHP_EOL;
    }

    /**
     * Print the CLI help message
     *
     * @return void
     */
    public static function cliHelp()
    {
        echo ' -b --build ProjectName    ' . Locale::factory()->__('Build a project based on the files in the \'config\' folder') . PHP_EOL;
        echo ' -c --check                ' . Locale::factory()->__('Check the current configuration for required dependencies') . PHP_EOL;
        echo ' -h --help                 ' . Locale::factory()->__('Display this help') . PHP_EOL;
        echo ' -i --instructions         ' . Locale::factory()->__('Display build project instructions') . PHP_EOL;
        echo ' -l --lang fr              ' . Locale::factory()->__('Set the default language for the project') . PHP_EOL;
        echo ' -m --map folder file.php  ' . Locale::factory()->__('Create a class map file from the source folder and save to the output file') . PHP_EOL;
        echo ' -t --test folder          ' . Locale::factory()->__('Run the unit tests from a folder') . PHP_EOL;
        echo ' -v --version              ' . Locale::factory()->__('Display version of Pop PHP Framework and latest available') . PHP_EOL . PHP_EOL;
    }

    /**
     * Return a CLI error message based on the code
     *
     * @param int    $num
     * @param string $arg
     * @return string
     */
    public static function cliError($num = 0, $arg = null)
    {
        $i = (int)$num;
        if (!array_key_exists($i, self::$_cliErrorCodes)) {
            $i = 0;
        }
        $msg = Locale::factory()->__(self::$_cliErrorCodes[$i]) . $arg . PHP_EOL .
               Locale::factory()->__('Run \'.' . DIRECTORY_SEPARATOR . 'pop -h\' for help.') . PHP_EOL . PHP_EOL;
        return $msg;
    }

    /**
     * Return the (Y/N) input from STDIN
     *
     * @return string
     */
    public static function cliInput($msg = null)
    {
        echo ((null === $msg) ? Locale::factory()->__('Continue?') : $msg) . ' (Y/N) ';
        $input = null;

        while (($input != 'y') && ($input != 'n')) {
            if (null !== $input) {
                echo $msg;
            }
            $prompt = fopen("php://stdin", "r");
            $input = fgets($prompt, 5);
            $input = substr(strtolower(rtrim($input)), 0, 1);
            fclose ($prompt);
        }

        return $input;
    }

    /**
     * Return the location of the bootstrap file from STDIN
     *
     * @return string
     */
    public static function getBootstrap()
    {
        $msg = Locale::factory()->__('Enter the folder where the \'bootstrap.php\' is located in relation to the current folder: ');
        echo $msg;
        $input = null;

        while (!file_exists($input . '/bootstrap.php')) {
            if (null !== $input) {
                echo Locale::factory()->__('Bootstrap file not found. Try again.') . PHP_EOL . $msg;
            }
            $prompt = fopen("php://stdin", "r");
            $input = fgets($prompt, 255);
            $input = rtrim($input);
            fclose ($prompt);
        }

        return $input;
    }

    /**
     * Return the two-letter language code from STDIN
     *
     * @param array $langs
     * @return string
     */
    public static function getLanguage($langs)
    {
        $msg = Locale::factory()->__('Enter the two-letter code for the default language: ');
        echo $msg;
        $lang = null;

        while (!array_key_exists($lang, $langs)) {
            if (null !== $lang) {
                echo $msg;
            }
            $prompt = fopen("php://stdin", "r");
            $lang = fgets($prompt, 5);
            $lang = rtrim($lang);
            fclose ($prompt);
        }

        return $lang;
    }

    /**
     * Create the base folder and file structure
     *
     * @param string $name
     * @param array  $build
     * @return void
     */
    protected static function _create($name, $build)
    {
        echo Locale::factory()->__('Creating base folder and file structure...') . PHP_EOL;

        $projectCfg = new File(__DIR__ . '/../../../../../config/project.config.php');
        $projectCfg->write('<?php' . PHP_EOL . PHP_EOL);
        if (isset($build['databases'])) {
            $projectCfg->write('$db = array(' . PHP_EOL, true);
            $i = 0;
            foreach ($build['databases'] as $dbname => $db) {
                $projectCfg->write("    'poptest' => Pop\\Db\\Db::factory('" . $db['type'] . "', array (" . PHP_EOL, true);
                $j = 0;
                foreach ($db as $key => $value) {
                    if ($key != 'type') {
                        $ary = "        '{$key}' => '{$value}'";
                        $j++;
                        if ($j < count($db)) {
                           $ary .= ',';
                        }
                        $projectCfg->write($ary . PHP_EOL, true);
                    }
                }
                $i++;
                $end = ($i < count($build['databases'])) ? '    )),' : '    ))';
                $projectCfg->write($end . PHP_EOL, true);
            }
            $projectCfg->write(');' . PHP_EOL, true);
        }
        $projectCfg->save();

        $folders = array(
            'project' => __DIR__ . '/../../../../../module/' . $name,
            'config'  => __DIR__ . '/../../../../../module/' . $name . '/config',
            'src'     => __DIR__ . '/../../../../../module/' . $name . '/src',
            'library' => __DIR__ . '/../../../../../module/' . $name . '/src/' . $name,
            'view'    => __DIR__ . '/../../../../../module/' . $name . '/view'
        );
        foreach ($folders as $folder) {
            if (!file_exists($folder)) {
                mkdir($folder);
            }
        }

        // Create the module config file
        $moduleCfg = new File($folders['config'] . '/module.config.php');
        $moduleCfg->write('<?php' . PHP_EOL . PHP_EOL)
                  ->save();
    }

    /**
     * Create the table class files
     *
     * @param string $name
     * @param array  $dbTables
     * @return void
     */
    protected static function _createTables($name, $dbTables)
    {
        echo Locale::factory()->__('Creating database table class files...') . PHP_EOL;

        $tableDir = __DIR__ . '/../../../../../module/' . $name . '/src/' . $name . '/Table';
        if (!file_exists($tableDir)) {
            mkdir($tableDir);
        }

        foreach ($dbTables as $table) {
            if (null !== $table['tableName']) {
                $tableName = String::factory($table['tableName'])->underscoreToCamelcase()->upperFirst();
                $tableCls = new File($tableDir . '/' . $tableName . '.php');
                $tableCls->write('<?php' . PHP_EOL . PHP_EOL)
                         ->write('namespace ' . $name . '\\Table;' . PHP_EOL . PHP_EOL, true)
                         ->write('use Pop\\Record\\Record;' . PHP_EOL . PHP_EOL, true);

                $auto = ($table['auto']) ? 'true' : 'false';
                $primaryId = (null !== $table['primaryId']) ? "'" . $table['primaryId'] . "'" : 'null';

                $tableCls->write('class ' . $tableName . ' extends Record' . PHP_EOL, true)
                         ->write('{' . PHP_EOL . PHP_EOL, true)
                         ->write('    protected $_primaryId = ' . $primaryId . ';' . PHP_EOL . PHP_EOL, true)
                         ->write('    protected $_auto =  ' . $auto . ';' . PHP_EOL . PHP_EOL, true)
                         ->write('}' . PHP_EOL, true)
                         ->save();
            }
        }
    }

}
