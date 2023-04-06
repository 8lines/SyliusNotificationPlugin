@applying_cart_link
Feature: Applying cart link with an expiration date
    In order to be able to add products and promotions to the cart by entering the link
    As a Visitor
    I want to have appropriate products and promotions added to my cart only if the given cart link has not expired

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

    @ui @api
    Scenario: Applying cart link that has reached its usage limit
        Given cart link with "Clothes" code has already expired
        When I use cart link with slug "clothes2023"
        Then there should appear an error that used cart link does not exist
        And there should be one item in my cart
        And this item should have name "Jumper"
        And this item should have variant "Large Jumper"

    @ui @api
    Scenario: Applying cart link with an expiration date
        Given cart link with "Clothes" code expires tomorrow
        When I use cart link with slug "clothes2023"
        Then I should see "T-Shirt" with quantity 1 in my cart
        And this item should have variant "Large T-Shirt"
