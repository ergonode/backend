Feature: Workflow

  Scenario: Create status (not authenticated)
    When I send a POST request to "/api/v1/en/status"
    Then the response status code should be 401

  Scenario: Update source status (not authenticated)
    When I send a PUT request to "/api/v1/en/status/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get statuses (not authenticated)
    When I send a GET request to "/api/v1/en/status"
    Then the response status code should be 401

  Scenario: Set default status (not authenticated)
    When I send a PUT request to "/api/v1/en/workflow/default/status/@@random_uuid@@/default"
    Then the response status code should be 401

  Scenario: Delete status (not authenticated)
    When I send a DELETE request to "/api/v1/en/status/@@random_uuid@@"
    Then the response status code should be 401
