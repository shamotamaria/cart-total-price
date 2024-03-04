<?php

// Abstract Coupon Class
abstract class Coupon {
  protected $id;
  public $discount; // 0.2 || 2

  // Abstract method that will be implemented per each Coupon class
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
        } else {
          $items[$key]->discounted = $item->price*(1 - $this->discount);
        }
      }
    }
  }
}

// Class implements method to apply: Take $D off of the Nth product of type T
class IndividualCoupon extends Coupon {

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

