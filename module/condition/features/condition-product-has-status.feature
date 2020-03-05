Feature: Condition Product has status

  Scenario: Create status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received
    And remember response param "id" as "status_1"

  Scenario: Create status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received
    And remember response param "id" as "status_2"

  Scenario: Get product has status condition
    When I request "/api/v1/EN/conditions/PRODUCT_HAS_STATUS_CONDITION" using HTTP GET
    Then unauthorized response is received

  Scenario: Get Product has status
    Given current authentication token
    When I request "/api/v1/EN/conditions/PRODUCT_HAS_STATUS_CONDITION" using HTTP GET
    Then the response code is 200

  Scenario Outline: Post new valid product has status condition set
    Given current authentication token
    Given the request body is:
      """
       {
          "conditions": [
            {
              "type": "PRODUCT_HAS_STATUS_CONDITION",
              "operator": <operator>,
              "value": <value>
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    Examples:
      | operator   | value |
      | "NOT_HAS" | ["@status_1@", "@status_2@" ]   |
      | "HAS" | ["@status_1@", "@status_2@" ]   |


  Scenario Outline: Post new invalid product has status condition set
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_HAS_STATUS_CONDITION",
              "operator":  <operator>,
              "value": <value>
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
    Examples:
      | operator   | value |
      | "HAS"      |  ""   |
      | "HAS"      | null  |
      | null       | 1     |
      | "INVALID"  | 2     |
      | ""         | 1     |


  Scenario Outline: Post new invalid product has status condition set
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
    Examples:
      | operator           |  value        |
      |                    |   "value" : 1 |
      | "operator" : "HAS" |               |

