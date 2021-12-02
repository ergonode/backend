Feature: Workflow transitions

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create role
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "role_id"

  Scenario: Create from status
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "FROM @@random_md5@@",
        "name": {
          "pl_PL": "pl_PL",
          "en_GB": "en_GB"
        },
        "description": {
          "pl_PL": "pl_PL",
          "en_GB": "en_GB"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "workflow_from_status"

  Scenario: Create to status
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "TO @@random_md5@@",
        "name": {
          "pl_PL": "pl_PL",
          "en_GB": "en_GB"
        },
        "description": {
          "pl_PL": "pl_PL",
          "en_GB": "en_GB"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "workflow_to_status"

  Scenario: Get from status
    When I send a GET request to "/api/v1/en_GB/status/@workflow_from_status@"
    Then the response status code should be 200
    And store response param "id" as "workflow_from_status_id"

  Scenario: Get to status
    When I send a GET request to "/api/v1/en_GB/status/@workflow_to_status@"
    Then the response status code should be 200
    And store response param "id" as "workflow_to_status_id"

  Scenario: Add transition to workflow
    When I send a POST request to "/api/v1/en_GB/workflow/default/transitions" with body:
      """
      {
        "from": "@workflow_from_status_id@",
        "to": "@workflow_to_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en_GB": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en_GB": "Translated description en"
        },
        "roles": [
           "@role_id@"
        ]
      }
      """
    Then the response status code should be 201

  Scenario: Get transition in default workflow
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions/@workflow_from_status_id@/@workflow_to_status_id@"
    Then the response status code should be 200
    And the JSON node "role_ids[0]" should not be null

  Scenario: Create transition to workflow (duplicated)
    When I send a POST request to "/api/v1/en_GB/workflow/default/transitions"
      """
      {
        "from": "@workflow_from_status_id@",
        "to": "@workflow_to_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en_GB": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en_GB": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (without from)
    When I send a POST request to "/api/v1/en_GB/workflow/default/transitions" with body:
      """
      {
        "to": "@workflow_to_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en_GB": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en_GB": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (without to)
    When I send a POST request to "/api/v1/en_GB/workflow/default/transitions" with body:
      """
      {
        "from": "@workflow_from_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en_GB": "Translated name en"
        },
        "to": {
          "pl_PL": "Translated description PL",
          "en_GB": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (from not found)
    When I send a POST request to "/api/v1/en_GB/workflow/default/transitions" with body:
      """
      {
        "from": "@@random_uuid@@",
        "to": "@workflow_to_status_id@",
        "name": {
          "pl_PL": "Translated name PL",
          "en_GB": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en_GB": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (to not found)
    When I send a POST request to "/api/v1/en_GB/workflow/default/transitions" with body:
      """
      {
        "from": "@workflow_from_status_id@",
        "to": "@@random_uuid@@",
        "name": {
          "pl_PL": "Translated name PL",
          "en_GB": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en_GB": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update transition to workflow
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@workflow_from_status_id@/@workflow_to_status_id@" with body:
      """
      {
        "name": {
          "pl_PL": "Translated name PL",
          "en_GB": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en_GB": "Translated description en"
        },
        "roles": []
      }
      """
    Then the response status code should be 204

  Scenario: Get transition in default workflow
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions/@workflow_from_status_id@/@workflow_to_status_id@"
    Then the response status code should be 200
    And the JSON node "role_ids[0]" should not be null

  Scenario: Update transition to workflow (from not found)
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@@random_code@@/@workflow_to_status_id@"
      """
      {
        "name": {
          "pl_PL": "Translated name PL",
          "en_GB": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en_GB": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update transition to workflow (to not found)
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@workflow_from_status_id@/@@random_code@@" with body:
      """
      {
        "name": {
          "pl_PL": "Translated name PL",
          "en_GB": "Translated name en"
        },
        "description": {
          "pl_PL": "Translated description PL",
          "en_GB": "Translated description en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Get transition in default workflow
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions/@workflow_from_status_id@/@workflow_to_status_id@"
    Then the response status code should be 200

  Scenario: Get transition in default workflow (from not found)
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions/@@random_uuid@@/@workflow_to_status_id@"
    Then the response status code should be 404

  Scenario: Get transition in default workflow (to not found)
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions/@workflow_from_status_id@/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Get transitions (order by from)
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions?field=from"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get transitions (order by to)
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions?field=to"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get transitions (order by name)
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions?field=name"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get transitions (order by description)
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions?field=description"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Delete transition in default workflow (from not found)
    When I send a DELETE request to "/api/v1/en_GB/workflow/default/transitions/@@random_uuid@@/@workflow_to_status_id@"
    Then the response status code should be 404

  Scenario: Delete transition in default workflow (to not found)
    When I send a DELETE request to "/api/v1/en_GB/workflow/default/transitions/@workflow_from_status_id@/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Delete transition in default workflow
    When I send a DELETE request to "/api/v1/en_GB/workflow/default/transitions/@workflow_from_status_id@/@workflow_to_status_id@"
    Then the response status code should be 204

  Scenario: Delete from status
    When I send a DELETE request to "/api/v1/en_GB/status/@workflow_from_status_id@"
    Then the response status code should be 204

  Scenario: Delete to status
    When I send a DELETE request to "/api/v1/en_GB/status/@workflow_to_status_id@"
    Then the response status code should be 204
