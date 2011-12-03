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
 * @package    Pop_Filter
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Filter;

/**
 * @category   Pop
 * @package    Pop_Filter
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class Percentage
{

    /**
     * Static method to calculate the percent values of an array of numbers
     *
     * @param  array $arr
     * @param  mixed $total
     * @param  int   $prec
     * @param  int   $mode
     * @return array
     */
    public static function calculate($arr, $total = null, $prec = 2, $mode = PHP_ROUND_HALF_UP)
    {
        $percentages = array();
        $total = (null === $total) ? array_sum($arr) : $total;

        foreach ($arr as $key => $value) {
            $percentages[$value] = round((($value / $total) * 100), $prec, $mode);
        }

        $percentages['total'] = $total;

        return $percentages;
    }

}