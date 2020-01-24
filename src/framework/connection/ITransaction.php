<?php
namespace Framework\Connection;
/**
 * This interface must be implemented by all class that want use an 
 * AtomicRemoteOperation, for example a database connection
 *
 * @author mochiwa
 */
interface ITransaction {
    
    function breakAutoCommit();
    
    function commit();
    
    function rollback();
}
