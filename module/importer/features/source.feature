Feature: Source module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get source type dictionary
    When I send a GET request to "/api/v1/en_GB/dictionary/sources"
    Then the response status code should be 200

  Scenario: Get not found type configuration
    When I send a Get request to "/api/v1/en_GB/sources/@@random_uuid@@/configuration"
    Then the response status code should be 404