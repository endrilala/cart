# Cart [WIP]

Framework independent cart abstraction library.

![Travis](https://api.travis-ci.org/maldoinc/cart.svg?branch=master)
![CodeClimate](https://codeclimate.com/github/maldoinc/cart/badges/gpa.svg)
![Test Coverage](https://codeclimate.com/github/maldoinc/cart/badges/coverage.svg)

## Installation

Install the package through composer by running `composer require maldoinc/cart`.

Otherwise just include the `Maldoinc\Cart` folder in your project and `require 'Maldoinc/Cart/autoload.php'`

## Usage

Upon creation the cart class accepts a `CartPersistentInterface` which will supply the persistence mechanisms to the
storage of your choice. Included are Array(most frequently used on $_SESSION superglobal) and File storages.

To add new storage types(such as database) implement a the needed interface and hook the new class into your data.


```php
// Basic usage where we store the cart in the $_SESSION variable
$cart = new Cart(new ArrayPersistenceStrategy($_SESSION, 'cart_data'));
```

### Adding items

```php
$data = ['name' => 'T Shirt', 'color' => 'red'];
$item = new CartItem($identifier = 'PRODUCT_CODE', $price = 10, $quantity = 1, $data);
$item->setVatIncluded(true);
$item->setVatRate(20);

$cart->add($cartItem);
$cart->save();
```

### Updating items

In order to update an item simply fetch it via `$cart->get` and then adjust the properties as needed.

```php
try {
    $item = $cart->get($rowId);
    $item->setPrice(15);
    
    $cart->save();
} catch(ItemNotFoundException $e) {
    // handle appropriately
}
```

### Deleting

Deleting items is as easy as calling `$cart->delete` with the appropriate rowId.


### Clearing the whole cart

The method `$cart->clear` deletes all items on the cart.


### Summary

Cart summary is available via the `getSummary` method which provides method like `getGrossPrice`, `getVat`, and `getNetPrice`.


## CartItem

This is the class that represents an item in the cart. It's constructor accepts different parameters concerning the behaviour of an item.

Below is an example item creation:

```php
$item = new CartItem(
    $pricePerUnit = 10,
    $quantity = 2,
    $vatRate = 20,
    // denotes whether the vat is included in the pricePerUnit supplied
    $vatIncluded = true
);
```

### Price information

After creating a `CartItem` instance, all the price details are available via the `getPriceInfo` method which returns the following details: `netPrice`, `netPricePerUnit`, `vat`, `vatPerUnit`, `grossPrice` and `grossPricePerUnit`.

## Fees/Discounts

Discounts are supported via the `cartFees` property. It contains a collection of `CartFee`. These can be either flat values, percentages, discounts or as additional fees on the cart total. 


```php
// Flat discount
$cart->getCartFees()->add(new CartFee('15 EUR Discount', -15));

// Percent value
$cart->getCartFees()->add(new CartFee('2% Discount', '-2%'));
```

Processing and or other fees that increase the total of the cart should be added as cart items to better handle the different VAT rates each individual fee may have.
