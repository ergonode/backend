Feature: Attribute autocomplete

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get attribute autocomplete
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "attribute/features/attribute.json"


  Scenario: Get attribute autocomplete (order by code)
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete?field=code"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "attribute/features/attribute.json"

  Scenario: Get attribute autocomplete (order by code)
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete?field=code"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "attribute/features/attribute.json"

  Scenario: Get attribute autocomplete (order by label)
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete?field=label"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "attribute/features/attribute.json"

  Scenario: Get attribute autocomplete (order ASC)
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete?field=code&order=ASC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "attribute/features/attribute.json"

  Scenario: Get attribute autocomplete (order DESC)
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete?field=code&order=DESC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "attribute/features/attribute.json"

  Scenario: Get attribute autocomplete (type TEXT)
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete?type=TEXT"
    And the JSON should be valid according to the schema "attribute/features/attribute.json"

  Scenario: Get attribute autocomplete (system attributes only)
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete?system=true"
    And the JSON should be valid according to the schema "attribute/features/attribute.json"

  Scenario: Get attribute autocomplete (search f limit 1)
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete?search=f&limit=1"
    And the JSON should be valid according to the schema "attribute/features/attribute.json"
