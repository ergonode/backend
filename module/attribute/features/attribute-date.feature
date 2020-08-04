Feature: Date attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create date attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "DATE_@@random_code@@",
        "type": "DATE",
        "groups": [],
        "scope": "local",
        "parameters":
        {
          "format": "yyyy-MM-dd"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create date attribute without required format parameter
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "DATE_@@random_code@@",
        "type": "DATE",
        "groups": []
      }
      """
    Then the response status code should be 400

  Scenario: Get created date attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                | @attribute_id@ |
      | type              | DATE           |
      | scope             | local          |
      | parameters.format | yyyy-MM-dd     |

  Scenario: Create date attribute with invalid format parameter
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "DATE_@@random_code@@",
        "type": "DATE",
        "groups": [],
        "parameters":
        {
          "format": "bad - format"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update attribute
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@" with body:
      """
      {
        "groups": [],
        "scope": "local",
        "parameters": {"format": "yyyy-MM-dd"}
      }
      """
    Then the response status code should be 204

  Scenario: Delete date attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
