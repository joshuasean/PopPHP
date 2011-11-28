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
 * @package    Pop_Form
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Form\Element;

use Pop\Form\Element,
    Pop\Locale\Locale;

/**
 * @category   Pop
 * @package    Pop_Form
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class Select extends Element
{

    /**
     * Current values
     * @var array
     */
    public $values = array();

    /**
     * Constructor
     *
     * Instantiate the select form element object.
     *
     * @param  string $name
     * @param  string|array $value
     * @param  string|array $marked
     * @param  string $indent
     * @return void
     */
    public function __construct($name, $value = null, $marked = null, $indent = null)
    {
        $val = null;
        $lang = new Locale();

        // If the value flag is YEAR-based, calculate the year range for the select drop-down menu.
        if (is_string($value) && (strpos($value, 'YEAR') !== false)) {
            $years = array('----' => '----');
            $yearAry = array();
            $yearAry = explode('_', $value);
            if (isset($yearAry[1]) && isset($yearAry[2])) {
                if ($yearAry[1] < $yearAry[2]) {
                    for ($i = $yearAry[1]; $i <= $yearAry[2]; $i++) {
                        $years[$i] = $i;
                    }
                } else {
                    for ($i = $yearAry[1]; $i >= $yearAry[2]; $i--) {
                        $years[$i] = $i;
                    }
                }
            } else if (isset($yearAry[1])) {
                $year = date('Y');
                if ($year < $yearAry[1]) {
                    for ($i = $year; $i <= $yearAry[1]; $i++) {
                        $years[$i] = $i;
                    }
                } else {
                    for ($i = $year; $i >= $yearAry[1]; $i--) {
                        $years[$i] = $i;
                    }
                }
            } else {
                $year = date('Y');
                for ($i = $year; $i <= ($year + 10); $i++) {
                    $years[$i] = $i;
                }
            }
            $val = $years;
        // Else, if the value flag is one of the pre-defined array values, set the value of the select drop-down menu to it.
        } else {
            switch ($value) {
                // Months, numeric short values.
                case 'MONTHS_SHORT':
                    $val = array('--' => '--', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12');
                    break;
                // Months, long name values.
                case 'MONTHS_LONG':
                    $val = array('--' => '------', '01' => $lang->__('January'), '02' => $lang->__('February'), '03' => $lang->__('March'), '04' => $lang->__('April'), '05' => $lang->__('May'), '06' => $lang->__('June'), '07' => $lang->__('July'), '08' => $lang->__('August'), '09' => $lang->__('September'), '10' => $lang->__('October'), '11' => $lang->__('November'), '12' => $lang->__('December'));
                    break;
                // Days of Month, numeric short values.
                case 'DAYS_OF_MONTH':
                    $val = array('--' => '--', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31');
                    break;
                // Days of Week, long name values.
                case 'DAYS_OF_WEEK':
                    $sun = $lang->__('Sunday');
                    $mon = $lang->__('Monday');
                    $tue = $lang->__('Tuesday');
                    $wed = $lang->__('Wednesday');
                    $thu = $lang->__('Thursday');
                    $fri = $lang->__('Friday');
                    $sat = $lang->__('Saturday');
                    $val = array('--' => '------', $sun => $sun, $mon => $mon, $tue => $tue, $wed => $wed, $thu => $thu, $fri => $fri, $sat => $sat);
                    break;
                // Hours, 12-hour values.
                case '12_HOURS':
                    $val = array('--' => '--', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12');
                    break;
                // Military Hours, 24-hour values.
                case '24_HOURS':
                    $val = array('--' => '--', '00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23');
                    break;
                // Minutes, incremental by 1 minute.
                case 'MINUTES':
                    $val = array('--' => '--', '00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35', '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40', '41' => '41', '42' => '42', '43' => '43', '44' => '44', '45' => '45', '46' => '46', '47' => '47', '48' => '48', '49' => '49', '50' => '50', '51' => '51', '52' => '52', '53' => '53', '54' => '54', '55' => '55', '56' => '56', '57' => '57', '58' => '58', '59' => '59');
                    break;
                // Minutes, incremental by 5 minutes.
                case '5_MINUTES':
                    $val = array('--' => '--', '00' => '00', '05' => '05', '10' => '10', '15' => '15', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '40' => '40', '45' => '45', '50' => '50', '55' => '55');
                    break;
                // Minutes, incremental by 10 minutes.
                case '10_MINUTES':
                    $val = array('--' => '--', '00' => '00', '10' => '10', '20' => '20', '30' => '30', '40' => '40', '50' => '50');
                    break;
                // Minutes, incremental by 15 minutes.
                case '15_MINUTES':
                    $val = array('--' => '--', '00' => '00', '15' => '15', '30' => '30', '45' => '45');
                    break;
                // US States, short name values.
                case 'US_STATES_SHORT':
                    $val = array('--' => '--', 'AK' => 'AK', 'AL' => 'AL', 'AR' => 'AR', 'AZ' => 'AZ', 'CA' => 'CA', 'CO' => 'CO', 'CT' => 'CT', 'DC' => 'DC', 'DE' => 'DE', 'FL' => 'FL', 'GA' => 'GA', 'HI' => 'HI', 'IA' => 'IA', 'ID' => 'ID', 'IL' => 'IL', 'IN' => 'IN', 'KS' => 'KS', 'KY' => 'KY', 'LA' => 'LA', 'MA' => 'MA', 'MD' => 'MD', 'ME' => 'ME', 'MI' => 'MI', 'MN' => 'MN', 'MO' => 'MO', 'MS' => 'MS', 'MT' => 'MT', 'NC' => 'NC', 'ND' => 'ND', 'NE' => 'NE', 'NH' => 'NH', 'NJ' => 'NJ', 'NM' => 'NM', 'NV' => 'NV', 'NY' => 'NY', 'OH' => 'OH', 'OK' => 'OK', 'OR' => 'OR', 'PA' => 'PA', 'RI' => 'RI', 'SC' => 'SC', 'SD' => 'SD', 'TN' => 'TN', 'TX' => 'TX', 'UT' => 'UT', 'VA' => 'VA', 'VT' => 'VT', 'WA' => 'WA', 'WI' => 'WI', 'WV' => 'WV', 'WY' => 'WY');
                    break;
                // US States, long name values.
                case 'US_STATES_LONG':
                    $val = array('--' => '------', 'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DC' => 'District of Columbia', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming');
                    break;
                // Else, set the custom array of values passed.
                default:
                    // If it's an array, just set it.
                    if (is_array($value)) {
                        $val = $value;
                    // Else, check for the values in the XML options file.
                    } else {
                        $xmlFile = __DIR__ . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'options.xml';
                        if (file_exists($xmlFile)) {
                            $xml = new \SimpleXMLElement($xmlFile, null, true);
                            $xmlValues = array();
                            foreach ($xml->set as $node) {
                                $xmlValues[(string)$node->attributes()->name] = array();
                                foreach ($node->opt as $opt) {
                                    $xmlValues[(string)$node->attributes()->name][(string)$opt->attributes()->value] = (string)$opt;
                                }
                            }
                            $val = (array_key_exists($value, $xmlValues)) ? $xmlValues[$value] : $value;
                        } else {
                            $val = $value;
                        }
                    }
            }
        }

        $this->values = $val;
        $this->setMarked($marked);

        parent::__construct('select', $name, $val, $marked, $indent);
    }

    /**
     * Set the current marked value. The marked value is based on the key of the associative array (not the value.)
     *
     * @param  string $val
     * @return void
     */
    public function setMarked($val)
    {
        $this->marked = null;

        if (array_key_exists($val, $this->values) !==  false) {
            $this->marked = $this->values[$val];
        }
    }

}
