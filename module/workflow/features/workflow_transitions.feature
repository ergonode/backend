Feature: Workflow

  Scenario: Create role
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
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
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "workflow_source_status"

  Scenario: Create destination status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "DESTINATION @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "workflow_destination_status"

  Scenario: Get source status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status/@workflow_source_status@"
    Then the response status code should be 200
    And store response param "code" as "workflow_source_status_code"

  Scenario: Get destination status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status/@workflow_destination_status@"
    Then the response status code should be 200
    And store response param "code" as "workflow_destination_status_code"

  Scenario: Add transition to workflow
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/workflow/default/transitions" with body:
      """
      {
        "source": "@workflow_source_status_code@",
        "destination": "@workflow_destination_status_code@",
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        },
        "roles": [
           "@role_id@"
        ]
      }
      """
    Then the response status code should be 201

  Scenario: Get transition in default workflow
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then the response status code should be 200
    And the JSON node "role_ids[0]" should not be null

  Scenario: Create transition to workflow (duplicated)
    Given I am Authenticated as "test@ergonode.com"
    When I send a POST request to "/api/v1/EN/workflow/default/transitions"
      """
      {
        "source": "@workflow_source_status_code@",
        "destination": "@workflow_destination_status_code@",
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (without source)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/workflow/default/transitions" with body:
      """
      {
        "destination": "@workflow_destination_status_code@",
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (without destination)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/workflow/default/transitions" with body:
      """
      {
        "source": "@workflow_source_status_code@",
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (source not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/workflow/default/transitions" with body:
      """
      {
        "source": "@@random_uuid@@",
        "destination": "@workflow_destination_status_code@",
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (destination not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/workflow/default/transitions" with body:
      """
      {
        "source": "@workflow_source_status_code@",
        "destination": "@@random_uuid@@",
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create transition to workflow (not authorized)
    When I send a POST request to "/api/v1/EN/workflow/default/transitions" with body:
      """
      {
        "source": "@workflow_source_status_code@",
        "destination": "@workflow_destination_status_code@",
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        }
      }
      """
    Then the response status code should be 401

  Scenario: Update transition to workflow
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@" with body:
      """
      {
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        },
        "roles": []
      }
      """
    Then the response status code should be 204

  Scenario: Get transition in default workflow
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then the response status code should be 200
    And the JSON node "role_ids[0]" should not be null

  Scenario: Update transition to workflow (source not found)
    Given I am Authenticated as "test@ergonode.com"
    When I send a PUT request to "/api/v1/EN/workflow/default/transitions/@@random_code@@/@workflow_destination_status_code@"
      """
      {
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        }
      }
      """
    Then the response status code should be 404

  Scenario: Update transition to workflow (destination not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@@random_code@@" with body:
      """
      {
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        }
      }
      """
    Then the response status code should be 404

  Scenario: Update transition to workflow (not authorized)
    When I send a PUT request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@" with body:
      """
      {
        "name": {
          "PL": "Translated name PL",
          "EN": "Translated name EN"
        },
        "description": {
          "PL": "Translated description PL",
          "EN": "Translated description EN"
        }
      }
      """
    Then the response status code should be 401

  Scenario: Get transition in default workflow
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then the response status code should be 200

  Scenario: Get transition in default workflow (not authorized)
    When I send a GET request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then the response status code should be 401

  Scenario: Get transition in default workflow (source not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/workflow/default/transitions/@@random_uuid@@/@workflow_destination_status_code@"
    Then the response status code should be 404

  Scenario: Get transition in default workflow (destination not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Get transitions (order by source)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/workflow/default/transitions?field=source"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get transitions (order by destination)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/workflow/default/transitions?field=destination"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get transitions (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/workflow/default/transitions?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get transitions (order by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/workflow/default/transitions?field=description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get transitions (not authorized)
    When I send a GET request to "/api/v1/EN/workflow/default/transitions?field=source"
    Then the response status code should be 401

  Scenario: Delete transition in default workflow (not authorized)
    When I send a DELETE request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then the response status code should be 401

  Scenario: Delete transition in default workflow (source not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/workflow/default/transitions/@@random_uuid@@/@workflow_destination_status_code@"
    Then the response status code should be 404

  Scenario: Delete transition in default workflow (destination not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Delete transition in default workflow
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then the response status code should be 204

  Scenario: Delete source status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/status/@workflow_source_status_code@"
    Then the response status code should be 204

  Scenario: Delete destination status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/status/@workflow_destination_status_code@"
    Then the response status code should be 204
