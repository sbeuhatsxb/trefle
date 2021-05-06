<?php

namespace App\Entity;

use App\Repository\PlantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlantRepository::class)
 */
class Plant
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $scientificName;

	/**
	 * @ORM\ManyToOne(targetEntity=Rank::class, inversedBy="plants")
	 */
	private $rank;

	/**
	 * @ORM\ManyToOne(targetEntity=Genus::class, inversedBy="plants")
	 */
	private $genus;

	/**
	 * @ORM\ManyToOne(targetEntity=Family::class, inversedBy="plants")
	 */
	private $family;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $bibliography;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $commonName;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $familyCommonName;

	/**
	 * @ORM\Column(type="text", nullable=true, nullable=true)
	 */
	private $imageUrl;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $flowerColor;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $flowerConspicuous;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $foliageColor;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $foliageTexture;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $fruitColor;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $fruitConspicuous;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $groundHumidity;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $growthForm;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $growthHabit;


	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $growthRate;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $ediblePart;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $vegetable;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $edible;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $light;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $soilNutriments;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $soilSalinity;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $anaerobicTolerance;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $atmosphericHumidity;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $averageHeightCm;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $maximumHeightCm;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $minimumRootDepthCm;

	/**
	 * @ORM\Column(type="float", nullable=true)
	 */
	private $phMinimum;

	/**
	 * @ORM\Column(type="float", nullable=true)
	 */
	private $phMaximum;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $plantingSowingDescription;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $plantingRowSpacingCm;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $plantingSpreadCm;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $synonyms;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $commonNames;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $urlPowo;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $urlPlantnet;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $urlGbif;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $urlWikipediaEn;

	/**
	 * @ORM\ManyToMany(targetEntity=GrowthMonths::class, inversedBy="plants")
	 */
	private $growthMonths;

	/**
	 * @ORM\ManyToMany(targetEntity=BloomMonths::class, inversedBy="plants")
	 */
	private $bloomMonths;

	/**
	 * @ORM\ManyToMany(targetEntity=FruitMonths::class, inversedBy="plants")
	 */
	private $fruitMonths;


	public function __construct()
	{
		$this->growthMonths = new ArrayCollection();
		$this->bloomMonths = new ArrayCollection();
		$this->fruitMonths = new ArrayCollection();
	}

	/**
	 * @return mixed
	 */
	public function getFlowerColor()
	{
		return $this->flowerColor;
	}

	/**
	 * @param mixed $flowerColor
	 * @return $this
	 */
	public function setFlowerColor($flowerColor)
	{
		$this->flowerColor = $flowerColor;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFlowerConspicuous()
	{
		return $this->flowerConspicuous;
	}

	/**
	 * @param mixed $flowerConspicuous
	 * @return $this
	 */
	public function setFlowerConspicuous($flowerConspicuous)
	{
		$this->flowerConspicuous = $flowerConspicuous;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFoliageColor()
	{
		return $this->foliageColor;
	}

	/**
	 * @param mixed $foliageColor
	 * @return $this
	 */
	public function setFoliageColor($foliageColor)
	{
		$this->foliageColor = $foliageColor;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFoliageTexture()
	{
		return $this->foliageTexture;
	}

	/**
	 * @param mixed $foliageTexture
	 * @return $this
	 */
	public function setFoliageTexture($foliageTexture)
	{
		$this->foliageTexture = $foliageTexture;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFruitColor()
	{
		return $this->fruitColor;
	}

	/**
	 * @param mixed $fruitColor
	 * @return $this
	 */
	public function setFruitColor($fruitColor)
	{
		$this->fruitColor = $fruitColor;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFruitConspicuous()
	{
		return $this->fruitConspicuous;
	}

	/**
	 * @param mixed $fruitConspicuous
	 * @return $this
	 */
	public function setFruitConspicuous($fruitConspicuous)
	{
		$this->fruitConspicuous = $fruitConspicuous;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getGrowthForm()
	{
		return $this->growthForm;
	}

	/**
	 * @param mixed $growthForm
	 * @return $this
	 */
	public function setGrowthForm($growthForm)
	{
		$this->growthForm = $growthForm;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getGrowthHabit()
	{
		return $this->growthHabit;
	}

	/**
	 * @param mixed $growthHabit
	 * @return $this
	 */
	public function setGrowthHabit($growthHabit)
	{
		$this->growthHabit = $growthHabit;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getGrowthRate()
	{
		return $this->growthRate;
	}

	/**
	 * @param mixed $growthRate
	 * @return $this
	 */
	public function setGrowthRate($growthRate)
	{
		$this->growthRate = $growthRate;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEdiblePart()
	{
		return $this->ediblePart;
	}

	/**
	 * @param mixed $ediblePart
	 * @return $this
	 */
	public function setEdiblePart($ediblePart)
	{
		$this->ediblePart = $ediblePart;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEdible()
	{
		return $this->edible;
	}

	/**
	 * @param mixed $edible
	 * @return $this
	 */
	public function setEdible($edible)
	{
		$this->edible = $edible;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLight()
	{
		return $this->light;
	}

	/**
	 * @param mixed $light
	 * @return $this
	 */
	public function setLight($light)
	{
		$this->light = $light;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSoilNutriments()
	{
		return $this->soilNutriments;
	}

	/**
	 * @param mixed $soilNutriments
	 * @return $this
	 */
	public function setSoilNutriments($soilNutriments)
	{
		$this->soilNutriments = $soilNutriments;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSoilSalinity()
	{
		return $this->soilSalinity;
	}

	/**
	 * @param mixed $soilSalinity
	 * @return $this
	 */
	public function setSoilSalinity($soilSalinity)
	{
		$this->soilSalinity = $soilSalinity;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAnaerobicTolerance()
	{
		return $this->anaerobicTolerance;
	}

	/**
	 * @param mixed $anaerobicTolerance
	 * @return $this
	 */
	public function setAnaerobicTolerance($anaerobicTolerance)
	{
		$this->anaerobicTolerance = $anaerobicTolerance;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAtmosphericHumidity()
	{
		return $this->atmosphericHumidity;
	}

	/**
	 * @param mixed $atmosphericHumidity
	 * @return $this
	 */
	public function setAtmosphericHumidity($atmosphericHumidity)
	{
		$this->atmosphericHumidity = $atmosphericHumidity;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPhMaximum()
	{
		return $this->phMaximum;
	}

	/**
	 * @param mixed $phMaximum
	 * @return $this
	 */
	public function setPhMaximum($phMaximum)
	{
		$this->phMaximum = $phMaximum;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSynonyms()
	{
		return $this->synonyms;
	}

	/**
	 * @param mixed $synonyms
	 * @return $this
	 */
	public function setSynonyms($synonyms)
	{
		$this->synonyms = $synonyms;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCommonNames()
	{
		return $this->commonNames;
	}

	/**
	 * @param mixed $commonNames
	 * @return $this
	 */
	public function setCommonNames($commonNames)
	{
		$this->commonNames = $commonNames;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUrlPowo()
	{
		return $this->urlPowo;
	}

	/**
	 * @param mixed $urlPowo
	 * @return $this
	 */
	public function setUrlPowo($urlPowo)
	{
		$this->urlPowo = $urlPowo;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUrlPlantnet()
	{
		return $this->urlPlantnet;
	}

	/**
	 * @param mixed $urlPlantnet
	 * @return $this
	 */
	public function setUrlPlantnet($urlPlantnet)
	{
		$this->urlPlantnet = $urlPlantnet;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUrlGbif()
	{
		return $this->urlGbif;
	}

	/**
	 * @param mixed $urlGbif
	 * @return $this
	 */
	public function setUrlGbif($urlGbif)
	{
		$this->urlGbif = $urlGbif;
		return $this;
	}


	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return string|null
	 */
	public function getScientificName(): ?string
	{
		return $this->scientificName;
	}

	/**
	 * @param string $scientificName
	 * @return $this
	 */
	public function setScientificName(string $scientificName): self
	{
		$this->scientificName = $scientificName;

		return $this;
	}

	/**
	 * @return Rank|null
	 */
	public function getRank(): ?Rank
	{
		return $this->rank;
	}

	/**
	 * @param Rank|null $rank
	 * @return $this
	 */
	public function setRank(?Rank $rank): self
	{
		$this->rank = $rank;

		return $this;
	}

	/**
	 * @return Genus|null
	 */
	public function getGenus(): ?Genus
	{
		return $this->genus;
	}

	/**
	 * @param Genus|null $genus
	 * @return $this
	 */
	public function setGenus(?Genus $genus): self
	{
		$this->genus = $genus;

		return $this;
	}

	/**
	 * @return Family|null
	 */
	public function getFamily(): ?Family
	{
		return $this->family;
	}

	/**
	 * @param Family|null $family
	 * @return $this
	 */
	public function setFamily(?Family $family): self
	{
		$this->family = $family;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getBibliography(): ?string
	{
		return $this->bibliography;
	}

	/**
	 * @param string|null $bibliography
	 * @return $this
	 */
	public function setBibliography(?string $bibliography): self
	{
		$this->bibliography = $bibliography;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getCommonName(): ?string
	{
		return $this->commonName;
	}

	/**
	 * @param string|null $commonName
	 * @return $this
	 */
	public function setCommonName(?string $commonName): self
	{
		$this->commonName = $commonName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getFamilyCommonName(): ?string
	{
		return $this->familyCommonName;
	}

	/**
	 * @param string $familyCommonName
	 * @return $this
	 */
	public function setFamilyCommonName(string $familyCommonName): self
	{
		$this->familyCommonName = $familyCommonName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getImageUrl(): ?string
	{
		return $this->imageUrl;
	}

	/**
	 * @param string|null $imageUrl
	 * @return $this
	 */
	public function setImageUrl(?string $imageUrl): self
	{
		$this->imageUrl = $imageUrl;

		return $this;
	}


	/**
	 * @return int|null
	 */
	public function getGroundHumidity(): ?int
	{
		return $this->groundHumidity;
	}

	/**
	 * @param int|null $groundHumidity
	 * @return $this
	 */
	public function setGroundHumidity(?int $groundHumidity): self
	{
		$this->groundHumidity = $groundHumidity;

		return $this;
	}

	/**
	 * @return bool|null
	 */
	public function getVegetable(): ?bool
	{
		return $this->vegetable;
	}

	/**
	 * @param bool|null $vegetable
	 * @return $this
	 */
	public function setVegetable(?bool $vegetable): self
	{
		$this->vegetable = $vegetable;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getAverageHeightCm(): ?int
	{
		return $this->averageHeightCm;
	}

	/**
	 * @param int|null $averageHeightCm
	 * @return $this
	 */
	public function setAverageHeightCm(?int $averageHeightCm): self
	{
		$this->averageHeightCm = $averageHeightCm;

		return $this;
	}

	/**
	 * @return float|null
	 */
	public function getPhMinimum(): ?float
	{
		return $this->phMinimum;
	}

	/**
	 * @param float|null $phMinimum
	 * @return $this
	 */
	public function setPhMinimum(?float $phMinimum): self
	{
		$this->phMinimum = $phMinimum;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMaximumHeightCm()
	{
		return $this->maximumHeightCm;
	}

	/**
	 * @param mixed $maximumHeightCm
	 * @return $this
	 */
	public function setMaximumHeightCm($maximumHeightCm)
	{
		$this->maximumHeightCm = $maximumHeightCm;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMinimumRootDepthCm()
	{
		return $this->minimumRootDepthCm;
	}

	/**
	 * @param mixed $minimumRootDepthCm
	 * @return $this
	 */
	public function setMinimumRootDepthCm($minimumRootDepthCm)
	{
		$this->minimumRootDepthCm = $minimumRootDepthCm;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPlantingSowingDescription()
	{
		return $this->plantingSowingDescription;
	}

	/**
	 * @param mixed $plantingSowingDescription
	 * @return $this
	 */
	public function setPlantingSowingDescription($plantingSowingDescription)
	{
		$this->plantingSowingDescription = $plantingSowingDescription;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPlantingRowSpacingCm()
	{
		return $this->plantingRowSpacingCm;
	}

	/**
	 * @param mixed $plantingRowSpacingCm
	 * @return $this
	 */
	public function setPlantingRowSpacingCm($plantingRowSpacingCm)
	{
		$this->plantingRowSpacingCm = $plantingRowSpacingCm;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPlantingSpreadCm()
	{
		return $this->plantingSpreadCm;
	}

	/**
	 * @param mixed $plantingSpreadCm
	 * @return $this
	 */
	public function setPlantingSpreadCm($plantingSpreadCm)
	{
		$this->plantingSpreadCm = $plantingSpreadCm;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUrlWikipediaEn()
	{
		return $this->urlWikipediaEn;
	}

	/**
	 * @param mixed $urlWikipediaEn
	 * @return $this
	 */
	public function setUrlWikipediaEn($urlWikipediaEn)
	{
		$this->urlWikipediaEn = $urlWikipediaEn;
		return $this;
	}

	/**
	 * @return Collection|GrowthMonths[]
	 */
	public function getGrowthMonths(): Collection
	{
		return $this->growthMonths;
	}

	public function addGrowthMonth(GrowthMonths $growthMonth): self
	{
		if (!$this->growthMonths->contains($growthMonth)) {
			$this->growthMonths[] = $growthMonth;
		}

		return $this;
	}

	public function removeGrowthMonth(GrowthMonths $growthMonth): self
	{
		$this->growthMonths->removeElement($growthMonth);

		return $this;
	}

	/**
	 * @return Collection|BloomMonths[]
	 */
	public function getBloomMonths(): Collection
	{
		return $this->bloomMonths;
	}

	public function addBloomMonth(BloomMonths $bloomMonth): self
	{
		if (!$this->bloomMonths->contains($bloomMonth)) {
			$this->bloomMonths[] = $bloomMonth;
		}

		return $this;
	}

	public function removeBloomMonth(BloomMonths $bloomMonth): self
	{
		$this->bloomMonths->removeElement($bloomMonth);

		return $this;
	}

	/**
	 * @return Collection|FruitMonths[]
	 */
	public function getFruitMonths(): Collection
	{
		return $this->fruitMonths;
	}

	public function addFruitMonth(FruitMonths $fruitMonth): self
	{
		if (!$this->fruitMonths->contains($fruitMonth)) {
			$this->fruitMonths[] = $fruitMonth;
		}

		return $this;
	}

	public function removeFruitMonth(FruitMonths $fruitMonth): self
	{
		$this->fruitMonths->removeElement($fruitMonth);

		return $this;
	}

}
