Feature: Designer module upload image

  Scenario: Upload image
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/multimedia/upload" with params:
    | key    | value |
    | upload | @image/test.jpg |
    Then the response status code should be 201
    And store response param "id" as "multimedia_id"


