<?php
namespace App\Normalisation;

use App\Entity\Event;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class EventHydrator
{

    private EntityManagerInterface $entityManager;

    private SubjectRepository $subjectRepository;

    public function __construct(EntityManagerInterface $entityManager, SubjectRepository $subjectRepository)
    {
        $this->entityManager = $entityManager;
        $this->subjectRepository = $subjectRepository;
    }

    public function hydrate() : void
    {
//        $this->truncate();
        foreach ($this->subjectRepository->findAll() as $subject) {
            for($i = 0; $i < $subject->getHours(); $i++) {
                $this->entityManager->persist((new Event())->setSubject($subject));
            }
        }

        $this->entityManager->flush();
    }

    public function truncate()
    {
        $classMetaData = $this->entityManager->getClassMetadata(Event::class);
        $connection = $this->entityManager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();

        try {
            $q = $dbPlatform->getTruncateTableSql($classMetaData->getTableName(), true);
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
            $connection->executeQuery($q);
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw $e;
        }

    }


}