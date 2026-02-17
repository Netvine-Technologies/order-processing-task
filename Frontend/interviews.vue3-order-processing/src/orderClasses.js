class Product {
    constructor(name, price, quantity) {
      this.name = name;
      this.price = price;
      this.quantity = quantity;
    }
  
    calculatePrice() {
      return this.price * this.quantity;
    }
  }
  
  class DigitalProduct extends Product {
    constructor(name, price, quantity, downloadLink) {
      super(name, price, quantity);
      this.downloadLink = downloadLink;
    }
  }
  
  class PhysicalProduct extends Product {
    constructor(name, price, quantity, weight) {
      super(name, price, quantity);
      this.weight = weight;
    }
  
    calculatePrice() {
      return this.price * this.quantity * this.weight;
    }
  }
  
  class SubscriptionProduct extends Product {
    constructor(name, price, quantity, subscriptionDuration) {
      super(name, price, quantity);
      this.subscriptionDuration = subscriptionDuration;
    }
  }
  
  // Order class
  class Order {
    constructor() {
      this.products = [];
    }
  
    addProduct(product) {
      this.products.push(product);
    }
  
    calculateTotal() {
      return this.products.reduce(
        (total, product) => total + product.calculatePrice(),
        0
      );
    }
    // slighlty extending the functionality of an order
    removeProduct(index) {
      this.products.splice(index, 1);
    }
  }

  // Slightly extending order logic.
export class ProductFactory {
  static registry = {
    digital: (data) =>
      new DigitalProduct(
        data.name,
        data.price,
        data.quantity,
        data.downloadLink
      ),

    physical: (data) =>
      new PhysicalProduct(
        data.name,
        data.price,
        data.quantity,
        data.weight
      ),

    subscription: (data) =>
      new SubscriptionProduct(
        data.name,
        data.price,
        data.quantity,
        data.subscriptionDuration
      ),
  };

  static create(type, data) {
    const creator = this.registry[type];
    if (!creator) {
      throw new Error(`Unknown product type: ${type}`);
    }

    return creator(data);
  }
}



  
  export { Order, Product, DigitalProduct, PhysicalProduct, SubscriptionProduct };
  