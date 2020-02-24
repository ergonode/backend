Feature: Condition Product belong category tree exists
  Scenario: Get product belong category tree exists condition
    When I request "/api/v1/EN/conditions/PRODUCT_BELONG_CATEGORY_TREE_CONDITION" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product belong category tree exists condition
    Given current authentication token
    When I request "/api/v1/EN/conditions/PRODUCT_BELONG_CATEGORY_TREE_CONDITION" using HTTP GET
    Then the response code is 200

  Scenario: Create category tree
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TREE_@@random_code@@",
        "name": {
          "DE": "Test DE",
          "EN": "Test EN",
          "PL": "Test PL"
        }
      }
      """
    When I request "/api/v1/EN/trees" using HTTP POST
    Then created response is received
    And remember response param "id" as "category_tree"

  Scenario: Post new BELONG_TO product category tree exists condition set
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_TREE_CONDITION",
              "operator": "BELONG_TO",
              "tree": "@category_tree@"
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received

  Scenario: Post new NOT_BELONG_TO product category tree exists condition set
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_TREE_CONDITION",
              "operator": "NOT_BELONG_TO",
              "tree": "@category_tree@"
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
              "type": "PRODUCT_BELONG_CATEGORY_TREE_CONDITION",
              "operator": "x",
              "tree": "@category_tree@"
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received

  Scenario: Post new fail condition category tree doesn't exist
    Given current authentication token
    Given the request body is:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_TREE_CONDITION",
              "operator": "NOT_BELONG_TO",
              "tree": "@@random_uuid@@"
            }
          ]
        }
      """
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
