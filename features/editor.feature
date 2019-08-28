Feature: Testing editor component

  Background:
    And I login as "test@ergonode.com" with "123"

  Scenario: I create draft product
    And I get attribute group dictionary
    And I fill template correctly
    And I create template
    And I get 201 result code
    And I get template Id
    And I create product witch sku "SKU_NUMBER"
    And I get 201 result code
    And I get product Id
    And I create product draft
    And I get 201 result code
    And I get draft Id
    And I switch to language PL
    And I fill text attribute correctly
    And I create attribute
    And I get 201 result code
    And I get attribute Id
    And I set attribute "ATTRIBUTE VALUE"
    And I get 202 result code
    And I get Draft View
    And I get 200 result code
    And I apply draft
    Then I get 202 result code

