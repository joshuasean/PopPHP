<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Validator
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

use Pop\I18n\I18n;

/**
 * Excluded validator class
 *
 * @category   Pop
 * @package    Pop_Validator
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.2.2
 */
class Excluded extends Validator
{

    /**
     * Method to evaluate the validator
     *
     * @param  mixed $input
     * @return boolean
     */
    public function evaluate($input = null)
    {
        // Set the input, if passed
        if (null !== $input) {
            $this->input = $input;
        }

        // Set the default message
        if (null === $this->defaultMessage) {
            if ($this->condition) {
                $this->defaultMessage = I18n::factory()->__('The value must be excluded.');
            } else {
                $this->defaultMessage = I18n::factory()->__('The value must not be excluded.');
            }
        }

        // If input check is an array
        if (is_array($this->input)) {
            if (!is_array($this->value)) {
                $this->value = array($this->value);
            }
            $this->result = true;
            foreach ($this->value as $value) {
                if ((!in_array($value, $this->input)) != $this->condition) {
                    $this->result = false;
                }
            }
        // Else, if input check is a string
        } else {
            if (is_array($this->value)) {
                $this->result = ((!in_array($this->input, $this->value)) != $this->condition) ? false : true;
            } else {
                if ((strpos((string)$this->input, (string)$this->value) === false) == $this->condition) {
                    $this->result = true;
                } else {
                    $this->result = false;
                }
            }
        }

        return $this->result;
    }

}
