<?php

class Cart
{
  private $cart;
  private $products;
  private $coupons;

  // Apply each coupon on products list
  private function applyDiscounts()
  {
    foreach ($this->coupons as $coupone) {
      $coupone->apply($this->products);
    }
  }

  // Loop through items and calculate total
  private function calculateTotal()
  {
    $total = 0;
    foreach ($this->products as $product) {
      if ($product->discounted) {
        $total += $product->discounted;
      } else {
        $total += $product->price;
      }
    }
    return $total;
  }

  // Split coupons and Products
  private function prepareProductsAndCoupons()
  {
    foreach ($cart as $key => $item) {
      if ($item instanceof Product) {
        $this->products[$item->id] = $item;
      }
      if ($item instanceof Coupon) {
        $this->coupons[$item->id] = $item;
        if ($item instanceof NextCoupone) {
          $this->coupons[$item->id]->target = $this->findNextItem($key) ? $this->findNextItem($key) : null;
        }
      }
    }
  }

  // Looking for the next item after Coupon in sequence
  private function findNextItem($key)
  {
    for ($i = $key; $i <= count($this->cart); $i++) {
      if ($this->cart[$i] instanceof Coupon) {
        continue;
      }
      if ($this->cart[$i] instanceof Product) {
        return $this->cart[$i]->id;
      }
    }
    return false;
  }

  // Calculate total
  public function totalPrice()
  {
    $this->prepareProductsAndCoupons();
    $this->applyDiscounts();
    return $this->calculateTotal();
  }
}
