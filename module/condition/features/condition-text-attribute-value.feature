Feature: Condition Product sku exists

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create text attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "CONDITION_TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "second_attribute_id"

  Scenario: Create second text attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "CONDITION_TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create numeric attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "CONDITION_NUMERIC_@@random_code@@",
          "type": "NUMERIC",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "numeric_attribute_id"

  Scenario: Get product sku exists condition configuration
    When I send a GET request to "/api/v1/en_GB/conditions/TEXT_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type               | TEXT_ATTRIBUTE_VALUE_CONDITION |
      | parameters[0].name | attribute                      |
      | parameters[0].type | SELECT                         |
      | parameters[1].name | operator                       |
      | parameters[1].type | SELECT                         |

  Scenario: create TEXT_ATTRIBUTE_VALUE_CONDITION condition set
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "operator": "=",
              "attribute": "@attribute_id@",
              "value": "100"
            }
          ]
        }
      """
    Then the response status code should be 201
    And store response param "id" as "condition_set_id"

  Scenario: create condition set with incorrect attribute uuid
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "operator": "=",
              "attribute": "abc",
              "value": "100"
            }
          ]
        }
      """
    Then the response status code should be 400

  Scenario: create condition set without attribute
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "operator": "=",
              "value": "100"
            }
          ]
        }
      """
    Then the response status code should be 400

  Scenario: create condition set without operator
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@attribute_id@",
              "value": "100"
            }
          ]
        }
      """
    Then the response status code should be 400

  Scenario: create condition set without value
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@attribute_id@",
              "operator": "="
            }
          ]
        }
      """
    Then the response status code should be 400

  Scenario: create condition set with incorrect attribute type
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "operator": "=",
              "attribute": "@numeric_attribute_id@",
              "value": "100"
            }
          ]
        }
      """
    Then the response status code should be 400

  Scenario: Get created condition set (numeric attribute)
    Given I send a GET request to "/api/v1/en_GB/conditionsets/@condition_set_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                      | @condition_set_id@             |
      | conditions[0].type      | TEXT_ATTRIBUTE_VALUE_CONDITION |
      | conditions[0].attribute | @attribute_id@                 |
      | conditions[0].operator  | =                              |
      | conditions[0].value     | 100                            |

  Scenario: Update condition set
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
      """
      {
         "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@attribute_id@",
              "operator": "=",
              "value": "200"
            }
         ]
      }
      """
    Then the response status code should be 204

  Scenario: Get created condition set (numeric attribute)
    Given I send a GET request to "/api/v1/en_GB/conditionsets/@condition_set_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                      | @condition_set_id@             |
      | conditions[0].type      | TEXT_ATTRIBUTE_VALUE_CONDITION |
      | conditions[0].attribute | @attribute_id@                 |
      | conditions[0].operator  | =                              |
      | conditions[0].value     | 200                            |

  Scenario: Update condition set (numeric attribute with not uuid attribute)
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
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

  Scenario: Update condition set with not numeric attribute
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
      """
      {
         "conditions": [
            {
              "type": "TEXT_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@numeric_attribute_id@",
              "operator": "=",
              "value": 123
            }
         ]
      }
      """
    Then the response status code should be 400

  Scenario: Update condition set (numeric attribute without value)
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
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

  Scenario: Update condition set (numeric attribute without operator)
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
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

  Scenario: Update condition set (numeric attribute invalid operator)
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
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

  Scenario: Update condition set (numeric attribute without attribute)
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
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

  Scenario: Update condition set (numeric attribute with not existing attribute)
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
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

  Scenario: Delete numeric attribute binded to condition_set
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 409

  Scenario: Delete TEXT_ATTRIBUTE_VALUE_CONDITION condition set
    When I send a DELETE request to "/api/v1/en_GB/conditionsets/@condition_set_id@"
    Then the response status code should be 204

  Scenario: Delete numeric attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204

  Scenario: Delete numeric attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@second_attribute_id@"
    Then the response status code should be 204
