Feature: Condition Product sku exists

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get product sku exists condition
    When I send a GET request to "/api/v1/en_GB/conditions/PRODUCT_SKU_EXISTS_CONDITION"
    Then the response status code should be 200

  Scenario: Post new IS_EQUAL product sku exists condition set
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
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
