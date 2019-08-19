Feature: Testing product component

  Background:
    When I login as "test@ergonode.com" with "123"

  Scenario: I get designer attributes
    When I fill template correctly
    And I create template
    And I get 201 result code
    And I get template Id
    And I create product witch sku "SKU_NUMBER_1"
    Then I get 201 result code
    And I get product Id

