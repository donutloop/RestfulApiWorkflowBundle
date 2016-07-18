<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\Donutloop\RestfulApiWorkflowBundle\Library;

use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowEntityInterface;

/**
 * Class DatabaseWorkflowTestEntity2
 * @package Tests\Donutloop\RestfulApiWorkflowBundle\Library
 */
class DatabaseWorkflowTestEntity2 implements DatabaseWorkflowEntityInterface
{
    /**
     * @inheritDoc
     */
    public function getLiteralType()
    {
        return 'DatabaseWorkflowTestEntity';
    }

    /**
     * @inheritDoc
     */
    public function getLiteralName()
    {
        return 'DatabaseWorkflowTestEntity';
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return uniqid();
    }
}