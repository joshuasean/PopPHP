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
 * @package    Pop_Code
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Code;

use Pop\Locale\Locale;

/**
 * @category   Pop
 * @package    Pop_Code
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class DocblockGenerator
{

    /**
     * Docblock description
     * @var string
     */
    protected $_desc = null;

    /**
     * Docblock tags
     * @var array
     */
    protected $_tags = array('param' => array());

    /**
     * Docblock indent
     * @var string
     */
    protected $_indent = null;

    /**
     * Docblock output
     * @var string
     */
    protected $_output = null;

    /**
     * Constructor
     *
     * Instantiate the docblock generator object
     *
     * @param  string $desc
     * @param  string $indent
     * @return void
     */
    public function __construct($desc = null, $indent = null)
    {
        $this->_desc = $desc;
        $this->_indent = $indent;
    }

    /**
     * Static method to instantiate the docblock object and return itself
     * to facilitate chaining methods together.
     *
     * @param  string $desc
     * @param  string $indent
     * @return Pop\Code\DocblockGenerator
     */
    public static function factory($desc = null, $indent = null)
    {
        return new self($desc, $indent);
    }

    /**
     * Static method to parse a docblock string and return a new
     * docblock generator object.
     *
     * @param  string $docblock
     * @param  string $forceIndent
     * @throws Exception
     * @return Pop\Code\DocblockGenerator
     */
    public static function parse($docblock, $forceIndent = null)
    {
        if ((strpos($docblock, '/*') === false) || (strpos($docblock, '*/') === false)) {
            throw new Exception(Locale::factory()->__('The docblock is not in the correct format.'));
        }

        $desc = null;
        $formattedDesc = null;
        $indent = null;
        $tags = null;

        // Parse the description, if any
        if (strpos($docblock, '@') !== false) {
            $desc = substr($docblock, 0, strpos($docblock, '@'));
            $desc = str_replace('/*', '', $desc);
            $desc = str_replace('*/', '', $desc);
            $desc = str_replace(PHP_EOL . ' * ', ' ', $desc);
            $desc = trim(str_replace('*', '', $desc));
            $descAry = explode(PHP_EOL, $desc);
            $formattedDesc = null;
            foreach ($descAry as $line) {
                $formattedDesc .= ' ' . trim($line);
            }
            $formattedDesc = trim($formattedDesc);
        }

        // Get the indentation, if any, and create docblock object
        $indent = (null === $forceIndent) ? substr($docblock, 0, strpos($docblock, '/')) : $forceIndent;
        $newDocblock = new self($formattedDesc, $indent);

        // Get the tags, if any
        if (strpos($docblock, '@') !== false) {
            $tags = substr($docblock, strpos($docblock, '@'));
            $tags = substr($tags, 0, strpos($tags, '*/'));
            $tags = str_replace('*', '', $tags);
            $tagsAry = explode(PHP_EOL, $tags);

            foreach ($tagsAry as $key => $value) {
                $value = trim(str_replace('@', '', $value));
                // Param tags
                if (stripos($value, 'param') !== false) {
                    $paramtag = trim(str_replace('param', '', $value));
                    $paramtype = trim(substr($paramtag, 0, strpos($paramtag, ' ')));
                    $varname = null;
                    $paramdesc = null;
                    if (strpos($paramtag, ' ') !== false) {
                        $varname = trim(substr($paramtag, strpos($paramtag, ' ')));
                        if (strpos($varname, ' ') !== false) {
                            $paramdesc = trim(substr($varname, strpos($varname, ' ')));
                        }
                    } else {
                        $paramtype = $paramtag;
                    }
                    $newDocblock->setParam($paramtype, $varname, $paramdesc);
                // Else, return tags
                } else if (stripos($value, 'return') !== false) {
                    $returntag = trim(str_replace('return', '', $value));
                    if (strpos($returntag, ' ') !== false) {
                        $returntype = substr($returntag, 0, strpos($returntag, ' '));
                        $returndesc = trim(str_replace($returntype, '', $returntag));
                    } else {
                        $returntype = $returntag;
                        $returndesc = null;
                    }
                    $newDocblock->setReturn($returntype, $returndesc);
                // Else, all other tags
                } else {
                    $tagname = trim(substr($value, 0, strpos($value, ' ')));
                    $tagdesc = trim(str_replace($tagname, '', $value));
                    if (!empty($tagname) && !empty($tagdesc)) {
                        $newDocblock->setTag($tagname, $tagdesc);
                    } else {
                        unset($tagsAry[$key]);
                    }
                }
            }
        }

        return $newDocblock;
    }

    /**
     * Set the docblock description
     *
     * @param  string $desc
     * @return Pop\Code\DocblockGenerator
     */
    public function setDesc($desc = null)
    {
        $this->_desc = $desc;
        return $this;
    }

    /**
     * Get the docblock description
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * Set the docblock indent
     *
     * @param  string $indent
     * @return Pop\Code\DocblockGenerator
     */
    public function setIndent($indent = null)
    {
        $this->_indent = $indent;
        return $this;
    }

    /**
     * Get the docblock indent
     *
     * @return string
     */
    public function getIndent()
    {
        return $this->_indent;
    }

    /**
     * Add a basic tag
     *
     * @param  string $name
     * @param  string $desc
     * @return Pop\Code\DocblockGenerator
     */
    public function setTag($name, $desc = null)
    {
        $this->_tags[$name] = $desc;
        return $this;
    }

    /**
     * Add basic tags
     *
     * @param  array $tags
     * @return Pop\Code\DocblockGenerator
     */
    public function setTags(array $tags)
    {
        foreach ($tags as $name => $desc) {
            $this->_tags[$name] = $desc;
        }
        return $this;
    }

    /**
     * Get a tag
     *
     * @param  string $name
     * @return string
     */
    public function getTag($name)
    {
        return (isset($this->_tags[$name])) ? $this->_tags[$name] : null;
    }

    /**
     * Add a param tag
     *
     * @param  string $name
     * @param  string $desc
     * @return Pop\Code\DocblockGenerator
     */
    public function setParam($type, $var = null, $desc = null)
    {
        $this->_tags['param'][] = array('type' => $type, 'var' => $var, 'desc' => $desc);
        return $this;
    }

    /**
     * Add a param tag
     *
     * @param  array $params
     * @return Pop\Code\DocblockGenerator
     */
    public function setParams(array $params)
    {
        foreach ($params as $param) {
            $this->_tags['param'][] = $param;
        }
        return $this;
    }

    /**
     * Get a param
     *
     * @param  string $name
     * @return string
     */
    public function getParam($name)
    {
        return (isset($this->_tags['param'][$name])) ? $this->_tags['param'][$name] : null;
    }

    /**
     * Add a return tag
     *
     * @param  string $type
     * @param  string $desc
     * @return Pop\Code\DocblockGenerator
     */
    public function setReturn($type, $desc = null)
    {
        $this->_tags['return'] = array('type' => $type, 'desc' => $desc);
        return $this;
    }

    /**
     * Get the return
     *
     * @param  string $name
     * @return string
     */
    public function getReturn()
    {
        return (isset($this->_tags['return'])) ? $this->_tags['return'] : null;
    }

    /**
     * Render docblock
     *
     * @param  boolean $ret
     * @return mixed
     */
    public function render($ret = false)
    {
        $this->_output = $this->_indent . '/**' . PHP_EOL;

        if (!empty($this->_desc)) {
            $desc = trim($this->_desc);
            $descAry = explode(PHP_EOL, $desc);
            $i = 0;
            foreach ($descAry as $d) {
                $i++;
                $this->_output .= $this->_indent . ' * ' . wordwrap($d, 70, PHP_EOL . $this->_indent . " * ") . PHP_EOL;
                if ($i < count($descAry)) {
                     $this->_output .= $this->_indent . ' * ' . PHP_EOL;
                }
            }
        }

        $this->_output .= $this->_formatTags();
        $this->_output .= $this->_indent . ' */' . PHP_EOL;

        if ($ret) {
            return $this->_output;
        } else {
            echo $this->_output;
        }
    }

    /**
     * Format the docblock tags
     *
     * @return string
     */
    protected function _formatTags()
    {
        $tags = null;
        $tagLength = $this->_getTagLength();

        // Format basic tags
        foreach ($this->_tags as $tag => $desc) {
            if (($tag != 'param') && ($tag != 'return') && ($tag != 'throws')) {
                $tags .= $this->_indent . ' * @' . $tag .
                    str_repeat(' ', $tagLength - strlen($tag) + 1) .
                    $desc . PHP_EOL;
            }
        }

        // Format param tags
        foreach ($this->_tags['param'] as $param) {
            $paramLength = $this->_getParamLength();
            $tags .= $this->_indent . ' * @param' .
                str_repeat(' ', $tagLength - 4) . $param['type'] .
                str_repeat(' ', $paramLength - strlen($param['type']) + 1) .
                $param['var'];
            if (null !== $param['desc']) {
                $tags .= ' ' . $param['desc'] . PHP_EOL;
            } else {
                $tags .= PHP_EOL;
            }
        }

        // Format throw tag
        if (array_key_exists('throws', $this->_tags)) {
            $tags .= $this->_indent . ' * @throws' .
                 str_repeat(' ', $tagLength - 5) .
                 $this->_tags['throws'] . PHP_EOL;
        }

        // Format return tag
        if (array_key_exists('return', $this->_tags)) {
            $tags .= $this->_indent . ' * @return' .
                 str_repeat(' ', $tagLength - 5) .
                 $this->_tags['return']['type'];
            if (null !== $this->_tags['return']['desc']) {
                $tags .= ' ' . $this->_tags['return']['desc'] . PHP_EOL;
            } else {
                $tags .= PHP_EOL;
            }
        }

        return ((null !== $tags) && (null !== $this->_desc)) ? $this->_indent . ' * ' . PHP_EOL . $tags : $tags;
    }

    /**
     * Get the longest tag length
     *
     * @return int
     */
    protected function _getTagLength()
    {
        $length = 0;

        foreach ($this->_tags as $key => $value) {
            if (strlen($key) > $length) {
                $length = strlen($key);
            }
        }

        return $length;
    }

    /**
     * Get the longest param type length
     *
     * @return int
     */
    protected function _getParamLength()
    {
        $length = 0;

        foreach ($this->_tags['param'] as $param) {
            if (strlen($param['type']) > $length) {
                $length = strlen($param['type']);
            }
        }

        return $length;
    }

    /**
     * Print docblock
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render(true);
    }

}