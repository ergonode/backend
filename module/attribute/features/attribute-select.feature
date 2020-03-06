Feature: Select attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create select attribute
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
          "code": "SELECT@@random_code@@",
          "type": "SELECT",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create select attribute with option
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "SELECT_@@random_code@@",
        "type": "SELECT",
        "groups": [],
        "multilingual": true,
        "options": [
          {
            "key": "key_1",
            "value": {
              "PL": "Option PL 1",
              "EN": "Option EN 1"
            }
          }
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id_2"

  Scenario: Create select attribute with duplicated options
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "SELECT_@@random_code@@",
        "type": "SELECT",
        "groups": [],
        "multilingual": true,
        "options": [
          {
            "key": "key_1",
            "value": {
              "PL": "Option PL 1",
              "EN": "Option EN 1"
            }
          },
          {
            "key": "key_1",
            "value": {
              "PL": "Option PL 1",
              "EN": "Option EN 1"
            }
          }
        ]
      }
      """
    Then the response status code should be 400

  Scenario: Update select attribute with duplicated options
    And I send a "PUT" request to "/api/v1/EN/attributes/@attribute_id_2@" with body:
      """
      {
        "options":
        [
          {
            "key": "key_1",
            "value": {
              "PL": "Option PL 1",
              "EN": "Option EN 1"
            }
          },
          {
            "key": "key_1",
            "value": {
              "PL": "Option PL 1",
              "EN": "Option EN 1"
            }
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update select attribute
    And I send a "PUT" request to "/api/v1/EN/attributes/@attribute_id@" with body:
      """
      {
          "groups": []
      }
      """
    Then the response status code should be 204

  Scenario: Delete select attribute
    And I send a "DELETE" request to "/api/v1/EN/attributes/@attribute_id@"
    Then the response status code should be 204

  Scenario: Delete select attribute
    And I send a "DELETE" request to "/api/v1/EN/attributes/@attribute_id_2@"
    Then the response status code should be 204