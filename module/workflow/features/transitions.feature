Feature: Workflow transitions

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create role
    When I send a POST request to "/api/v1/en/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "role_id"

  Scenario: Create source status
    When I send a POST request to "/api/v1/en/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE @@random_md5@@",
        "name": {
          "pl_PL": "pl_PL",
          "en": "en"
        },
        "description": {
          "pl_PL": "pl_PL",
          "en": "en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "workflow_source_status"

  Scenario: Create destination status
    When I send a POST request to "/api/v1/en/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "DESTINATION @@random_md5@@",
        "name": {
          "pl_PL": "pl_PL",
          "en": "en"
        },
        "description": {
          "pl_PL": "pl_PL",
          "en": "en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "workflow_destination_status"

  Scenario: Get source status
    When I send a GET request to "/api/v1/en/status/@workflow_source_status@"
    Then the response status code should be 200
    And store response param "id" as "workflow_source_status_id"

  Scenario: Get destination status
    When I send a GET request to "/api/v1/en/status/@workflow_destination_status@"
    Then the response status code should be 200
    And store response param "id" as "workflow_destination_status_id"

  Scenario: Add transition to workflow
    When I send a POST request to "/api/v1/en/workflow/default/transitions" with body:
      """
      {
        "source": "@workflow_source_status_id@",
        "destination": "@workflow_destination_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en": "Translated description en"
        },
        "roles": [
           "@role_id@"
        ]
      }
      """
    Then the response status code should be 201

  Scenario: Get transition in default workflow
    When I send a GET request to "/api/v1/en/workflow/default/transitions/@workflow_source_status_id@/@workflow_destination_status_id@"
    Then the response status code should be 200
    And the JSON node "role_ids[0]" should not be null

  Scenario: Create transition to workflow (duplicated)
    Given I am Authenticated as "test@ergonode.com"
    When I send a POST request to "/api/v1/en/workflow/default/transitions"
      """
      {
        "source": "@workflow_source_status_id@",
        "destination": "@workflow_destination_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (without source)
    When I send a POST request to "/api/v1/en/workflow/default/transitions" with body:
      """
      {
        "destination": "@workflow_destination_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (without destination)
    When I send a POST request to "/api/v1/en/workflow/default/transitions" with body:
      """
      {
        "source": "@workflow_source_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (source not found)
    When I send a POST request to "/api/v1/en/workflow/default/transitions" with body:
      """
      {
        "source": "@@random_uuid@@",
        "destination": "@workflow_destination_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (destination not found)
    When I send a POST request to "/api/v1/en/workflow/default/transitions" with body:
      """
      {
        "source": "@workflow_source_status_id@",
        "destination": "@@random_uuid@@",
        "name": {
          "pl_PL": "Translated name PL",
          "en": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update transition to workflow
    When I send a PUT request to "/api/v1/en/workflow/default/transitions/@workflow_source_status_id@/@workflow_destination_status_id@" with body:
      """
      {
        "name": {
          "pl_PL": "Translated name PL",
          "en": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en": "Translated description en"
        },
        "roles": []
      }
      """
    Then the response status code should be 204

  Scenario: Get transition in default workflow
    When I send a GET request to "/api/v1/en/workflow/default/transitions/@workflow_source_status_id@/@workflow_destination_status_id@"
    Then the response status code should be 200
    And the JSON node "role_ids[0]" should not be null

  Scenario: Update transition to workflow (source not found)
    When I send a PUT request to "/api/v1/en/workflow/default/transitions/@@random_code@@/@workflow_destination_status_id@"
      """
      {
        "name": {
          "pl_PL": "Translated name PL",
          "en": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en": "Translated description en"
        }
      }
      """
    Then the response status code should be 404

  Scenario: Update transition to workflow (destination not found)
    When I send a PUT request to "/api/v1/en/workflow/default/transitions/@workflow_source_status_id@/@@random_code@@" with body:
      """
      {
        "name": {
          "pl_PL": "Translated name PL",
          "en": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en": "Translated description en"
        }
      }
      """
    Then the response status code should be 404

  Scenario: Get transition in default workflow
    When I send a GET request to "/api/v1/en/workflow/default/transitions/@workflow_source_status_id@/@workflow_destination_status_id@"
    Then the response status code should be 200

  Scenario: Get transition in default workflow (source not found)
    When I send a GET request to "/api/v1/en/workflow/default/transitions/@@random_uuid@@/@workflow_destination_status_id@"
    Then the response status code should be 404

  Scenario: Get transition in default workflow (destination not found)
    When I send a GET request to "/api/v1/en/workflow/default/transitions/@workflow_source_status_id@/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Get transitions (order by source)
    When I send a GET request to "/api/v1/en/workflow/default/transitions?field=source"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get transitions (order by destination)
    When I send a GET request to "/api/v1/en/workflow/default/transitions?field=destination"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get transitions (order by name)
    When I send a GET request to "/api/v1/en/workflow/default/transitions?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get transitions (order by description)
    When I send a GET request to "/api/v1/en/workflow/default/transitions?field=description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Delete transition in default workflow (source not found)
    When I send a DELETE request to "/api/v1/en/workflow/default/transitions/@@random_uuid@@/@workflow_destination_status_id@"
    Then the response status code should be 404

  Scenario: Delete transition in default workflow (destination not found)
    When I send a DELETE request to "/api/v1/en/workflow/default/transitions/@workflow_source_status_id@/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Delete transition in default workflow
    When I send a DELETE request to "/api/v1/en/workflow/default/transitions/@workflow_source_status_id@/@workflow_destination_status_id@"
    Then the response status code should be 204

  Scenario: Delete source status
    When I send a DELETE request to "/api/v1/en/status/@workflow_source_status_id@"
    Then the response status code should be 204

  Scenario: Delete destination status
    When I send a DELETE request to "/api/v1/en/status/@workflow_destination_status_id@"
    Then the response status code should be 204
