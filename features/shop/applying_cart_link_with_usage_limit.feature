@applying_cart_link
Feature: Applying cart link with usage limit
    In order to be able to add products and promotions to the cart by entering the link
    As a Customer
    I want to have appropriate products and promotions added to my cart only if only when the given cart link has not been used too many times

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a "T-Shirt" configurable product
        And this product has "Large T-Shirt" variant priced at "$20.00"
        And the store has a "Jumper" configurable product
        And this product has "Large Jumper" variant priced at "$50.00"
        And I add this product to the cart
        And the store has promotion "Summer Sale" with coupon "SUMMER2023"
        And this promotion gives "$10.00" discount to every order
        And there is also a cart link "Clothes" with "clothes2023" slug
        And this cart link adds "Large T-Shirt" product variant to the cart
        And this cart link can be used 5 times

    @ui @api
    Scenario: Applying cart link with a usage limit
        When I use cart link with slug "clothes2023"
        Then I should see "T-Shirt" with quantity 1 in my cart
        And this item should have variant "Large T-Shirt"

    @ui @api
    Scenario: Applying cart link that has reached its usage limit
        Given cart link with "Clothes" code usage limit is already reached
        When I use cart link with slug "clothes2023"
        Then there should appear an error that used cart link does not exist
        And there should be one item in my cart
        And this item should have name "Jumper"
        And this item should have variant "Large Jumper"
