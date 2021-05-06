<?php

namespace App\Command;

use App\Entity\BloomMonths;
use App\Entity\Family;
use App\Entity\FruitMonths;
use App\Entity\Genus;
use App\Entity\GrowthMonths;
use App\Entity\Plant;
use App\Entity\Rank;
use App\Entity\Url;
use App\Repository\FamilyRepository;
use App\Repository\GenusRepository;
use App\Repository\RankRepository;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

class ImportGenusCommand extends Command
{

	protected static $defaultName = 'app:importGenus';
	protected static $defaultDescription = 'Add a short description for your command';
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;

		parent::__construct();
	}

	protected function configure()
	{
		$this
			->setDescription(self::$defaultDescription)
			->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
			->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
	}

	/**
	 * @throws \League\Csv\UnableToProcessCsv
	 * @throws \League\Csv\InvalidArgument
	 * @throws \League\Csv\Exception
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$arg1 = $input->getArgument('arg1');

		if ($arg1) {
			$io->note(sprintf('You passed an argument: %s', $arg1));
		}

		if ($input->getOption('option1')) {
			// ...
		}
		/**
		 * @var Reader $reader
		 */
		$reader = Reader::createFromPath('src/Data/plants.csv', 'r');
		$reader->setHeaderOffset(0);
		$reader->setDelimiter("\t");
		$records = Statement::create()->process($reader);
		$records->getHeader();

		//Feed Urls
		$url = new Url();
		$url->setName("Powo");
		$url->setUrl("http://powo.science.kew.org/taxon/urn:lsid:ipni.org:names:");
		$this->entityManager->persist($url);
		$this->entityManager->flush();

		$url = new Url();
		$url->setName("Plantnet");
		$url->setUrl("https://identify.plantnet.org/species/the-plant-list/");
		$this->entityManager->persist($url);
		$this->entityManager->flush();

		$url = new Url();
		$url->setName("Gbif");
		$url->setUrl("https://www.gbif.org/species/");
		$this->entityManager->persist($url);
		$this->entityManager->flush();

		$url = new Url();
		$url->setName("Wikipedia");
		$url->setUrl("https://en.wikipedia.org/wiki/");
		$this->entityManager->persist($url);
		$this->entityManager->flush();

		//Feed months
		$months = [
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December'
		];

		for ($i = 1; $i <= 12; $i++) {
			$bloomMonth = new BloomMonths();
			$bloomMonth->setName($months[$i]);
			$fruitMonths = new FruitMonths();
			$fruitMonths->setName($months[$i]);
			$growthMonth = new GrowthMonths();
			$growthMonth->setName($months[$i]);
			$this->entityManager->persist($bloomMonth);
			$this->entityManager->persist($fruitMonths);
			$this->entityManager->persist($growthMonth);
		}
		$this->entityManager->flush();

		//Rank
		$ranks = [];
		$records = $reader->fetchColumn(2);
		foreach ($records as $record) {
			if (!in_array($record, $ranks)) {
				$ranks[] = $record;
			}
		}
		foreach ($ranks as $rank_) {
			$rank = new Rank();
			$rank->setName($rank_);
			$this->entityManager->persist($rank);
		}
		$this->entityManager->flush();

		//Genus
		$genes = [];
		$records = $reader->fetchColumn(3);
		foreach ($records as $record) {
			if (!in_array($record, $genes)) {
				$genes[] = $record;
			}
		}
		foreach ($genes as $gene) {
			$genus = new Genus();
			$genus->setName($gene);
			$this->entityManager->persist($genus);
		}
		$this->entityManager->flush();

		//Family
		$families = [];
		$records = $reader->fetchColumn(4);
		foreach ($records as $record) {
			if (!in_array($record, $families)) {
				$families[] = $record;
			}
		}
		foreach ($families as $family_) {
			$family = new Family();
			$family->setName($family_);
			$this->entityManager->persist($family);
		}
		$this->entityManager->flush();

		$months = [
			'jan' => 1,
			'feb' => 2,
			'mar' => 3,
			'apr' => 4,
			'may' => 5,
			'jun' => 6,
			'jul' => 7,
			'aug' => 8,
			'sep' => 9,
			'oct' => 10,
			'nov' => 11,
			'dec' => 12
		];

		$bloomMonthRepo = $this->entityManager->getRepository(BloomMonths::class);
		$fruitMonthRepo = $this->entityManager->getRepository(FruitMonths::class);
		$growthMonthRepo = $this->entityManager->getRepository(GrowthMonths::class);
		/** @var RankRepository $rankRepo */
		$rankRepo = $this->entityManager->getRepository(Rank::class);
		/** @var FamilyRepository $familyRepo */
		$familyRepo = $this->entityManager->getRepository(Family::class);
		/** @var GenusRepository $genusRepo */
		$genusRepo = $this->entityManager->getRepository(Genus::class);

		$records = $reader->getRecords(['id', 'scientific_name', 'rank', 'genus', 'family', 'year', 'author', 'bibliography', 'common_name', 'family_common_name', 'image_url', 'flower_color', 'flower_conspicuous', 'foliage_color', 'foliage_texture', 'fruit_color', 'fruit_conspicuous', 'fruit_months', 'bloom_months', 'ground_humidity', 'growth_form', 'growth_habit', 'growth_months', 'growth_rate', 'edible_part', 'vegetable', 'edible', 'light', 'soil_nutriments', 'soil_salinity', 'anaerobic_tolerance', 'atmospheric_humidity', 'average_height_cm', 'maximum_height_cm', 'minimum_root_depth_cm', 'ph_maximum', 'ph_minimum', 'planting_days_to_harvest', 'planting_description', 'planting_sowing_description', 'planting_row_spacing_cm', 'planting_spread_cm', 'synonyms', 'distributions', 'common_names', 'url_usda', 'url_tropicos', 'url_tela_botanica', 'url_powo', 'url_plantnet', 'url_gbif', 'url_openfarm', 'url_catminat', 'url_wikipedia_en']);
		$i = 0;
		foreach ($records as $offset => $record) {
			$plant = new Plant();
			$plant->setScientificName($record['scientific_name']);
			/** @var Rank $rank */
			$rank = $rankRepo->findOneBy(['name' => $record['rank']]);
			/** @var Family $family */
			$family = $familyRepo->findOneBy(['name' => $record['family']]);
			/** @var Genus $genus */
			$genus = $genusRepo->findOneBy(['name' => $record['genus']]);
			$plant->setRank($rank);
			$rank->addPlant($plant);
			$plant->setGenus($genus);
			$family->addPlant($plant);
			$plant->setFamily($family);
			$genus->addPlant($plant);
			if ($record['bibliography']) {
				$plant->setBibliography($record['bibliography']);
			}
			if ($record['common_name']) {
				$plant->setCommonName($record['common_name']);
			}
			if ($record['family_common_name']) {
				$plant->setFamilyCommonName($record['family_common_name']);
			}
			if ($record['image_url']) {
				$plant->setImageUrl($record['image_url']);
			}
			if ($record['flower_color']) {
				$plant->setFlowerColor($record['flower_color']);
			}
			if ($record['flower_conspicuous']) {
				$plant->setFlowerConspicuous($record['flower_conspicuous']);
			}
			if ($record['foliage_color']) {
				$plant->setFoliageColor($record['foliage_color']);
			}
			if ($record['foliage_texture']) {
				$plant->setFoliageTexture($record['foliage_texture']);
			}
			if ($record['fruit_color']) {
				$plant->setFruitColor($record['fruit_color']);
			}
			if ($record['fruit_conspicuous']) {
				$plant->setFruitConspicuous($record['fruit_conspicuous']);
			}
			if (!empty($record['fruit_months'])) {
				$fruitMonths = explode(',', $record['fruit_months']);
				foreach ($fruitMonths as $fruitMonth) {
					$fruit = $fruitMonthRepo->find($months[$fruitMonth]);
					$plant->addFruitMonth($fruit);
				}
			}
			if (!empty($record['bloom_months'])) {
				$bloomMonths = explode(',', $record['bloom_months']);
				foreach ($bloomMonths as $bloomMonth) {
					$bloom = $bloomMonthRepo->find($months[$bloomMonth]);
					$plant->addBloomMonth($bloom);
				}
			}
			if ($record['ground_humidity']) {
				$plant->setGroundHumidity($record['ground_humidity']);
			}

			if ($record['growth_form']) {
				$plant->setGrowthForm($record['growth_form']);
			}
			if ($record['growth_habit']) {
				$plant->setGrowthHabit($record['growth_habit']);
			}
			if (!empty($record['growth_months'])) {
				$growthMonths = explode(',', $record['growth_months']);
				foreach ($growthMonths as $growthMonth) {
					$growth = $growthMonthRepo->find($months[$growthMonth]);
					$plant->addGrowthMonth($growth);
				}
			}
			if ($record['growth_rate']) {
				$plant->setGrowthRate($record['growth_rate']);
			}
			if ($record['edible_part']) {
				$plant->setEdible($record['edible_part']);
			}
			if ($record['edible'] === 'true') {
				$plant->setEdiblePart(true);
			} else if ($record['edible'] === 'false') {
				$plant->setEdiblePart(true);
			}
			if ($record['vegetable'] === 'true') {
				$plant->setVegetable(true);
			} else if ($record['vegetable'] === 'false') {
				$plant->setVegetable(true);
			}
			if ($record['light']) {
				$plant->setLight($record['light']);
			}
			if ($record['soil_nutriments']) {
				$plant->setSoilNutriments($record['soil_nutriments']);
			}
			if ($record['soil_salinity']) {
				$plant->setSoilSalinity($record['soil_salinity']);
			}
			if ($record['anaerobic_tolerance']) {
				$plant->setAnaerobicTolerance($record['anaerobic_tolerance']);
			}
			if ($record['atmospheric_humidity']) {
				$plant->setAtmosphericHumidity($record['atmospheric_humidity']);
			}
			if ($record['average_height_cm']) {
				$plant->setAverageHeightCm($record['average_height_cm']);
			}
			if ($record['maximum_height_cm']) {
				$plant->setMaximumHeightCm($record['maximum_height_cm']);
			}
			if ($record['minimum_root_depth_cm']) {
				$plant->setMinimumRootDepthCm($record['minimum_root_depth_cm']);
			}
			if ($record['ph_maximum']) {
				$plant->setPhMaximum($record['ph_maximum']);
			}
			if ($record['ph_minimum']) {
				$plant->setPhMinimum($record['ph_minimum']);
			}
			if ($record['planting_sowing_description']) {
				$plant->setPlantingSowingDescription($record['planting_sowing_description']);
			}
			if ($record['planting_row_spacing_cm']) {
				$plant->setPlantingRowSpacingCm($record['planting_row_spacing_cm']);
			}
			if ($record['planting_spread_cm']) {
				$plant->setPlantingSpreadCm($record['planting_spread_cm']);
			}
			if ($record['synonyms']) {
				$plant->setSynonyms($record['synonyms']);
			}
			if ($record['common_names']) {
				$plant->setCommonNames($record['common_names']);
			}
			if ($record['url_powo']) {
				$plant->setUrlPowo(str_replace("http://powo.science.kew.org/taxon/urn:lsid:ipni.org:names:", "", $record['url_powo']));
			}
			if ($record['url_plantnet']) {
				$plant->setUrlPlantnet(str_replace("https://identify.plantnet.org/species/the-plant-list/", "", $record['url_plantnet']));
			}
			if ($record['url_gbif']) {
				$plant->setUrlGbif(str_replace("https://www.gbif.org/species/", "", $record['url_gbif']));
			}
			if ($record['url_wikipedia_en']) {

				$plant->setUrlWikipediaEn(str_replace("https://en.wikipedia.org/wiki/", "", $record['url_wikipedia_en']));
			}

			$this->entityManager->persist($plant);
			$this->entityManager->persist($genus);
			$this->entityManager->persist($family);
			$this->entityManager->persist($rank);
			if ($i % 50 === 0) {
				echo(".");
				$this->entityManager->flush();
			}
			$i++;
		}

		$this->entityManager->flush();

		$io->success('Youpi');

		return Command::SUCCESS;
	}
}
