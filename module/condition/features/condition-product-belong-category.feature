Feature: Condition Product belong category exists
  Scenario: Get product belong category exists condition
    When I send a GET request to "/api/v1/EN/conditions/PRODUCT_BELONG_CATEGORY_CONDITION"
    Then the response status code should be 401

  Scenario: Get product belong category exists condition
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/conditions/PRODUCT_BELONG_CATEGORY_CONDITION"
    Then the response status code should be 200

  Scenario: Create category
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/categories" with body:
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
    Then the response status code should be 201
    And store response param "id" as "category"

  Scenario: Post new BELONG_TO product category exists condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
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
    Then the response status code should be 201

  Scenario: Post new NOT_BELONG_TO product category exists condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
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
    Then the response status code should be 201

  Scenario: Post new fail condition operator
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
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
    Then the response status code should be 400

  Scenario: Post new fail condition category doesn't exist
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_CONDITION",
              "operator": "NOT_BELONG_TO",
              "category": "@@random_uuid@@"
            }
          ]
        }
      """
    Then the response status code should be 400
