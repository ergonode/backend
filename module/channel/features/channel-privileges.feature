Feature: channel module

  Scenario: Create channel (not authorized)
    When I send a POST request to "/api/v1/en_GB/channels"
    Then the response status code should be 401

  Scenario: Get channels (not authorized)
    When I send a GET request to "/api/v1/en_GB/channels"
    Then the response status code should be 401
