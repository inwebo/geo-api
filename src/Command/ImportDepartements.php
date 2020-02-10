<?php


namespace App\Command;


use App\Entity\Departement;
use App\Entity\Region;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDepartements extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EntityManagerInterface
     */
    private $regionRepository;

    public function __construct(EntityManagerInterface $entityManager, RegionRepository $regionRepository)
    {
        parent::__construct('geo:departements');
        $this->entityManager    = $entityManager;
        $this->regionRepository = $regionRepository;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('geo:departements:import')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = './public/departements-france.csv';


        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1024, ",")) !== false) {

                $departement = new Departement();
                $departement->setCode($data[0]);
                $departement->setName($data[1]);

                $region = $this->regionRepository->findOneBy(['name' => $data[2]]);
                $departement->setRegion($region);

                $this->entityManager->persist($departement);
            }
            fclose($handle);
        }

        $this->entityManager->flush();

        return 1;
    }
}