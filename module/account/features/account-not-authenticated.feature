Feature: Account module

  Scenario: Get role autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/roles/autocomplete"
    Then the response status code should be 401

  Scenario: Get profile (not authenticated)
    When I send a GET request to "/api/v1/profile"
    Then the response status code should be 401

  Scenario: Create role (not authenticated)
    Given I send a POST request to "/api/v1/en_GB/roles"
    Then the response status code should be 401

  Scenario: Delete role for delete (not authenticated)
    When I send a DELETE request to "/api/v1/en_GB/roles/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Update role (not authenticated)
    When I send a PUT request to "/api/v1/en_GB/roles/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get role (not authenticated)
    When I send a GET request to "/api/v1/en_GB/roles/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get roles (not authenticated)
    When I send a GET request to "/api/v1/en_GB/roles"
    Then the response status code should be 401

  Scenario: Create user (not authenticated)
    Given I send a POST request to "/api/v1/en_GB/accounts"
    Then the response status code should be 401

  Scenario: Update user (not authenticated)
    When I send a PUT request to "/api/v1/en_GB/accounts/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get user (not authenticated)
    When I send a GET request to "/api/v1/en_GB/accounts/@@random_uuid@@"
    Then the response status code should be 401

  @changePassword
  Scenario: Change password (not authenticated)
    When I send a PUT request to "/api/v1/en_GB/accounts/@@random_uuid@@/password"
    Then the response status code should be 401

  Scenario: Get privilege dictionary (not authenticated)
    When I send a GET request to "/api/v1/en_GB/dictionary/privileges"
    Then the response status code should be 401

  Scenario: Get accounts (not authenticated)
    When I send a GET request to "/api/v1/en_GB/accounts"
    Then the response status code should be 401

  Scenario: Get accounts log (not authenticated)
    When I send a GET request to "/api/v1/en_GB/accounts/log"
    Then the response status code should be 401
