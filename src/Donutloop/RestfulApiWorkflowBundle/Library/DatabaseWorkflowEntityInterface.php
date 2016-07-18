<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Donutloop\RestfulApiWorkflowBundle\Library;

/**
 * Interface DatabaseWorkflowEntityInterface
 * @package Donutloop\RestfulApiWorkflowBundle\Library
 */
interface DatabaseWorkflowEntityInterface
{
    /**
     * @return string
     */
    public function getLiteralType();

    /**
     * @return string
     */
    public function getLiteralName();

    /**
     * @return int
     */
    public function getIdentifier();
    
}