Feature: Workflow transitions

    Scenario: Update transition to workflow (not authenticated)
    When I send a PUT request to "/api/v1/en/workflow/default/transitions/@@random_uuid@@/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get transition in default workflow (not authenticated)
    When I send a GET request to "/api/v1/en/workflow/default/transitions/@@random_uuid@@/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get transitions (not authenticated)
    When I send a GET request to "/api/v1/en/workflow/default/transitions?field=source"
    Then the response status code should be 401

  Scenario: Delete transition in default workflow (not authenticated)
    When I send a DELETE request to "/api/v1/en/workflow/default/transitions/@@random_uuid@@/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Create transition to workflow (not authenticated)
    When I send a POST request to "/api/v1/en/workflow/default/transitions"
    Then the response status code should be 401
