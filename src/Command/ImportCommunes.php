<?php


namespace App\Command;


use App\Entity\Commune;
use App\Entity\Departement;
use App\Entity\Region;
use App\Repository\DepartementRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommunes extends Command
{

    public $regionMap = [
        "Auvergne-Rhône-Alpes" => ['Auvergne', 'Rhône-Alpes'],
        "Bourgogne-Franche-Comté" => ['Bourgogne', 'Franche-Comté'],
        "Bretagne" => ['Bourgogne', 'Bretagne'],
        "Centre-Val de Loire" => ['Centre'],
        "Corse" => ['Corse'],
        "Grand Est " => ['Alsace', 'Champagne-Ardenne', 'Lorraine'],
        "Hauts-de-France" => ['Nord-Pas-de-Calais', 'Picardie'],
        "Île-de-France" => ['Île-de-France'],
        "Normandie" => ['Basse-Normandie', 'Haute-Normandie'],
        "Nouvelle-Aquitaine" => ['Aquitaine', 'Limousin', 'Poitou-Charentes'],
        "Occitanie" => ['Languedoc-Roussillon', 'Midi-Pyrénées'],
        "Pays de la Loire" => ['Pays de la Loire'],
        "Provence-Alpes-Côte d'Azur" => ['Provence-Alpes-Côte d\'Azur'],
    ];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RegionRepository
     */
    private $regionRepository;
    /**
     * @var DepartementRepository
     */
    private $departementRepository;

    public function __construct(EntityManagerInterface $entityManager, RegionRepository $regionRepository, DepartementRepository $departementRepository)
    {
        parent::__construct('geo:communes');
        $this->entityManager    = $entityManager;
        $this->regionRepository = $regionRepository;
        $this->departementRepository = $departementRepository;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('geo:communes:import')
            ;
    }

    protected function getCodesPostaux()  {
        $file = './public/code-insee-postaux-geoflar.csv';

        $codes = [];

        $l = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1024, ";")) !== false) {
                if($l !== 0) {
                    // Nouvelle calédonie
                    if(isset($data[8]) && isset($data[9])) {
                        if(!array_key_exists($data[8], $codes)) {
                            $codes[$data[8]] = [];
                        }

                        if(!array_key_exists($data[9], $codes[$data[8]])) {
                            $codes[$data[8]][$data[9]] = [];
                        }

                        $codes[$data[8]][$data[9]][] = $data[2];
                    }
                }
                $l++;
            }
            fclose($handle);
        }

        return $codes;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = './public/communes-france.csv';

        var_dump($this->getCodesPostaux());die();
        $codePostaux = $this->getCodesPostaux();
        $l = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1024, ",")) !== false) {
                if($l !== 0) {
                    $commune = new Commune();
                    $commune->setName($data[6]);
                    $commune->setPopulation((int) $data[6]);
                    $departement = $this->departementRepository->findOneBy(['code' => $data[2]]);
                    $commune->setDepartement($departement);
                    $commune->setCode($data[5]);

                    $codeRegion = $departement->getRegion()->getCode();
                    var_dump($commune->getCode());
                    $codePostal = function () use($codePostaux, $codeRegion, $data, $commune) {
//                        var_dump($codePostaux[$codeRegion]);
                        if(isset($codePostaux[$codeRegion])) {
                            if(isset($codePostaux[$codeRegion][$data[5]])) {
                                $commune->setPostal($codePostaux[$codeRegion][$data[5]]);

                            }
                        }
                    };

                }

                $l++;
            }
            fclose($handle);
        }

        return 1;
    }
}