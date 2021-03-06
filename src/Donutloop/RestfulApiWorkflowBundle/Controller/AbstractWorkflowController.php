<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Donutloop\RestfulApiWorkflowBundle\Controller;

use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseEntryInterface;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflow;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowAwareInterface;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowEntityInterface;
use Donutloop\RestfulApiWorkflowBundle\Library\ViewData;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractWorkflowController extends ApiController {

    abstract public function getWorkflow(): DatabaseWorkflowAwareInterface;

    /**
     * @return View
     */
    public function handleGetOne($id) {

        $workflow = $this->getWorkflow();

        try{
            $entity = $workflow->get($id);
        }catch (EntityNotFoundException $e){
            return $this->handleNotFound($e->getMessage());
        }

        return $this->prepareView(new ViewData(Codes::HTTP_OK, $entity));
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function handleDelete(int $id) {

        $workflow = $this->getWorkflow();

        try{
            $entity = $workflow->get($id);
        }catch (EntityNotFoundException $e){
            return $this->handleNotFound($e->getMessage());
        }

        $workflow->delete($entity);

        return $this->handleSuccess(sprintf('Dataset successfully removed (id: %d)', $id));
    }

    /**
     * @param DatabaseEntryInterface $entry
     * @return View
     */
    public function handleCreate(DatabaseEntryInterface $entry){

        $callback = function(DatabaseWorkflowAwareInterface $workflow, DatabaseEntryInterface $entry) {
            return $workflow->create($workflow->prepareEntity($entry));
        };

        return $this->process($entry, $callback , 'Dataset unsuccessfully created');
    }

    /**
     * @param DatabaseEntryInterface $entry
     * @return View
     */
    public function handleUpdate(DatabaseEntryInterface $entry): View {

        $callback = function(DatabaseWorkflowAwareInterface $workflow, DatabaseEntryInterface $entry) {
            return $workflow->update($workflow->prepareUpdateEntity($entry));
        };

        return $this->process($entry, $callback , 'Dataset unsuccessfully updated');
    }

    /**
     * @param $paramFetcher
     * @return View
     */
    public function handleFindAll($paramFetcher) {
        
        $callback = function(DatabaseWorkflowAwareInterface $workflow, $offset, $limit, $queryParam) {
            return $workflow->findAll($offset, $limit, $queryParam);
        };

        return $this->getWrapper($callback, $paramFetcher, null, $context = ['viewdata_list']);
    }

    /**
     * @param array $queryParam
     * @param $paramFetcher
     * @return View
     */
    public function handleFindAllBy(array $queryParam, $paramFetcher) {

        $callback = function(DatabaseWorkflowAwareInterface $workflow, $offset, $limit, $queryParam) {
            return $workflow->findAllBy($queryParam, $offset, $limit);
        };

        return $this->getWrapper($callback, $paramFetcher, $queryParam, $context = ['viewdata_list']);
    }

    /**
     * @param DatabaseEntryInterface $entry
     * @param callable $callback
     * @param string $message
     * @return View
     */
    private function process(DatabaseEntryInterface $entry, callable $callback, string $message): View {

        $workflow = $this->getWorkflow();

        try{
            /**
             * @var DatabaseWorkflowEntityInterface $entity
             */
            $entity = $callback($workflow, $entry);
        }catch (\Exception $e){
            return $this->handleError(Codes::HTTP_BAD_REQUEST, vsprintf('%s (id: %s)', [$message, $entry->getIdentifier()]));
        }

        return $this->handleSuccess(vsprintf('%s (id: %s)', [$message, $entity->getIdentifier()]));
    }

    /**
     * @param callable $callback
     * @param ParamFetcher $paramFetcher
     * @param null $queryParam
     * @throws NoResultException | \Exception
     * @return View
     */
    public function getWrapper(callable $callback, ParamFetcher $paramFetcher, $queryParam = null, $context): View {

        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        $data = null;

        try{
            $data = $callback($this->getWorkflow(), $offset, $limit, $queryParam);
        }catch (NoResultException $e) {
            $this->handleNotFound($e->getMessage());
        }catch( \Exception $e){
            $this->handleError(Codes::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return $this->prepareView(new ViewData(Codes::HTTP_OK, $data, ['offset' => $offset, 'limit' => $limit]), $context);
    }
}