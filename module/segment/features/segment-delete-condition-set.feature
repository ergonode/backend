Feature: Segment delete condition set

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get local text attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=text_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "attribute_text"

  Scenario: Create condition set
    Given I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
      {
        "conditions": [
          {
            "type": "ATTRIBUTE_EXISTS_CONDITION",
            "attribute": "@attribute_text@"
          }
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment_conditionset"

  Scenario: Create segment
    Given remember param "segment_code" with value "SEG_1_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "@segment_code@",
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "pl_PL": "Segment",
          "en_GB": "Segment"
        },
        "description": {
          "pl_PL": "Opis segmentu",
          "en_GB": "Segment description"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment"

  Scenario: Delete segment
    When I send a DELETE request to "/api/v1/en_GB/segments/@segment@"
    Then the response status code should be 204
