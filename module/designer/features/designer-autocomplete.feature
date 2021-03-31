Feature: Template autocomplete

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template autocomplete
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "designer/features/template.json"

  Scenario: Get template autocomplete (order by code)
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete?field=label"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "designer/features/template.json"

  Scenario: Get template autocomplete (order ASC)
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete?field=label&order=ASC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "designer/features/template.json"

  Scenario: Get template autocomplete (order DESC)
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete?field=label&order=DESC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "designer/features/template.json"

  Scenario: Get template autocomplete (search f limit 1)
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete?search=f&limit=1"
    And the JSON should be valid according to the schema "designer/features/template.json"
