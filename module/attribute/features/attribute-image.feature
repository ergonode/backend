Feature: Image attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create image attribute
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
          "code": "IMAGE_@@random_code@@",
          "type": "IMAGE",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Update image attribute
    And I send a "PUT" request to "/api/v1/EN/attributes/@attribute_id@" with body:
      """
      {
          "groups": []
      }
      """
    Then the response status code should be 204

  Scenario: Delete image attribute
    And I send a "DELETE" request to "/api/v1/EN/attributes/@attribute_id@"
    Then the response status code should be 204

