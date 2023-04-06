@applying_cart_link
Feature: Applying cart link
    In order to be able to add products and promotions to the cart by entering the link
    As a Visitor
    I want to have appropriate products and promotions added to my cart

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
    Scenario: Applying cart link that adds one product
        When I use cart link with slug "clothes2023"
        Then I should see "T-Shirt" with quantity 1 in my cart
        And this item should have variant "Large T-Shirt"
        And there should be no discount

    @ui @api
    Scenario: Applying cart link that adds multiple products
        Given the store has a "Coat" configurable product
        And this product has "Small Coat" variant priced at "$100.00"
        And the store has a "Shoes" configurable product
        And this product has "Medium Shoes" variant priced at "$90.00"
        And cart link with "Clothes" code adds "Small Coat" product variant to the cart
        And this cart link adds "Medium Shoes" product variant to the cart
        When I use cart link with slug "clothes2023"
        Then I should see "T-Shirt" with quantity 1 in my cart
        And this item should have variant "Large T-Shirt"
        And I should see "Coat" with quantity 1 in my cart
        And this item should have variant "Small Coat"
        And I should see "Shoes" with quantity 1 in my cart
        And this item should have variant "Medium Shoes"
        And there should be no discount

    @ui @api
    Scenario: Applying cart link that adds product and clears the cart
        Given cart link with "Clothes" code clears the cart
        When I use cart link with slug "clothes2023"
        Then there should be one item in my cart
        And this item should have name "T-Shirt"
        And this item should have variant "Large T-Shirt"
        And there should be no discount

    @ui @api
    Scenario: Applying cart link that is disabled
        Given cart link with "Clothes" code is disabled
        When I use cart link with slug "clothes2023"
        Then there should appear an error that used cart link does not exist
        And there should be one item in my cart
        And this item should have name "Jumper"
        And this item should have variant "Large Jumper"

    @ui @api
    Scenario: Applying cart link that adds product and applies promotion
        Given cart link with "Clothes" code applies specified "SUMMER2023" promotion coupon to the cart
        When I use cart link with slug "clothes2023"
        Then I should see "T-Shirt" with quantity 1 in my cart
        And this item should have variant "Large T-Shirt"
        And there should be "SUMMER2023" coupon in my cart
