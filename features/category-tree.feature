Feature: Testing product component

  Background:
    When I login as "test@ergonode.com" with "123"

  Scenario: I create category tree
    Given I fill category tree witch code "tree_1" and "name"
    And I create category tree
    And I get 201 result code
    And I remember category tree id
    Given I get added category tree
    And I get 200 result code

