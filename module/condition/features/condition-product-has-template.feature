Feature: Condition Product has template
  Scenario: Get product has template condition
    When I request "/api/v1/EN/conditions/PRODUCT_HAS_TEMPLATE_CONDITION" using HTTP GET
    Then unauthorized response is received

  Scenario: Get Product has template
    Given current authentication token
    When I request "/api/v1/EN/conditions/PRODUCT_HAS_TEMPLATE_CONDITION" using HTTP GET
    Then the response code is 200

  Scenario Outline: Post new valid product has template condition set
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_HAS_TEMPLATE_CONDITION",
              "operator": <operator>,
              "value": <value>
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    Examples:
      | operator | value  |
      | "HAS"     | 1     |
      | "NOT_HAS" | 2     |
      | "NOT_HAS" | "2"   |
      | "NOT_HAS" | "2"   |


  Scenario Outline: Post new invalid product has template condition set
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_HAS_TEMPLATE_CONDITION",
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


  Scenario Outline: Post new invalid product has template condition set
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_HAS_TEMPLATE_CONDITION",
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

