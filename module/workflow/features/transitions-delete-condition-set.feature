Feature: Segment delete condition set

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get local text attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=text_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "attribute_text"

  Scenario: Create source status
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "workflow_source_status"

  Scenario: Create destination status
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "DESTINATION@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "workflow_destination_status"

  Scenario: Get source status
    When I send a GET request to "/api/v1/en_GB/status/@workflow_source_status@"
    Then the response status code should be 200
    And store response param "id" as "workflow_source_status_id"

  Scenario: Get destination status
    When I send a GET request to "/api/v1/en_GB/status/@workflow_destination_status@"
    Then the response status code should be 200
    And store response param "id" as "workflow_destination_status_id"

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
    And store response param "id" as "workflow_conditionset"

  Scenario: Add transition to workflow
    When I send a POST request to "/api/v1/en_GB/workflow/default/transitions" with body:
      """
      {
        "source": "@workflow_source_status_id@",
        "destination": "@workflow_destination_status_id@",
        "roles": [],
        "condition_set" : "@workflow_conditionset@"
      }
      """
    Then the response status code should be 201

  Scenario: Delete transition in default workflow
    When I send a DELETE request to "/api/v1/en_GB/workflow/default/transitions/@workflow_source_status_id@/@workflow_destination_status_id@"
    Then the response status code should be 204
