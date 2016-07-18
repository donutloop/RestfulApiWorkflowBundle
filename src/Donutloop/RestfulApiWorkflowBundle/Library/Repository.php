<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Donutloop\RestfulApiWorkflowBundle\Library;

use Doctrine\ORM\EntityRepository;

/**
 * Class Repository
 * @package AppBundle\Library
 */
class Repository extends EntityRepository
{
    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->getEntityManager()
                    ->createQuery(sprintf('SELECT COUNT(t.id) FROM %s as t ', $this->_entityName))
                    ->getSingleScalarResult();
    }
}
