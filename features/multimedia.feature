Feature: Multimedia
  In order to mange Multimedia
  I need abe able to create and retrieve through the API.
  Scenario: Upload new multimedia file
    Given current authentication token
    And I attach "features/multimedia-test-image.png" to the request as upload
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then created response is received
    And the response body contains JSON:
    """
      {"id": "01730e8d-fb8d-5afe-9be1-b621bacbb6dd"}
    """
  Scenario: Download uploaded multimedia file
    Given current authentication token
    And I request "api/v1/multimedia/01730e8d-fb8d-5afe-9be1-b621bacbb6dd" using HTTP GET
    Then the response code is 200
    And the "Content-Length" response header is 937
  Scenario: Upload new multimedia file without auth token
    Given I request "/api/v1/multimedia/upload" using HTTP POST
    Then unauthorized response is received
  Scenario: Upload new multimedia file without uploaded file
    Given current authentication token
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then validation error response is received
  Scenario: Upload new multimedia with empty file
    Given current authentication token
    And I attach "features/multimedia-test-empty-image.png" to the request as upload
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then validation error response is received
