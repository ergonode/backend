Feature: Condition Product sku exists
  Scenario: Get product sku exists condition
    When I request "/api/v1/EN/conditions/PRODUCT_SKU_EXISTS_CONDITION" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product sku exists condition
    Given current authentication token
    When I request "/api/v1/EN/conditions/PRODUCT_SKU_EXISTS_CONDITION" using HTTP GET
    Then the response code is 200

  Scenario: Post new IS_EQUAL product sku exists condition set
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
  Scenario: Post new IS_NOT_EQUAL product sku exists condition set
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
  Scenario: Post new HAS product sku exists condition set
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
  Scenario: Post new HAS product sku exists condition set
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
  Scenario: Post new  product sku exists condition set with invalid operator
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
  Scenario: Post new  product sku exists condition set without operator
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
  Scenario: Post new  product sku exists condition set without value
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
  Scenario: Post new  product sku exists condition set with empty operator
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
  Scenario: Post new  product sku exists condition set with empty value
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
  Scenario: Post new  product sku exists condition set with null operator
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
  Scenario: Post new  product sku exists condition set with null value
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received
  Scenario: Post new  product sku exists condition set with Wildcard value
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
  Scenario: Post new  product sku exists condition set with valid regexp value
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
  Scenario: Post new  product sku exists condition set with invalid regexp value
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received