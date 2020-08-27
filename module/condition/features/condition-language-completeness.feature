Feature: Condition Product sku exists

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get product sku exists condition configuration
    When I send a GET request to "/api/v1/en_GB/conditions/LANGUAGE_COMPLETENESS_CONDITION"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type               | LANGUAGE_COMPLETENESS_CONDITION |
      | parameters[0].name | completeness                    |
      | parameters[0].type | SELECT                          |
      | parameters[1].name | language                        |
      | parameters[1].type | SELECT                          |

  Scenario: create LANGUAGE_COMPLETENESS_CONDITION condition set
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "LANGUAGE_COMPLETENESS_CONDITION",
              "completeness": "complete",
              "language" : "en_GB"
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
              "type": "LANGUAGE_COMPLETENESS_CONDITION",
              "completeness": "incorrect value",
              "language": "en"
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
              "type": "LANGUAGE_COMPLETENESS_CONDITION"
            }
          ]
        }
      """
    Then the response status code should be 400

  Scenario: Get created condition set
    Given I send a GET request to "/api/v1/en_GB/conditionsets/@condition_set_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                         | @condition_set_id@              |
      | conditions[0].type         | LANGUAGE_COMPLETENESS_CONDITION |
      | conditions[0].completeness | complete    |
      | conditions[0].language     | en_GB                           |

  Scenario: Update condition set
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
      """
      {
         "conditions": [
            {
              "type": "LANGUAGE_COMPLETENESS_CONDITION",
              "completeness": "not complete",
              "language" : "en_GB"
            }
         ]
      }
      """
    Then the response status code should be 204

  Scenario: Get created condition set
    Given I send a GET request to "/api/v1/en_GB/conditionsets/@condition_set_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                         | @condition_set_id@              |
      | conditions[0].type         | LANGUAGE_COMPLETENESS_CONDITION |
      | conditions[0].completeness | not complete    |
      | conditions[0].language     | en_GB                           |

  Scenario: Update condition set with not uuid attribute
    Given I send a PUT request to "/api/v1/en_GB/conditionsets/@condition_set_id@" with body:
      """
      {
         "conditions": [
            {
              "type": "LANGUAGE_COMPLETENESS_CONDITION",
              "completeness": "incorrect value",
              "language": "en"
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
              "type": "LANGUAGE_COMPLETENESS_CONDITION"
            }
         ]
      }
      """
    Then the response status code should be 400

  Scenario: Delete ATTRIBUTE_EXISTS_CONDITION condition set
    When I send a DELETE request to "/api/v1/en_GB/conditionsets/@condition_set_id@"
    Then the response status code should be 204
