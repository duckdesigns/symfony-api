<?php
declare(strict_types = 1);

namespace App\Tests\Util;

trait Database
{

    public function truncateDatabaseTables()
    {
        $sql = 'SET FOREIGN_KEY_CHECKS=0;
                TRUNCATE TABLE event;
                TRUNCATE TABLE location;
                TRUNCATE TABLE post;
                SET FOREIGN_KEY_CHECKS=1;';
        
        $kernel = self::bootKernel();
        /** @var ObjectManager $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $statement = $entityManager->getConnection()->prepare($sql);
        $statement->execute();
    }
}

