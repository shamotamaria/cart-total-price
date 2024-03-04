<?php
class Product {
  public $id;
  public $type;
  public $price;
  public $discounted;
}

abstract class Coupon {
  protected $id;
  public $discount; // 0.2 || 2

  abstract public function apply(&$items);

}

// Class implements method to apply: Take N% off each individual product in the cart
class GenericCoupon extends Coupon {

  public function apply(&$items = []) {
    foreach($items as $key => $item) {
      if($items[$key]->discounted) {
        $items[$key]->discounted = $items[$key]->discounted*(1 - $this->discount);
      } else{
        $items[$key]->discounted = $item->price*(1 - $this->discount);
      }
    }
  }
}

// Class implements method to apply: Take P% off the next product in the cart
class NextCoupon extends Coupon {

  public $target; //id of the next product in the  card

  public function apply(&$items = []) {
    if(!$this->target) return; // if target product id is null - doing nothing

    foreach($items as $key => $item) {
     if($key === $this->target) {
      if($items[$key]->discounted) {
        $items[$key]->discounted = $items[$key]->discounted*(1 - $this->discount);
      } else{
        $items[$key]->discounted = $item->price*(1 - $this->discount);
      }
     }
    }
  }
}

// Class implements method to apply: Take $D off of the Nth product of type T
class IndividualCoupon extends Coupon{
  private $product_type;
  private $position;
  public $target;

  public function apply(&$items = []) {
    $count = 0;
    foreach($items as $key => $item) {
      if($item->type === $this->product_type) {
        $count++;
        if($count === $this->target) {
          if($items[$key]->discounted) {
            $items[$key]->discounted -= $this->discount;
          } else{
            $items[$key]->discounted = $item->price - $this->discount;
          }
        }
      }
    }
  }
}

class Cart {
  private $cart;
  private $products;
  private $coupons;

  // Apply each coupon on products list
  private function applyDiscounts() {
    foreach($this->coupons as $coupone) {
     $coupone->apply($this->products);
    }
  }

  // Loop through items and calculate total
  private function calculateTotal() {
    $total = 0;
    foreach($this->products as $product) {
      if($product->discounted_price) {
        $total += $product->discounted;
      } else {
        $total += $product->price;
      }
    }
    return $total;
  }

  // Split coupons and Products
  private function prepareProductsAndCoupons() {
    foreach($cart as $key => $item) {
       if($item instanceof Product) {
        $this->products[$item->id] = $item;
       }
       if($item instanceof Coupon) {
        $this->coupons[$item->id] = $item;
          if($item instanceof NextCoupone) {
            $this->coupons[$item->id]->target = $this->findNextItem($key)?$this->findNextItem($key):null;
          }
      }
    }
  }

  // Looking for the next item after Coupon in sequence
  private function findNextItem($key) {
    for ($i = $key; $i <= count($this->cart); $i++) {
      if($this->cart[$i] instanceof Coupon) {
        continue;
      }
      if($this->cart[$i] instanceof Product) {
        return $this->cart[$i]->id;
      }
    }
    return false;
  }

  // Calculate total
  public function totalPrice() {
    $this->prepareProductsAndCoupons();
    $this->applyDiscounts();
    return $this->calculateTotal();
  }
}
