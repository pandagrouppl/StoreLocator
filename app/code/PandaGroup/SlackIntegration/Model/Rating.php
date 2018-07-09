<?php

namespace PandaGroup\SlackIntegration\Model;

class Rating{
    protected $connection;

    public function __construct()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();;
        $this->connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
    }

    public function getRatingCodeById($id){
        $result =  $this->connection->fetchAll("SELECT rating_code FROM rating WHERE rating_id =  $id");
        return $result[0]['rating_code'];
    }

}

?>