Feature: Condition Product belong category exists

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"


  Scenario: Get product belong category exists condition
    When I send a GET request to "/api/v1/en_GB/conditions/PRODUCT_BELONG_CATEGORY_CONDITION"
    Then the response status code should be 200

  Scenario: Get 1 category id
    When I send a GET request to "/api/v1/en_GB/categories?filter=name=Category_1&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "category1"

  Scenario: Get 2 category id
    When I send a GET request to "/api/v1/en_GB/categories?filter=name=Category_2&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "category2"

  Scenario: Post new BELONG_TO product category exists condition set
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_CONDITION",
              "operator": "BELONG_TO",
              "category": [
                "@category1@",
                "@category2@"
              ]
            }
          ]
        }
      """
    Then the response status code should be 201

  Scenario: Post new NOT_BELONG_TO product category exists condition set
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_CONDITION",
              "operator": "NOT_BELONG_TO",
              "category": [
                "@category1@"
              ]
            }
          ]
        }
      """
    Then the response status code should be 201

  Scenario: Post new fail condition operator
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_CONDITION",
              "operator": "x",
              "category": [
                "@category1@",
                "@category2@"
              ]
            }
          ]
        }
      """
    Then the response status code should be 400

  Scenario: Post new fail condition category doesn't exist
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_BELONG_CATEGORY_CONDITION",
              "operator": "NOT_BELONG_TO",
              "category": [
                "@@random_uuid@@"
              ]
            }
          ]
        }
      """
    Then the response status code should be 400
