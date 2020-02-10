<?php


namespace App\Command;


use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportRegions extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct('geo:regions');
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('geo:regions:import')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = './public/regions-france.csv';

        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1024, ",")) !== false) {
                $region = new Region();
                $region->setName($data[1]);
                $region->setCode($data[0]);


                $this->entityManager->persist($region);
            }
            fclose($handle);
        }

        $this->entityManager->flush();

        return 1;
    }
}