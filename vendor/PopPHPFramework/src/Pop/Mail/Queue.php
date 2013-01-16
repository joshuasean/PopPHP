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
 * @package    Pop_Mail
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Mail;

/**
 * This is the Queue class for the Mail component.
 *
 * @category   Pop
 * @package    Pop_Mail
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    1.2.0
 */
class Queue extends \SplQueue
{

    /**
     * Constructor
     *
     * Instantiate the mail queue object.
     *
     * @param  mixed  $email
     * @param  string $name
     * @return \Pop\Mail\Queue
     */
    public function __construct($email = null, $name = null)
    {
        if (null !== $email) {
            if (is_array($email)) {
                $this->addRecipients($email);
            } else {
                $this->add($email, $name);
            }
        }
    }

    /**
     * Add a recipient
     *
     * @param  string $email
     * @param  string $name
     * @return \Pop\Mail\Queue
     */
    public function add($email, $name = null)
    {
        $rcpt = array();
        if (null !== $name) {
            $rcpt['name'] = $name;
        }
        $rcpt['email'] = $email;

        return $this->addRecipients(array($rcpt));
    }

    /**
     * Add recipients
     *
     * @param  mixed $rcpts
     * @throws Exception
     * @return \Pop\Mail\Queue
     */
    public function addRecipients($rcpts)
    {
        $regEx = '/[a-zA-Z0-9\.\-\_+%]+@[a-zA-Z0-9\-\_\.]+\.[a-zA-Z]{2,4}/';

        // If single, but not valid
        if (!is_array($rcpts) && !preg_match($regEx, $rcpts)) {
            throw new Exception("Error: You must pass at least one valid email address.");
        // Else, if single and valid
        } else if (!is_array($rcpts)) {
            $this[] = array('email' => $rcpts);
        // Else if an associative array of scalar values
        } else if (is_array($rcpts) && isset($rcpts['email'])) {
            if (!preg_match($regEx, $rcpts['email'])) {
                throw new Exception("Error: The email address '" . $rcpts['email'] . "' is not valid.");
            }
            $this[] = $rcpts;
        // Else if a numeric array of scalar values
        } else if (is_array($rcpts) && isset($rcpts[0]) && !is_array($rcpts[0])) {
            foreach ($rcpts as $email) {
                if (!preg_match($regEx, $email)) {
                    throw new Exception("Error: The email address '" . $email . "' is not valid.");
                }
                $this[] = array('email' => $email);
            }
        // Else, if an array of arrays
        } else if (is_array($rcpts) && isset($rcpts[0]) && is_array($rcpts[0])) {
            foreach ($rcpts as $rcpt) {
                if (!isset($rcpt['email'])) {
                    throw new Exception("Error: At least one of the array keys must be 'email'.");
                } else if (!preg_match($regEx, $rcpt['email'])) {
                    throw new Exception("Error: The email address '" . $rcpt['email'] . "' is not valid.");
                }
                $this[] = $rcpt;
            }
        } else {
            throw new Exception("Error: The recipients parameter passed was not valid.");
        }

        return $this;
    }

    /**
     * Build the to string
     *
     * @return string
     */
    public function __toString()
    {
        $to = array();
        foreach ($this as $rcpt) {
            $to[] = (isset($rcpt['name'])) ? $rcpt['name'] . " <" . $rcpt['email'] . ">" : $rcpt['email'];
        }

        return implode(', ', $to);
    }

}