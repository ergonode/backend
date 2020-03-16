Feature: Condition module

  Scenario: Get conditions dictionary (not authorized)
    When I send a GET request to "/api/v1/EN/dictionary/conditions"
    Then the response status code should be 401

  Scenario: Get conditions dictionary
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/dictionary/conditions"
    Then the response status code should be 200

  Scenario: Get condition (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/conditions/asd"
    Then the response status code should be 404

  Scenario: Get numeric condition (not authorized)
    When I send a GET request to "/api/v1/EN/conditions/NUMERIC_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 401

  Scenario: Get numeric condition
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/conditions/NUMERIC_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 200

  Scenario: Get option condition (not authorized)
    When I send a GET request to "/api/v1/EN/conditions/OPTION_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 401

  Scenario: Get option condition
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/conditions/OPTION_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 200

  Scenario: Get attribute exists condition (not authorized)
    When I send a GET request to "/api/v1/EN/conditions/ATTRIBUTE_EXISTS_CONDITION"
    Then the response status code should be 401

  Scenario: Get attribute exists condition
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/conditions/ATTRIBUTE_EXISTS_CONDITION"
    Then the response status code should be 200

  Scenario: Get text condition (not authorized)
    When I send a GET request to "/api/v1/EN/conditions/TEXT_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 401

  Scenario: Get text condition
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/conditions/TEXT_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 200

  Scenario: Create text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
      """
      {
          "code": "CONDITION_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": [],
          "parameters": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "condition_text_attribute"

  Scenario: Create condition set (not authorized)
    Given I send a POST request to "/api/v1/EN/conditionsets"
    Then the response status code should be 401

  Scenario: Create condition set without conditions
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
      {
        "conditions": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "conditionset"

  Scenario: Create condition set with conditions
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a POST request to "/api/v1/EN/conditionsets" with body:
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
    Then the response status code should be 201
    And store response param "id" as "conditionset"

  Scenario: Update condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 204

  Scenario: Update condition set (attribute not exists)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (attribute not uuid)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (numeric attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 204

  Scenario: Update condition set (numeric attribute with not uuid attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (numeric attribute without value)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (numeric attribute without operator)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (numeric attribute invalid operator)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (numeric attribute without attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (numeric attribute with not existing attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (option attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 204

  Scenario: Update condition set (option attribute with not uuid attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (option attribute without value)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (option attribute without attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (option attribute with not existing attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (text attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 204

  Scenario: Update condition set (text attribute with not uuid attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (text attribute without value)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (text attribute without operator)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (text attribute invalid operator)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (text attribute without attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Update condition set (text attribute with not existing attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/EN/conditionsets/@conditionset@" with body:
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
    Then the response status code should be 400

  Scenario: Get condition set (not authorized)
    Given I send a GET request to "/api/v1/EN/conditionsets/@conditionset@"
    Then the response status code should be 401

  Scenario: Get condition set (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a GET request to "/api/v1/EN/conditionsets/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a GET request to "/api/v1/EN/conditionsets/@conditionset@"
    Then the response status code should be 200

  Scenario: Delete condition set (not authorized)
    Given I send a DELETE request to "/api/v1/EN/conditionsets/@conditionset@"
    Then the response status code should be 401

  Scenario: Delete condition set (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a DELETE request to "/api/v1/EN/conditionsets/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a DELETE request to "/api/v1/EN/conditionsets/@conditionset@"
    Then the response status code should be 204

  Scenario: Create condition set (for conflict delete)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
      {
        "conditions": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "conditionset_delete"

  Scenario: Create segment (for relation to condition set conflict delete)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/segments" with body:
      """
      {
        "code": "SEG_REL_@@random_code@@",
        "condition_set_id": "@conditionset_delete@"
      }
      """
    Then the response status code should be 201

  Scenario: Delete condition set (with conflict)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a DELETE request to "/api/v1/EN/conditionsets/@conditionset_delete@"
    Then the response status code should be 409
