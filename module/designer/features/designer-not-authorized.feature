Feature: Designer module

  Scenario: Get template autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/templates/autocomplete"
    Then the response status code should be 401

  Scenario: Create template (not authenticated)
    When I send a POST request to "/api/v1/en_GB/templates"
    Then the response status code should be 401

  Scenario: Update template (not authenticated)
    When I send a PUT request to "/api/v1/en_GB/templates/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Delete template (not authenticated)
    When I send a DELETE request to "/api/v1/en_GB/templates/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get template (not authenticated)
    When I send a GET request to "/api/v1/en_GB/templates/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get templates (not authenticated)
    When I send a GET request to "/api/v1/en_GB/templates"
    Then the response status code should be 401

  Scenario: Get template groups (not authenticated)
    When I send a GET request to "/api/v1/en_GB/templates/groups"
    Then the response status code should be 401

  Scenario: Get template types (not authenticated)
    When I send a GET request to "/api/v1/en_GB/templates/types"
    Then the response status code should be 401
