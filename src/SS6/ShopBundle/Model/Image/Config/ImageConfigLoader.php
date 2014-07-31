<?php

namespace SS6\ShopBundle\Model\Image\Config;

use SS6\ShopBundle\Component\Condition;
use SS6\ShopBundle\Model\Image\Config\ImageConfig;
use SS6\ShopBundle\Model\Image\Config\ImageEntityConfig;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Parser;

class ImageConfigLoader {

	/**
	 * @var \Symfony\Component\Filesystem\Filesystem
	 */
	private $filesystem;

	/**
	 * @var SS6\ShopBundle\Model\Image\Config\ImageEntityConfig[]
	 */
	private $foundEntityConfigs;

	/**
	 * @var array
	 */
	private $foundEntityNames;

	/**
	 * @var array
	 */
	private $filenameMethodsByType;

	/**
	 * @param \Symfony\Component\Filesystem\Filesystem $filesystem
	 */
	public function __construct(Filesystem $filesystem) {
		$this->filesystem = $filesystem;
	}

	/**
	 * @param string $filename
	 * @return \SS6\ShopBundle\Model\Image\ImageConfig
	 */
	public function loadFromYaml($filename) {
		$yamlParser = new Parser();

		if (!$this->filesystem->exists($filename)) {
			throw new \Symfony\Component\Filesystem\Exception\FileNotFoundException(
				'File ' . $filename . ' does not exist'
			);
		}

		$imageConfigDefinition = new ImageConfigDefinition();
		$processor = new Processor();

		$inputConfig = $yamlParser->parse(file_get_contents($filename));
		$outputConfig = $processor->processConfiguration($imageConfigDefinition, array($inputConfig));

		$preparedConfig = $this->loadFromArray($outputConfig);

		$imageConfig = new ImageConfig($preparedConfig);
		
		return $imageConfig;
	}

	/**
	 * @param array $outputConfig
	 * @return \SS6\ShopBundle\Model\Image\Config\ImageEntityConfig[]
	 */
	public function loadFromArray($outputConfig) {
		$this->foundEntityConfigs = array();
		$this->foundEntityNames = array();

		foreach ($outputConfig as $entityConfig) {
			try {
				$this->processEntityConfig($entityConfig);
			} catch (\SS6\ShopBundle\Model\Image\Config\Exception\ImageConfigException $e) {
				throw new \SS6\ShopBundle\Model\Image\Config\Exception\EntityParseException(
					$entityConfig[ImageConfigDefinition::CONFIG_CLASS],
					$e
				);
			}
		}

		return $this->foundEntityConfigs;
	}

	/**
	 * @param array $entityConfig
	 * @throws \SS6\ShopBundle\Model\Image\Config\Exception\DuplicateEntityNameException
	 */
	private function processEntityConfig($entityConfig) {
		$entityClass = $entityConfig[ImageConfigDefinition::CONFIG_CLASS];
		$entityName = $entityConfig[ImageConfigDefinition::CONFIG_ENTITY_NAME];
		$this->filenameMethodsByType = array();

		if (
			!array_key_exists($entityClass, $this->foundEntityConfigs) &&
			!array_key_exists($entityName, $this->foundEntityNames)
		) {
			$types = $this->prepareTypes($entityConfig[ImageConfigDefinition::CONFIG_TYPES]);
			$sizes = $this->prepareSizes($entityConfig[ImageConfigDefinition::CONFIG_SIZES]);
			if (count($sizes) > 0) {
				$this->filenameMethodsByType[ImageEntityConfig::WITHOUT_NAME_KEY] =
					$entityConfig[ImageConfigDefinition::CONFIG_FILENAME_METHOD];
			}
			$imageEntityConfig = new ImageEntityConfig($entityName, $entityClass, $this->filenameMethodsByType, $types, $sizes);
			
			$this->foundEntityNames[$entityName] = $entityName;
			$this->foundEntityConfigs[$entityClass] = $imageEntityConfig;
		} else {
			throw new \SS6\ShopBundle\Model\Image\Config\Exception\DuplicateEntityNameException($entityName);
		}
	}

	/**
	 * @param array $sizesConfig
	 * @return \SS6\ShopBundle\Model\Image\Config\ImageSizeConfig[]
	 */
	private function prepareSizes($sizesConfig) {
		$result = array();
		foreach ($sizesConfig as $sizeConfig) {
			$sizeName = $sizeConfig[ImageConfigDefinition::CONFIG_SIZE_NAME];
			$key = Condition::ifNull($sizeName, ImageEntityConfig::WITHOUT_NAME_KEY);
			if (!array_key_exists($key, $result)) {
				$result[$key] = new ImageSizeConfig(
					$sizeName,
					$sizeConfig[ImageConfigDefinition::CONFIG_SIZE_WIDTH],
					$sizeConfig[ImageConfigDefinition::CONFIG_SIZE_HEIGHT],
					$sizeConfig[ImageConfigDefinition::CONFIG_SIZE_CROP]
				);
			} else {
				throw new \SS6\ShopBundle\Model\Image\Config\Exception\DuplicateSizeNameException($sizeName);
			}
		}

		return $result;
	}

	/**
	 * @param array $typesConfig
	 * @return array
	 */
	private function prepareTypes($typesConfig) {
		$result = array();
		foreach ($typesConfig as $typeConfig) {
			$typeName = $typeConfig[ImageConfigDefinition::CONFIG_TYPE_NAME];
			if (!array_key_exists($typeName, $result)) {
				$this->filenameMethodsByType[$typeName] = $typeConfig[ImageConfigDefinition::CONFIG_FILENAME_METHOD];
				$result[$typeName] = $this->prepareSizes($typeConfig[ImageConfigDefinition::CONFIG_SIZES]);
			} else {
				throw new \SS6\ShopBundle\Model\Image\Config\Exception\DuplicateTypeNameException($typeName);
			}
		}

		return $result;
	}

}