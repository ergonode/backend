Feature: Avatar privilege

  Scenario: Upload new avatar file without auth token
    Given I send a POST request to "/api/v1/avatar/upload"
    Then the response status code should be 401

  Scenario: Download uploaded avatar file without auth token
    Given I send a GET request to "api/v1/avatar/01730e8d-fb8d-5afe-9be1-b621bacbb6dd"
    Then the response status code should be 401
