<?php

namespace SS6\ShopBundle\Tests\Unit\Model\Cart;

use SS6\ShopBundle\Model\Cart\Cart;
use SS6\ShopBundle\Model\Cart\CartService;
use SS6\ShopBundle\Model\Cart\Item\CartItem;
use SS6\ShopBundle\Model\Customer\CustomerIdentifier;
use SS6\ShopBundle\Model\Pricing\Vat\Vat;
use SS6\ShopBundle\Model\Pricing\Vat\VatData;
use SS6\ShopBundle\Model\Product\Product;
use SS6\ShopBundle\Model\Product\ProductData;
use SS6\ShopBundle\Tests\Test\FunctionalTestCase;

class CartServiceTest extends FunctionalTestCase {

	public function testCannotAddProductFloatQuantityToCart() {
		$cartService = $this->getCartService();

		$customerIdentifier = new CustomerIdentifier('randomString');
		$product = $this->createProduct();

		$cartItems = [];
		$cart = new Cart($cartItems);

		$this->setExpectedException('SS6\ShopBundle\Model\Cart\Exception\InvalidQuantityException');
		$cartService->addProductToCart($cart, $customerIdentifier, $product, 1.1);
	}

	public function testCannotAddProductZeroQuantityToCart() {
		$cartService = $this->getCartService();

		$customerIdentifier = new CustomerIdentifier('randomString');
		$product = $this->createProduct();

		$cartItems = [];
		$cart = new Cart($cartItems);

		$this->setExpectedException('SS6\ShopBundle\Model\Cart\Exception\InvalidQuantityException');
		$cartService->addProductToCart($cart, $customerIdentifier, $product, 0);
	}

	public function testCannotAddProductNegativeQuantityToCart() {
		$cartService = $this->getCartService();

		$customerIdentifier = new CustomerIdentifier('randomString');
		$product = $this->createProduct();

		$cartItems = [];
		$cart = new Cart($cartItems);

		$this->setExpectedException('SS6\ShopBundle\Model\Cart\Exception\InvalidQuantityException');
		$cartService->addProductToCart($cart, $customerIdentifier, $product, -10);
	}

	public function testAddProductToCartMarksAddedProductAsNew() {
		$cartService = $this->getCartService();

		$customerIdentifier = new CustomerIdentifier('randomString');
		$product = $this->createProduct();

		$cartItems = [];
		$cart = new Cart($cartItems);

		$quantity = 2;

		$result = $cartService->addProductToCart($cart, $customerIdentifier, $product, $quantity);
		$this->assertTrue($result->getIsNew());
	}

	public function testAddProductToCartMarksRepeatedlyAddedProductAsNotNew() {
		$cartService = $this->getCartService();

		$customerIdentifier = new CustomerIdentifier('randomString');
		$product = $this->createProduct();

		$cartItem = new CartItem($customerIdentifier, $product, 1, '0.0');
		$cartItems = [$cartItem];
		$cart = new Cart($cartItems);

		$quantity = 2;

		$result = $cartService->addProductToCart($cart, $customerIdentifier, $product, $quantity);
		$this->assertFalse($result->getIsNew());
	}

	public function testAddProductResultContainsAddedProductQuantity() {
		$cartService = $this->getCartService();

		$customerIdentifier = new CustomerIdentifier('randomString');
		$product = $this->createProduct();

		$cartItems = [];
		$cart = new Cart($cartItems);

		$quantity = 2;

		$result = $cartService->addProductToCart($cart, $customerIdentifier, $product, $quantity);
		$this->assertSame($quantity, $result->getAddedQuantity());
	}

	public function testAddProductResultDoesNotContainPreviouslyAddedProductQuantity() {
		$cartService = $this->getCartService();

		$customerIdentifier = new CustomerIdentifier('randomString');
		$product = $this->createProduct();

		$cartItem = new CartItem($customerIdentifier, $product, 1, '0.0');
		$cartItems = [$cartItem];
		$cart = new Cart($cartItems);

		$quantity = 2;

		$result = $cartService->addProductToCart($cart, $customerIdentifier, $product, $quantity);
		$this->assertSame($quantity, $result->getAddedQuantity());
	}

	public function testCleanCartMakesCartEmpty() {
		$cartService = $this->getCartService();

		$customerIdentifier = new CustomerIdentifier('randomString');
		$product = $this->createProduct();

		$cartItem = new CartItem($customerIdentifier, $product, 1, '0.0');
		$cartItems = [$cartItem];
		$cart = new Cart($cartItems);

		$cartService->cleanCart($cart);

		$this->assertTrue($cart->isEmpty());
	}

	public function testMergeCartsReturnsCartWithSummedProducts() {
		$cartService = $this->getCartService();

		$product1 = $this->createProduct();
		$product2 = $this->createProduct();

		$sessionId1 = 'abc123';
		$sessionId2 = 'def456';
		$customerIdentifier1 = new CustomerIdentifier($sessionId1);
		$customerIdentifier2 = new CustomerIdentifier($sessionId2);

		$cartItem = new CartItem($customerIdentifier1, $product1, 2, '0.0');
		$cartItems = [$cartItem];
		$mainCart = new Cart($cartItems);

		$cartItem1 = new CartItem($customerIdentifier2, $product1, 3, '0.0');
		$cartItem2 = new CartItem($customerIdentifier2, $product2, 1, '0.0');
		$cartItems = [$cartItem1, $cartItem2];
		$mergingCart = new Cart($cartItems);

		$cartService->mergeCarts($mainCart, $mergingCart, $customerIdentifier1);

		foreach ($mainCart->getItems() as $item) {
			if ($item->getSessionId() !== $customerIdentifier1->getSessionId()) {
				$this->fail('Merged cart contain cartItem with wrong sessionId');
			}
		}

		$this->assertSame(2, $mergingCart->getItemsCount());
	}

	/**
	 * @return \SS6\ShopBundle\Model\Product\Product
	 */
	private function createProduct() {
		$price = 100;
		$vat = new Vat(new VatData('vat', 21));

		$productData = new ProductData();
		$productData->name = ['cs' => 'Any name'];
		$productData->price = $price;
		$productData->vat = $vat;
		$product = Product::create($productData);

		return $product;
	}

	/**
	 * @return \SS6\ShopBundle\Model\Cart\CartService
	 */
	private function getCartService() {
		return $this->getContainer()->get(CartService::class);
	}
}
