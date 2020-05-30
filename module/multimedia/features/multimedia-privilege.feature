Feature: Multimedia privilege

  Scenario: Upload new multimedia file without auth token
    Given I send a POST request to "/api/v1/multimedia/upload"
    Then the response status code should be 401

  Scenario: Download uploaded multimedia file without auth token
    Given I send a GET request to "api/v1/multimedia/01730e8d-fb8d-5afe-9be1-b621bacbb6dd"
    Then the response status code should be 401