<?php
namespace Tests\Donutloop\RestfulApiWorkflowBundle\Unit\Library;

use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowCollection;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowEntityInterface;
use Tests\Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowTestEntity;
use Tests\Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowTestEntity2;

/**
 * Class DatabaseWorkflowCollectionTest
 * @package Tests\Donutloop\RestfulApiWorkflowBundle\Unit\Library
 */
class DatabaseWorkflowCollectionTest extends \PHPUnit_Framework_TestCase
{
   public function getEntity(): DatabaseWorkflowEntityInterface
   {
      return new DatabaseWorkflowTestEntity();
   }

   public function getEntity2(): DatabaseWorkflowEntityInterface
   {
       return new DatabaseWorkflowTestEntity2();
   }

   public function testConstructor()
   {
       $collection = new DatabaseWorkflowCollection([$this->getEntity()]);
       static::assertEquals(1, $collection->count());
   }

    public function testConstructorInvalidArg()
    {
        $this->setExpectedException("InvalidArgumentException");
        $collection = new DatabaseWorkflowCollection([$this->getEntity(), $this->getEntity2()]);
    }

    public function testAddEntity()
    {
       $collection = new DatabaseWorkflowCollection();
       
       $collection->addEntity($this->getEntity());
       $collection->addEntity($this->getEntity());

       static::assertEquals(2, $collection->count());
   }

    public function testAddEntityInvalidArg()
    {
        $this->setExpectedException("InvalidArgumentException");

        $collection = new DatabaseWorkflowCollection();

        $collection->addEntity($this->getEntity());
        $collection->addEntity($this->getEntity2());
    }

    public function testSetEntities()
    {
        $collection = new DatabaseWorkflowCollection();

        $collection->setEntities([$this->getEntity(), $this->getEntity()]);

        static::assertEquals(2, $collection->count());
    }

    public function testSetEntitiesInvalidArg()
    {
        $this->setExpectedException("InvalidArgumentException");

        $collection = new DatabaseWorkflowCollection();

        $collection->setEntities([$this->getEntity(), $this->getEntity2()]);
    }

   public function testRemoveEntity(){

       $entity = $this->getEntity();

       $id = $entity->getIdentifier();

       $collection = new DatabaseWorkflowCollection([$entity]);

       $collection->removeEntity($id);

       static::assertEquals(1, $collection->count());
   }
}