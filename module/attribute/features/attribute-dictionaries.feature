Feature: Attribute dictionaries

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get attribute types dictionary
    And I send a "GET" request to "/api/v1/en_GB/dictionary/attributes/types"
    Then the response status code should be 200

  Scenario: Get attribute groups dictionary
    And I send a "GET" request to "/api/v1/en_GB/dictionary/attributes/groups"
    Then the response status code should be 200

  Scenario: Get attribute image formats dictionary
    And I send a "GET" request to "/api/v1/en_GB/dictionary/image_format"
    Then the response status code should be 200

  Scenario: Get attribute currencies dictionary
    And I send a "GET" request to "/api/v1/en_GB/dictionary/currencies"
    Then the response status code should be 200

  Scenario: Get attribute date formats dictionary
    And I send a "GET" request to "/api/v1/en_GB/dictionary/date_format"
    Then the response status code should be 200
