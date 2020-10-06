Feature: Workflow

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create first status
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE @@random_md5@@",
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
    And store response param "id" as "workflow_first_status"

  Scenario: Create second status
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "DESTINATION @@random_md5@@",
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
    And store response param "id" as "workflow_second_status"

   Scenario: Create status (invalid hex color)
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#zzZZzz",
        "code": "@@random_code@@"
      }
      """
    Then the response status code should be 400

  Scenario: Create status (without color)
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "code": "SOURCE @@random_md5@@",
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
    Then the response status code should be 400

  Scenario: Create status (without code)
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
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
    Then the response status code should be 400

  Scenario: Create status (without description and name)
    Given remember param "duplicated_status_code" with value "DESTINATION_1_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "@duplicated_status_code@"
      }
      """
    Then the response status code should be 201

  Scenario: Create status (duplicated)
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "@duplicated_status_code@"
      }
      """
    Then the response status code should be 400

  Scenario: Update source status
    Given remember param "duplicated_status_code" with value "DESTINATION_1_@@random_code@@"
    When I send a PUT request to "/api/v1/en_GB/status/@workflow_first_status@" with body:
      """
      {
        "color": "#ff00ff",
        "name": {
          "pl_PL": "Polish",
          "en_GB": "English"
        },
        "description": {
          "pl_PL": "Polish",
          "en_GB": "English"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update source status (not found)
    When I send a PUT request to "/api/v1/en_GB/status/@@random_code@@" with body:
      """
      {
        "color": "#ff00ff"
      }
      """
    Then the response status code should be 404

  Scenario: Get first status
    When I send a GET request to "/api/v1/en_GB/status/@workflow_first_status@"
    Then the response status code should be 200
    And store response param "code" as "workflow_first_status_code"

  Scenario: Get second status
    When I send a GET request to "/api/v1/en_GB/status/@workflow_second_status@"
    Then the response status code should be 200
    And store response param "code" as "workflow_second_status_code"

  Scenario: Get statuses
    When I send a GET request to "/api/v1/en_GB/status"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order by id)
    When I send a GET request to "/api/v1/en_GB/status?field=id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order by code)
    When I send a GET request to "/api/v1/en_GB/status?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order by name)
    When I send a GET request to "/api/v1/en_GB/status?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order by description)
    When I send a GET request to "/api/v1/en_GB/status?field=description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order ASC)
    When I send a GET request to "/api/v1/en_GB/status?field=name&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order DESC)
    When I send a GET request to "/api/v1/en_GB/status?field=name&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (filter by id)
    When I send a GET request to "/api/v1/en_GB/status?limit=25&offset=0&filter=id%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (filter by name)
    When I send a GET request to "/api/v1/en_GB/status?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (filter by code)
    When I send a GET request to "/api/v1/en_GB/status?limit=25&offset=0&filter=code%3Den"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (filter by description)
    When I send a GET request to "/api/v1/en_GB/status?limit=25&offset=0&filter=description%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Set default status
    When I send a PUT request to "/api/v1/en_GB/workflow/default/status/@workflow_first_status@/default"
    Then the response status code should be 204

  Scenario: Set default status (not found)
    When I send a DELETE request to "/api/v1/en_GB/workflow/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Delete status (not found)
    When I send a DELETE request to "/api/v1/en/status/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Delete first status
    When I send a DELETE request to "/api/v1/en_GB/status/@workflow_first_status_code@"
    Then the response status code should be 409

  Scenario: Delete second status
    When I send a DELETE request to "/api/v1/en_GB/status/@workflow_second_status_code@"
    Then the response status code should be 204
