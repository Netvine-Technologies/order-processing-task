<?php
declare(strict_types=1);

/*
Base Product (Abstract)
Pricing rule: price * quantity (Default)
*/

abstract class Product
{
    public function __construct(
        protected string $name,
        protected float $price,
        protected int $quantity
    ) {}

    // Polymorphic method
    abstract public function getTotal(): float;
}

/*
|--------------------------------------------------------------------------
Class: Digital Product
Pricing rule: price * quantity (default)
*/

class DigitalProduct extends Product
{
    public function __construct(
        string $name,
        float $price,
        int $quantity,
        private string $downloadLink
    ) {
        parent::__construct($name, $price, $quantity);
    }

    public function getTotal(): float
    {
        return $this->price * $this->quantity;
    }
}

/*
Class: Physical Product
Pricing Rule: price * quantity * weight
*/

class PhysicalProduct extends Product
{
    public function __construct(
        string $name,
        float $price,
        int $quantity,
        private float $weight
    ) {
        parent::__construct($name, $price, $quantity);
    }

    public function getTotal(): float
    {
        return $this->price * $this->quantity * $this->weight;
    }
}

/*
Class: Subscription Product
Pricing rule: price * quantity * duration (months)
*/

class SubscriptionProduct extends Product
{
    public function __construct(
        string $name,
        float $price,
        int $quantity,
        private int $durationMonths
    ) {
        parent::__construct($name, $price, $quantity);
    }

    public function getTotal(): float
    {
        return $this->price * $this->quantity * $this->durationMonths;
    }
}

/*
Class: Order Class
*/

class Order
{
    /** @var Product[] */
    private array $products = [];

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    public function calculateTotal(): float
    {
        $total = 0.0;

        foreach ($this->products as $product) {
            // Polymorphism — no if/else
            $total += $product->getTotal();
        }

        return $total;
    }
}

/*
Usage Example
*/

$order = new Order();

$digital = new DigitalProduct(
    "E-Book",
    10.00,
    2,
    "https://download-link.com"
);

$physical = new PhysicalProduct(
    "Laptop",
    1000.00,
    1,
    2.5
);

$subscription = new SubscriptionProduct(
    "Streaming Service",
    15.00,
    1,
    12
);

$order->addProduct($digital);
$order->addProduct($physical);
$order->addProduct($subscription);

echo "Order Total: £" . $order->calculateTotal() . PHP_EOL;