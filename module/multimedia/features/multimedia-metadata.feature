Feature: Multimedia
  In order to mange Multimedia
  I need to be able to create and retrieve through the API.

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"

  Scenario: Upload new multimedia file
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.png |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "multimedia_id"

  Scenario: Get uploaded multimedia metadata
    And I send a GET request to "api/v1/en_GB/multimedia/@multimedia_id@/metadata"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | width      | 16            |
      | height     | 16            |
      | alpha      | true          |
      | resolution | 18.9 pixel/cm |
