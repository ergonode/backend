Feature: Condition module

  Scenario: Get conditions dictionary (not authorized)
    When I send a GET request to "/api/v1/en/dictionary/conditions"
    Then the response status code should be 401

  Scenario: Get conditions dictionary
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/dictionary/conditions"
    Then the response status code should be 200

  Scenario: Get condition (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/conditions/asd"
    Then the response status code should be 404

  Scenario: Get numeric condition (not authorized)
    When I send a GET request to "/api/v1/en/conditions/NUMERIC_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 401

  Scenario: Get option condition (not authorized)
    When I send a GET request to "/api/v1/en/conditions/OPTION_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 401

  Scenario: Get option condition
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/conditions/OPTION_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 200

  Scenario: Get attribute exists condition (not authorized)
    When I send a GET request to "/api/v1/en/conditions/ATTRIBUTE_EXISTS_CONDITION"
    Then the response status code should be 401

  Scenario: Get text condition (not authorized)
    When I send a GET request to "/api/v1/en/conditions/TEXT_ATTRIBUTE_VALUE_CONDITION"
    Then the response status code should be 401

  Scenario: Create text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "CONDITION_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "label": {"pl_PL": "Atrybut tekstowy", "en": "Text attribute"},
          "groups": [],
          "parameters": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "condition_text_attribute"

  Scenario: Create condition set (not authorized)
    Given I send a POST request to "/api/v1/en/conditionsets"
    Then the response status code should be 401

  Scenario: Create condition set without conditions
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a POST request to "/api/v1/en/conditionsets" with body:
      """
      {
        "conditions": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "conditionset"

  Scenario: Update condition set (option attribute)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a PUT request to "/api/v1/en/conditionsets/@conditionset@" with body:
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
    Given I send a PUT request to "/api/v1/en/conditionsets/@conditionset@" with body:
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
    Given I send a PUT request to "/api/v1/en/conditionsets/@conditionset@" with body:
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

  Scenario: Get condition set (not authorized)
    Given I send a GET request to "/api/v1/en/conditionsets/@conditionset@"
    Then the response status code should be 401

  Scenario: Get condition set (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a GET request to "/api/v1/en/conditionsets/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a GET request to "/api/v1/en/conditionsets/@conditionset@"
    Then the response status code should be 200

  Scenario: Delete condition set (not authorized)
    Given I send a DELETE request to "/api/v1/en/conditionsets/@conditionset@"
    Then the response status code should be 401

  Scenario: Delete condition set (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a DELETE request to "/api/v1/en/conditionsets/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a DELETE request to "/api/v1/en/conditionsets/@conditionset@"
    Then the response status code should be 204

  Scenario: Create condition set (for conflict delete)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a POST request to "/api/v1/en/conditionsets" with body:
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
    When I send a POST request to "/api/v1/en/segments" with body:
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
    Given I send a DELETE request to "/api/v1/en/conditionsets/@conditionset_delete@"
    Then the response status code should be 409
