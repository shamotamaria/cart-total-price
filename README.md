# Cart Total Price: Implementation using abstract method.

### Overview

This project implements a flexible and extensible system for applying coupon discounts to the total amount of a shopping cart using an abstract method approach. By leveraging abstract methods, the implementation provides a scalable solution that allows for easy addition of new coupon types and customization of discount calculation logic.

### Implementation

The implementation consists of the following components:

- **Abstract Coupon Class**: Defines the abstract method `apply()` which is responsible for calculating the discount based on the coupon type.

- **GenericCoupon, NextCoupon, IndividualCoupon**: Concrete subclasses of the abstract coupon class implement specific coupon types and provide custom discount calculation logic.

- **Cart Class**: Manages the items in the cart and applies coupon discounts accordingly.
- **Product Class**: Represents Product blueprint.





