Feature: Template autocomplete

  Scenario: Get template autocomplete
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/designer/features/template.json"

  Scenario: Get template autocomplete (not authorized)
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete"
    Then the response status code should be 401

  Scenario: Get template autocomplete (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete?field=name"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/designer/features/template.json"

  Scenario: Get template autocomplete (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete?field=name&order=ASC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/designer/features/template.json"

  Scenario: Get template autocomplete (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete?field=name&order=DESC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/designer/features/template.json"

  Scenario: Get template autocomplete (search f limit 1)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete?search=f&limit=1"
    And the JSON should be valid according to the schema "module/designer/features/template.json"
