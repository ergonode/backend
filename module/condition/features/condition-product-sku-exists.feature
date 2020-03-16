Feature: Condition Product sku exists
  Scenario: Get product sku exists condition
    When I send a GET request to "/api/v1/EN/conditions/PRODUCT_SKU_EXISTS_CONDITION"
    Then the response status code should be 401

  Scenario: Get product sku exists condition
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/conditions/PRODUCT_SKU_EXISTS_CONDITION"
    Then the response status code should be 200

  Scenario: Post new IS_EQUAL product sku exists condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "=",
              "value": "1"
            }
          ]
        }
      """
    Then the response status code should be 201
  Scenario: Post new IS_NOT_EQUAL product sku exists condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "<>",
              "value": "1"
            }
          ]
        }
      """
    Then the response status code should be 201
  Scenario: Post new HAS product sku exists condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "HAS",
              "value": "1"
            }
          ]
        }
      """
    Then the response status code should be 201
  Scenario: Post new HAS product sku exists condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "HAS",
              "value": "1"
            }
          ]
        }
      """
    Then the response status code should be 201
  Scenario: Post new  product sku exists condition set with invalid operator
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "invalid",
              "value": "1"
            }
          ]
        }
      """
    Then the response status code should be 400
  Scenario: Post new  product sku exists condition set without operator
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "value": "1"
            }
          ]
        }
      """
    Then the response status code should be 400
  Scenario: Post new  product sku exists condition set without value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "="
            }
          ]
        }
      """
    Then the response status code should be 400
  Scenario: Post new  product sku exists condition set with empty operator
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "",
              "value": "1"
            }
          ]
        }
      """
    Then the response status code should be 400
  Scenario: Post new  product sku exists condition set with empty value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "=",
              "value": ""
            }
          ]
        }
      """
    Then the response status code should be 400
  Scenario: Post new  product sku exists condition set with null operator
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": null,
              "value": "1"
            }
          ]
        }
      """
    Then the response status code should be 400
  Scenario: Post new  product sku exists condition set with null value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "=",
              "value": null
            }
          ]
        }
      """
    Then the response status code should be 400
  Scenario: Post new  product sku exists condition set with Wildcard value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "WILDCARD",
              "value": "abc"
            }
          ]
        }
      """
    Then the response status code should be 201
  Scenario: Post new  product sku exists condition set with valid regexp value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "REGEXP",
              "value": "~aaa[a-z]d~"
            }
          ]
        }
      """
    Then the response status code should be 201
  Scenario: Post new  product sku exists condition set with invalid regexp value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_SKU_EXISTS_CONDITION",
              "operator": "REGEXP",
              "value": "~a(aa[a-z]d~"
            }
          ]
        }
      """
    Then the response status code should be 400