<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Shipping
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Shipping;

/**
 * Shipping class
 *
 * @category   Pop
 * @package    Pop_Shipping
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.6.0
 */
class Shipping
{

    /**
     * Shipping adapter
     * @var mixed
     */
    protected $adapter = null;

    /**
     * Constructor
     *
     * Instantiate the shipping object
     *
     * @param  Adapter\AbstractAdapter $adapter
     * @return \Pop\Shipping\Shipping
     */
    public function __construct(Adapter\AbstractAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Access the adapter
     *
     * @return \Pop\Shipping\Adapter\AbstractAdapter
     */
    public function adapter()
    {
        return $this->adapter;
    }

    /**
     * Set ship to
     *
     * @param  array $shipTo
     * @return self
     */
    public function shipTo(array $shipTo)
    {
        $this->adapter->shipTo($shipTo);
        return $this;
    }

    /**
     * Set ship from
     *
     * @param  array $shipFrom
     * @return self
     */
    public function shipFrom(array $shipFrom)
    {
        $this->adapter->shipFrom($shipFrom);
        return $this;
    }

    /**
     * Set dimensions
     *
     * @param  array  $dimensions
     * @param  string $unit
     * @return self
     */
    public function setDimensions(array $dimensions, $unit = null)
    {
        $this->adapter->setDimensions($dimensions, $unit);
        return $this;
    }

    /**
     * Set dimensions
     *
     * @param  string $weight
     * @param  string $unit
     * @return self
     */
    public function setWeight($weight, $unit = null)
    {
        $this->adapter->setWeight($weight, $unit);
        return $this;
    }

    /**
     * Send transaction data
     *
     * @return void
     */
    public function send()
    {
        $this->adapter->send();
    }

}