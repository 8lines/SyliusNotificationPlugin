@applying_cart_link
Feature: Preventing of applying not existing cart link
    In order to not add products and promotions to the cart when given cart link does not exist
    As a Visitor
    I want to have an error when I try to use not existing cart link

    Background:
        Given the store operates on a single channel in "United States"

    @ui @api
    Scenario: Trying to use cart link that does not exist
        When I use cart link with slug "clothes2023"
        Then there should appear an error that used cart link does not exist
