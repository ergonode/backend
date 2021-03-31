Feature: Condition module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get conditions dictionary
    When I send a GET request to "/api/v1/en_GB/dictionary/conditions"
    Then the response status code should be 200

  Scenario: Get condition (not found)
    When I send a GET request to "/api/v1/en_GB/conditions/asd"
    Then the response status code should be 404

  Scenario: Get option condition
    When I send a GET request to "/api/v1/en_GB/conditions/OPTION_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 200

  Scenario: Create text attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "CONDITION_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "label": {"pl_PL": "Atrybut tekstowy", "en_GB": "Text attribute"},
          "groups": [],
          "parameters": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "condition_text_attribute"

  Scenario: Create condition set without conditions
    Given I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
      {
        "conditions": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "conditionset"

  Scenario: Update condition set (option attribute)
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@conditionset@" with body:
      """
      {
         "conditions": [
            {
              "type": "OPTION_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@condition_text_attribute@",
              "value": "123"
            }
         ]
      }
      """
    Then the response status code should be 204

  Scenario: Update condition set (option attribute with not uuid attribute)
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@conditionset@" with body:
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
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@conditionset@" with body:
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

  Scenario: Get condition set (not found)
    Given I send a GET request to "/api/v1/en_GB/conditionsets/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get condition set
    Given I send a GET request to "/api/v1/en_GB/conditionsets/@conditionset@"
    Then the response status code should be 200

  Scenario: Delete condition set (not found)
    Given I send a DELETE request to "/api/v1/en_GB/conditionsets/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete condition set
    Given I send a DELETE request to "/api/v1/en_GB/conditionsets/@conditionset@"
    Then the response status code should be 204

  Scenario: Create condition set (for conflict delete)
    Given I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
      {
        "conditions": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "conditionset_delete"

  Scenario: Create segment (for relation to condition set conflict delete)
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "SEG_REL_@@random_code@@",
        "condition_set_id": "@conditionset_delete@"
      }
      """
    Then the response status code should be 201

  Scenario: Delete condition set (with conflict)
    Given I send a DELETE request to "/api/v1/en_GB/conditionsets/@conditionset_delete@"
    Then the response status code should be 409

