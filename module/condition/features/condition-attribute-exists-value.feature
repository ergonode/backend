Feature: Condition Product sku exists

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get global numeric attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=numeric_attribute_global&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "attribute_id"

  Scenario: Get product sku exists condition configuration
    When I send a GET request to "/api/v1/en_GB/conditions/ATTRIBUTE_EXISTS_CONDITION"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type               | ATTRIBUTE_EXISTS_CONDITION |
      | parameters[0].name | attribute                  |
      | parameters[0].type | SELECT                     |

  Scenario: create ATTRIBUTE_EXISTS_CONDITION condition set
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "attribute": "@attribute_id@"
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
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "attribute": "abc"
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
              "type": "ATTRIBUTE_EXISTS_CONDITION"
            }
          ]
        }
      """
    Then the response status code should be 400

  Scenario: Get created condition set
    Given I send a GET request to "/api/v1/en_GB/conditionsets/@condition_set_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                      | @condition_set_id@         |
      | conditions[0].type      | ATTRIBUTE_EXISTS_CONDITION |
      | conditions[0].attribute | @attribute_id@             |

  Scenario: Update condition set (numeric attribute)
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
      """
      {
         "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "attribute": "@attribute_id@"
            }
         ]
      }
      """
    Then the response status code should be 204

  Scenario: Get created condition set (numeric attribute)
    Given I send a GET request to "/api/v1/en_GB/conditionsets/@condition_set_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                      | @condition_set_id@         |
      | conditions[0].type      | ATTRIBUTE_EXISTS_CONDITION |
      | conditions[0].attribute | @attribute_id@             |

  Scenario: Update condition set with not uuid attribute
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
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

  Scenario: Update condition set without attribute
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
      """
      {
         "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION"
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
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "attribute": "@@static_uuid@@"
            }
         ]
      }
      """
    Then the response status code should be 400

  Scenario: Delete numeric attribute binded to condition_set
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 409

  Scenario: Delete ATTRIBUTE_EXISTS_CONDITION condition set
    When I send a DELETE request to "/api/v1/en_GB/conditionsets/@condition_set_id@"
    Then the response status code should be 204

  Scenario: Delete numeric attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
