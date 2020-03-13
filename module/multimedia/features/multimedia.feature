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
    Given I send a POST request to "/api/v1/multimedia/upload"
    Then the response status code should be 401

  Scenario: Upload new multimedia file without uploaded file
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/multimedia/upload"
    Then the response status code should be 400

  Scenario: Upload new multimedia with empty file
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-empty-image.png |
    Then the response status code should be 400

  Scenario: Download uploaded multimedia file without auth token
    Given I send a GET request to "api/v1/multimedia/01730e8d-fb8d-5afe-9be1-b621bacbb6dd"
    Then the response status code should be 401

  Scenario: Download uploaded multimedia file with invalid uuid
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a GET request to "api/v1/multimedia/aaa-aa-aaa"
    Then the response status code should be 400

  Scenario: Download uploaded multimedia file with with not existing uuid
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a GET request to "api/v1/multimedia/01730e8d-fb8d-5afe-aaaa-b621bacbbaaa"
    Then the response status code should be 404