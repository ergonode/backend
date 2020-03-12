Feature: Multimedia
  In order to mange Multimedia
  I need to be able to create and retrieve through the API.

  Scenario: Upload new multimedia file
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.png |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "multimedia_id"

  Scenario: Upload same multimedia file again
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.png |
    Then the response status code should be 201
    And the JSON node "id" should exist

  Scenario: Upload new multimedia file without auth token
    Given I request "/api/v1/multimedia/upload" using HTTP POST
    Then unauthorized response is received

  Scenario: Upload new multimedia file without uploaded file
    Given current authentication token
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then validation error response is received

  Scenario: Upload new multimedia with empty file
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-empty-image.png |
    Then the response status code should be 400

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