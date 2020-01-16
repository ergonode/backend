Feature: Condition module

  Scenario: Get conditions dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/conditions" using HTTP GET
    Then unauthorized response is received

  Scenario: Get conditions dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/conditions" using HTTP GET
    Then the response code is 200

  Scenario: Get condition (not found)
    Given current authentication token
    When I request "/api/v1/EN/conditions/asd" using HTTP GET
    Then not found response is received

  Scenario: Get numeric condition (not authorized)
    When I request "/api/v1/EN/conditions/NUMERIC_ATTRIBUTE_VALUE_CONDITION" using HTTP GET
    Then unauthorized response is received

  Scenario: Get numeric condition
    Given current authentication token
    When I request "/api/v1/EN/conditions/NUMERIC_ATTRIBUTE_VALUE_CONDITION" using HTTP GET
    Then the response code is 200

  Scenario: Get option condition (not authorized)
    When I request "/api/v1/EN/conditions/OPTION_ATTRIBUTE_VALUE_CONDITION" using HTTP GET
    Then unauthorized response is received

  Scenario: Get option condition
    Given current authentication token
    When I request "/api/v1/EN/conditions/OPTION_ATTRIBUTE_VALUE_CONDITION" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute exists condition (not authorized)
    When I request "/api/v1/EN/conditions/ATTRIBUTE_EXISTS_CONDITION" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute exists condition
    Given current authentication token
    When I request "/api/v1/EN/conditions/ATTRIBUTE_EXISTS_CONDITION" using HTTP GET
    Then the response code is 200

  Scenario: Get text condition (not authorized)
    When I request "/api/v1/EN/conditions/TEXT_ATTRIBUTE_VALUE_CONDITION" using HTTP GET
    Then unauthorized response is received

  Scenario: Get text condition
    Given current authentication token
    When I request "/api/v1/EN/conditions/TEXT_ATTRIBUTE_VALUE_CONDITION" using HTTP GET
    Then the response code is 200

  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "CONDITION_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": [],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "condition_text_attribute"

  Scenario: Create condition set (not authorized)
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then unauthorized response is received

  Scenario: Create condition set without conditions
    Given current authentication token
    Given the request body is:
      """
      {
        "conditions": []
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    And remember response param "id" as "conditionset"

  Scenario: Create condition set with conditions
    Given current authentication token
    Given the request body is:
      """
      {
        "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "attribute": "@condition_text_attribute@"
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    And remember response param "id" as "conditionset"

  Scenario: Update condition set
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "attribute": "@condition_text_attribute@"
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then empty response is received

  Scenario: Update condition set (attribute not exists)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "attribute": "@@static_uuid@@"
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (attribute not uuid)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "attribute": "abc"
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (numeric attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "NUMERIC_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "operator": "=",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then empty response is received

  Scenario: Update condition set (numeric attribute with not uuid attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "NUMERIC_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "abc",
              "operator": "=",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (numeric attribute without value)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "NUMERIC_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "operator": "="
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (numeric attribute without operator)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "NUMERIC_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (numeric attribute invalid operator)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "NUMERIC_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "operator": "123",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (numeric attribute without attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "NUMERIC_ATTRIBUTE_VALUE_CONDITION",
              "operator": "=",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (numeric attribute with not existing attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "NUMERIC_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@@static_uuid@@",
              "operator": "=",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (option attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "OPTION_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then empty response is received

  Scenario: Update condition set (option attribute with not uuid attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "OPTION_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "abc",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (option attribute without value)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "OPTION_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@"
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (option attribute without attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "NUMERIC_ATTRIBUTE_VALUE_CONDITION",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (option attribute with not existing attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "NUMERIC_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@@static_uuid@@",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (text attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "operator": "=",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then empty response is received

  Scenario: Update condition set (text attribute with not uuid attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "abc",
              "operator": "=",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (text attribute without value)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "operator": "="
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (text attribute without operator)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (text attribute invalid operator)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "operator": "123",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (text attribute without attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "operator": "=",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (text attribute with not existing attribute)
    Given current authentication token
    Given the request body is:
      """
      {
         "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@@static_uuid@@",
              "operator": "=",
              "value": 123
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Get condition set (not authorized)
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get condition set (not found)
    Given current authentication token
    Given I request "/api/v1/EN/conditionsets/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get condition set
    Given current authentication token
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP GET
    Then the response code is 200

  Scenario: Delete condition set (not authorized)
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete condition set (not found)
    Given current authentication token
    Given I request "/api/v1/EN/conditionsets/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete condition set
    Given current authentication token
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP DELETE
    Then empty response is received

  Scenario: Create condition set (for conflict delete)
    Given current authentication token
    Given the request body is:
      """
      {
        "conditions": []
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    And remember response param "id" as "conditionset_delete"

  Scenario: Create segment (for relation to condition set conflict delete)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SEG_REL_@@random_code@@",
        "condition_set_id": "@conditionset_delete@"
      }
      """
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received

  Scenario: Delete condition set (with conflict)
    Given current authentication token
    Given I request "/api/v1/EN/conditionsets/@conditionset_delete@" using HTTP DELETE
    Then conflict response is received
