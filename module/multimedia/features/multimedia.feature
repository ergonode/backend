Feature: Multimedia
  In order to mange Multimedia
  I need to be able to create and retrieve through the API.

  Scenario: Upload new multimedia file
    Given current authentication token
    And I attach "module/multimedia/features/multimedia-test-image.png" to the request as upload
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then created response is received
    And remember response param "id" as "multimedia_id"

  Scenario: Upload same multimedia file again
    Given current authentication token
    And I attach "module/multimedia/features/multimedia-test-image.png" to the request as upload
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then created response is received

  Scenario: Upload new multimedia file without auth token
    Given I request "/api/v1/multimedia/upload" using HTTP POST
    Then unauthorized response is received

  Scenario: Upload new multimedia file without uploaded file
    Given current authentication token
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then validation error response is received

  Scenario: Upload new multimedia with empty file
    Given current authentication token
    And I attach "module/multimedia/features/multimedia-test-empty-image.png" to the request as upload
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then validation error response is received

  Scenario: Download uploaded multimedia file without auth token
    Given I request "api/v1/multimedia/01730e8d-fb8d-5afe-9be1-b621bacbb6dd" using HTTP GET
    Then unauthorized response is received

  Scenario: Download uploaded multimedia file with invalid uuid
    Given current authentication token
    And I request "api/v1/multimedia/aaa-aa-aaa" using HTTP GET
    Then the response code is 400

  Scenario: Download uploaded multimedia file with with not existing uuid
    Given current authentication token
    And I request "api/v1/multimedia/01730e8d-fb8d-5afe-aaaa-b621bacbbaaa" using HTTP GET
    Then not found response is received