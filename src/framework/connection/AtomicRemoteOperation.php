<?php

namespace Framework\Connection;
use Framework\Connection\ITransaction;


/**
 * Description of AtomicRemoteOperation
 *
 * @author mochiwa
 */
class AtomicRemoteOperation {
    /**
     * @var ITransaction 
     */
    protected $transactionManager;
    
    public function __construct(ITransaction $transactionManager) {
        $this->transactionManager=$transactionManager;
    }
    
    /**
     * Break the autocommit, launch the procedure , if no error commit else rollback
     * @param callable $callback
     * @param array $arguments
     * @return type
     */
    public function __invoke(callable $callback,array $arguments) {
        try{
            $this->transactionManager->breakAutoCommit();
            $dataReturned= call_user_func_array($callback,$arguments);
            $this->transactionManager->commit();
        } catch (\Exception $ex) {
            $this->transactionManager->rollback();
        }
        return $dataReturned;
    }
}
