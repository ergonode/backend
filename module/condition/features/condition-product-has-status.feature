Feature: Condition Product has status

  Scenario: Create status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE @@random_md5@@",
        "name": {
          "pl_PL": "pl_PL",
          "en_GB": "en_GB"
        },
        "description": {
          "pl_PL": "pl_PL",
          "en_GB": "en_GB"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "status_1"

  Scenario: Create status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE @@random_md5@@",
        "name": {
          "pl_PL": "pl_PL",
          "en_GB": "en_GB"
        },
        "description": {
          "pl_PL": "pl_PL",
          "en_GB": "en_GB"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "status_2"

  Scenario: Get product has status condition
    When I send a GET request to "/api/v1/en_GB/conditions/PRODUCT_HAS_STATUS_CONDITION"
    Then the response status code should be 401

  Scenario: Get Product has status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/conditions/PRODUCT_HAS_STATUS_CONDITION"
    Then the response status code should be 200

  Scenario Outline: Post new valid product has status condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
       {
          "conditions": [
            {
              "type": "PRODUCT_HAS_STATUS_CONDITION",
              "operator": <operator>,
              "value": <value>,
              "language": <language>
            }
          ]
        }
      """
    Then the response status code should be 201
    Examples:
      | operator   | value | language |
      | "NOT_HAS" | ["@status_1@", "@status_2@" ]   | ["en_GB", "pl_PL"] |
      | "HAS" | ["@status_1@", "@status_2@" ]   |  ["en_GB"] |


  Scenario Outline: Post new invalid product has status condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_HAS_STATUS_CONDITION",
              "operator":  <operator>,
              "value": <value>,
              "language": <language>
            }
          ]
        }
      """
    Then the response status code should be 400
    Examples:
      | operator   | value | language |
      | "HAS"      |  ""   | ["en_GB", "pl_PL"] |
      | "HAS"      | null  |["en_GB"] |
      | null       | 1     | ["en_GB", "pl_PL"] |
      | "INVALID"  | 2     |["en_GB"] |
      | ""         | 1     | ["en_GB", "pl_PL"]|


  Scenario Outline: Post new invalid product has status condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_HAS_STATUS_CONDITION",
              <operator>
              <value>
            }
          ]
        }
      """
    Then the response status code should be 400
    Examples:
      | operator           |  value        |
      |                    |   "value" : 1 |
      | "operator" : "HAS" |               |

