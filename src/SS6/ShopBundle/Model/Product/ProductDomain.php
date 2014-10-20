<?php

namespace SS6\ShopBundle\Model\Product;

use Doctrine\ORM\Mapping as ORM;
use SS6\ShopBundle\Model\Product\Product;

/**
 * @ORM\Table(name="product_domains")
 * @ORM\Entity
 */
class ProductDomain {

	/**
	 * @var \SS6\ShopBundle\Model\Product\Product
	 *
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="SS6\ShopBundle\Model\Product\Product")
	 */
	private $product;

	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 */
	private $domainId;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $hidden;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $visible;

	public function __construct(Product $product, $domainId, $hidden) {
		$this->product = $product;
		$this->domainId = $domainId;
		$this->hidden = $hidden;
		$this->visible = false;
	}

	/**
	 * @return int
	 */
	public function getDomainId() {
		return $this->domainId;
	}

	/**
	 * @return boolean
	 */
	public function isHidden() {
		return $this->hidden;
	}

	/**
	 * @param boolean $hidden
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	/**
	 * @return boolean
	 */
	public function isVisible() {
		return $this->visible;
	}

}