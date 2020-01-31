Feature: Condition Product belong category exists
  Scenario: Get product belong category exists condition
    When I request "/api/v1/EN/conditions/PRODUCT_BELONG_CATEGORY_CONDITION" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product belong category exists condition
    Given current authentication token
    When I request "/api/v1/EN/conditions/PRODUCT_BELONG_CATEGORY_CONDITION" using HTTP GET
    Then the response code is 200

  Scenario: Create category
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {
          "DE": "Test DE",
          "EN": "Test EN",
          "PL": "Test PL"
        }
      }
      """
    When I request "/api/v1/EN/categories" using HTTP POST
    Then created response is received
    And remember response param "id" as "category"

  Scenario: Post new BELONG_TO product category exists condition set
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_CONDITION",
              "operator": "BELONG_TO",
              "category": "@category@"
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received

  Scenario: Post new NOT_BELONG_TO product category exists condition set
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_CONDITION",
              "operator": "NOT_BELONG_TO",
              "category": "@category@"
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received

  Scenario: Post new fail condition operator
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_CONDITION",
              "operator": "x",
              "category": "@category@"
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received

  Scenario: Post new fail condition category doesn't exist
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_CONDITION",
              "operator": "NOT_BELONG_TO",
              "category": "2dbbaaa1-3053-4152-b324-862b6ff843e1"
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
