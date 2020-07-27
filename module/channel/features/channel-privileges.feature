Feature: channel module
## todo To be checked with the export profile


  Scenario: Create channel (not authorized)
    When I send a POST request to "/api/v1/en/channels"
    Then the response status code should be 401

  Scenario: Get channels (not authorized)
    When I send a GET request to "/api/v1/en/channels"
    Then the response status code should be 401
