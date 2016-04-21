<?php

namespace SS6\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SS6\ShopBundle\Component\DataFixture\AbstractReferenceFixture;
use SS6\ShopBundle\DataFixtures\Base\CategoryRootDataFixture;
use SS6\ShopBundle\Model\Category\CategoryData;
use SS6\ShopBundle\Model\Category\CategoryFacade;

class CategoryDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface {

	const PREFIX = 'category_';

	const ELECTRONICS = 'electronics';
	const TV = 'tv';
	const PHOTO = 'photo';
	const PRINTERS = 'printers';
	const PC = 'pc';
	const PHONES = 'phones';
	const COFFEE = 'coffee';
	const BOOKS = 'books';
	const TOYS = 'toys';
	const GARDEN_TOOLS = 'garden_tools';
	const FOOD = 'food';

	/**
	 * @param \Doctrine\Common\Persistence\ObjectManager $manager
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	public function load(ObjectManager $manager) {

		$categoryData = new CategoryData();

		$categoryData->name = ['cs' => 'Elektro', 'en' => 'Electronics'];
		$categoryData->descriptions = [
			1 => 'Spotřební elektronika zahrnuje elektronická zařízení každodenního (nebo alespoň častého) použití pro komunikaci, '
				. 'v kanceláři i doma.',
			2 => 'Our electronics include devices used for entertainment (flat screen TVs, DVD players, DVD movies, iPods, '
				. 'video games, remote control cars, etc.), communications (telephones, cell phones, e-mail-capable laptops, etc.) '
				. 'and home office activities (e.g., desktop computers, printers, paper shredders, etc.).',
		];
		$categoryData->parent = $this->getReference(CategoryRootDataFixture::ROOT);
		$electronicsCategory = $this->createCategory($categoryData, self::ELECTRONICS);

		$categoryData->name = ['cs' => 'Televize, audio', 'en' => 'TV, audio'];
		$categoryData->descriptions = [
			1 => 'Televize (z řeckého tele – daleko a latinského vize – vidět) je široce používané jednosměrné dálkové plošné vysílání'
				. ' (tzv. broadcasting) a individuální přijímání televizního vysílání – obrazu a zvuku do televizoru. '
				. 'Výrazně přispívá k celkové socializaci lidí takřka po celém světě.',
			2 => 'Television or TV is a telecommunication medium used for transmitting sound with moving images in monochrome '
				. '(black-and-white), or in color, and in two or three dimensions',
		];
		$categoryData->parent = $electronicsCategory;
		$this->createCategory($categoryData, self::TV);

		$categoryData->name = ['cs' => 'Fotoaparáty', 'en' => 'Cameras & Photo'];
		$categoryData->descriptions = [
			1 => 'Fotoaparát je zařízení sloužící k pořizování a zaznamenání fotografií. Každý fotoaparát je v principu '
				. 'světlotěsně uzavřená komora s malým otvorem (nebo nějakou složitější optickou soustavou – objektivem), '
				. 'jímž dovnitř vstupuje světlo, a nějakým druhem světlocitlivé záznamové vrstvy na druhé straně, na níž dopadající '
				. 'světlo kreslí obraz.',
			2 => 'A camera is an optical instrument for recording or capturing images, which may be stored locally, '
				. 'transmitted to another location, or both.',
		];
		$this->createCategory($categoryData, self::PHOTO);

		$categoryData->name = ['cs' => 'Tiskárny', 'en' => null];
		$categoryData->descriptions = [
			1 => 'Tiskárna je periferní výstupní zařízení, které slouží k přenosu dat uložených v elektronické podobě na papír '
				. 'nebo jiné médium (fotopapír, kompaktní disk apod.). Tiskárnu připojujeme k počítači, ale může fungovat i samostatně.',
			2 => 'A printer is a peripheral which makes a persistent human readable representation of graphics or text on paper '
				. 'or similar physical media.',
		];
		$this->createCategory($categoryData, self::PRINTERS);

		$categoryData->name = ['cs' => 'Počítače & příslušenství', 'en' => null];
		$categoryData->descriptions = [
			1 => 'Počítač je zařízení a výpočetní technika, která zpracovává data pomocí předem vytvořeného programu. '
				. 'Současný počítač je elektronický a skládá se z hardwaru, který představuje fyzické části počítače (mikroprocesor, '
				. 'klávesnice, monitor atd.) a ze softwaru (operační systém a programy). Počítač je zpravidla ovládán uživatelem, '
				. 'který poskytuje počítači data ke zpracování prostřednictvím jeho vstupních zařízení a počítač výsledky prezentuje '
				. 'pomocí výstupních zařízení. V současnosti jsou počítače využívány téměř ve všech oborech lidské činnosti.',
			2 => 'A personal computer (PC) is a general-purpose computer whose size, capabilities, and original sale price '
				. 'make it useful for individuals, and is intended to be operated directly by an end-user with no intervening computer '
				. 'time-sharing models that allowed larger, more expensive minicomputer and mainframe systems to be used by many people, '
				. 'usually at the same time.',
		];
		$this->createCategory($categoryData, self::PC);

		$categoryData->name = ['cs' => 'Mobilní telefony', 'en' => null];
		$categoryData->descriptions = [
			1 => 'Mobilní telefony umožňují nejen komunikaci v rámci mobilní sítě, ale i spojení s pevnou telefonní sítí přímo volbou '
				. 'telefonního čísla na vestavěné klávesnici a poskytují širokou škálu dalších telekomunikačních služeb, jako jsou '
				. 'SMS, MMS, WAP a připojení na Internet. Protože patří k nejrozšířenějším elektronickým zařízením, výrobci je vybavují '
				. 'také dalšími funkcemi.',
			2 => 'A telephone is a telecommunications device that permits two or more users to conduct a conversation when they are '
				. 'too far apart to be heard directly. A telephone converts sound, typically and most efficiently the human voice, '
				. 'into electronic signals suitable for transmission via cables or other transmission media over long distances, '
				. 'and replays such signals simultaneously in audible form to its user.',
		];
		$this->createCategory($categoryData, self::PHONES);

		$categoryData->name = ['cs' => 'Kávovary', 'en' => null];
		$categoryData->descriptions = [
			1 => 'Kávovar je stroj určený pro výrobu kávy takovým způsobem, aby se voda nemusela vařit v oddělené nádobě. '
				. 'Existuje obrovské množství kávovarů, nicméně princip přípravy kávy je vždy stejný: do kovového či papírového filtru '
				. 'se vloží rozemletá káva. Filtr s kávou se vloží do kávovaru, kde se přes něj (většinou pod tlakem) nechá přetéct '
				. 'horká voda vytékající do připravené nádoby na kávu (hrnek, sklenici apod.).',
			2 => 'Coffeemakers or coffee machines are cooking appliances used to brew coffee. While there are many different types '
				. 'of coffeemakers using a number of different brewing principles, in the most common devices, coffee grounds '
				. 'are placed in a paper or metal filter inside a funnel, which is set over a glass or ceramic coffee pot, '
				. 'a cooking pot in the kettle family. Cold water is poured into a separate chamber, which is then heated up to the '
				. 'boiling point, and directed into the funnel.',
		];
		$this->createCategory($categoryData, self::COFFEE);

		$categoryData->name = ['cs' => 'Knihy', 'en' => 'Books'];
		$categoryData->descriptions = [
			1 => 'Kniha je sešitý nebo slepený svazek listů nebo skládaný arch papíru, kartonu, pergamenu nebo jiného materiálu, '
				. 'popsaný, potištěný nebo prázdný s vazbou a opatřený přebalem.',
			2 => 'A book is a set of written, printed, illustrated, or blank sheets, made of ink, paper, parchment, or other '
				. 'materials, fastened together to hinge at one side. A single sheet within a book is a leaf, and each side of a leaf '
				. 'is a page. A set of text-filled or illustrated pages produced in electronic format is known as an electronic book, '
				. 'or e-book.',
		];
		$categoryData->parent = $this->getReference(CategoryRootDataFixture::ROOT);
		$this->createCategory($categoryData, self::BOOKS);

		$categoryData->name = ['cs' => 'Hračky a další', 'en' => null];
		$categoryData->descriptions = [
			1 => 'Hračka je předmět používaný ke hře dětí, ale někdy i dospělých. Slouží k upoutání pozornosti dítěte, jeho zabavení, '
				. 'ale také k rozvíjení jeho motorických a psychických schopností. Hračky existují již po tisíce let. Panenky, '
				. 'zvířátka, vojáčci a miniatury nástrojů dospělých jsou nacházeny v archeologických vykopávkách od nepaměti.',
			2 => 'A toy is an item that can be used for play. Toys are generally played with by children and pets. '
				. 'Playing with toys is an enjoyable means of training young children for life in society. Different materials are '
				. 'used to make toys enjoyable to all ages. ',
		];
		$this->createCategory($categoryData, self::TOYS);

		$categoryData->name = ['cs' => 'Zahradní náčiní', 'en' => 'Garden tools'];
		$categoryData->descriptions = [
			1 => 'Oddělení zahradního náčiní je jedno z největších oddělení v naší nabídce. Za pozornost stojí zejména naše '
				. 'filtry různých druhů nečistot, avšak doporučujeme popatřit zrakem i na naše boční držáky plechů.',
			2 => 'A garden tool is any one of many tools made for gardens and gardening and overlaps with the range of tools '
				. 'made for agriculture and horticulture. Garden tools can also be hand tools and power tools.',
		];
		$this->createCategory($categoryData, self::GARDEN_TOOLS);

		$categoryData->name = ['cs' => 'Jídlo', 'en' => 'Food'];
		$categoryData->descriptions = [
			1 => 'Potravina je výrobek nebo látka určená pro výživu lidí a konzumovaná ústy v nezměněném nebo upraveném stavu. '
				. 'Potraviny se dělí na poživatiny a pochutiny. Potraviny mohou být rostlinného, živočišného nebo jiného původu.',
			2 => 'Food is any substance consumed to provide nutritional support for the body. It is usually of plant or '
				. 'animal origin, and contains essential nutrients, such as fats, proteins, vitamins, or minerals. The substance '
				. 'is ingested by an organism and assimilated by the organism\'s cells to provide energy, maintain life, '
				. 'or stimulate growth.',
		];
		$this->createCategory($categoryData, self::FOOD);
	}

	/**
	 * @param \SS6\ShopBundle\Model\Category\CategoryData $categoryData
	 * @param string|null $referenceName
	 * @return \SS6\ShopBundle\Model\Category\Category
	 */
	private function createCategory(CategoryData $categoryData, $referenceName = null) {
		$categoryFacade = $this->get(CategoryFacade::class);
		/* @var $categoryFacade \SS6\ShopBundle\Model\Category\CategoryFacade */

		$category = $categoryFacade->create($categoryData);
		if ($referenceName !== null) {
			$this->addReference(self::PREFIX . $referenceName, $category);
		}

		return $category;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDependencies() {
		return [
			CategoryRootDataFixture::class,
		];
	}

}
