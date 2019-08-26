Feature: Testing product component

  Background:
    When I login as "test@ergonode.com" with "123"

  Scenario: I create category
    Given I fill category witch code "category_323231122"
    And I create category
    And I get 201 result code
    And I remember category id
    Given I get added category
    And I get 200 result code

