Feature: Core module - static connection setup

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create unit duplicate - created in different feature(transactional-feature1) and rolled back
    And remember param "symbol_name_1" with value "@@random_symbol@@"
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "test_unit",
        "symbol": "@symbol_name_1@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "unit_id_1"
