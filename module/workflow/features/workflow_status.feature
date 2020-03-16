Feature: Workflow

  Scenario: Create first status
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
    And store response param "id" as "workflow_first_status"

  Scenario: Create second status
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
    And store response param "id" as "workflow_second_status"

  Scenario: Create status (not authorized)
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
    Then the response status code should be 401

  Scenario: Create status (invalid hex color)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/status" with body:
      """
      {
        "color": "#zzZZzz",
        "code": "@@random_code@@"
      }
      """
    Then the response status code should be 400

  Scenario: Create status (without color)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/status" with body:
      """
      {
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
    Then the response status code should be 400

  Scenario: Create status (without code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/status" with body:
      """
      {
        "color": "#ff0000",
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
    Then the response status code should be 400

  Scenario: Create status (without description and name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given remember param "duplicated_status_code" with value "DESTINATION_1_@@random_code@@"
    When I send a POST request to "/api/v1/EN/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "@duplicated_status_code@"
      }
      """
    Then the response status code should be 201

  Scenario: Create status (duplicated)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "@duplicated_status_code@"
      }
      """
    Then the response status code should be 400

  Scenario: Update source status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given remember param "duplicated_status_code" with value "DESTINATION_1_@@random_code@@"
    When I send a PUT request to "/api/v1/EN/status/@workflow_first_status@" with body:
      """
      {
        "color": "#ff00ff",
        "name": {
          "PL": "Polish",
          "EN": "English"
        },
        "description": {
          "PL": "Polish",
          "EN": "English"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update source status (not authorized)
    When I send a PUT request to "/api/v1/EN/status/@workflow_first_status@" with body:
      """
      {
        "color": "#ff00ff"
      }
      """
    Then the response status code should be 401

  Scenario: Update source status (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/status/@@random_code@@" with body:
      """
      {
        "color": "#ff00ff"
      }
      """
    Then the response status code should be 404

  Scenario: Get first status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status/@workflow_first_status@"
    Then the response status code should be 200
    And store response param "code" as "workflow_first_status_code"

  Scenario: Get second status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status/@workflow_second_status@"
    Then the response status code should be 200
    And store response param "code" as "workflow_second_status_code"

  Scenario: Get statuses
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?field=id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?field=description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?field=name&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?field=name&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (filter by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?limit=25&offset=0&filter=id%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?limit=25&offset=0&filter=code%3DEN"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (filter by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/status?limit=25&offset=0&filter=description%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get statuses (not authorized)
    When I send a GET request to "/api/v1/EN/status"
    Then the response status code should be 401

  Scenario: Set default status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/workflow/default/status/@workflow_first_status@/default"
    Then the response status code should be 204

  Scenario: Set default status (not authorized)
    When I send a PUT request to "/api/v1/EN/workflow/default/status/@workflow_first_status@/default"
    Then the response status code should be 401

  Scenario: Set default status (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/workflow/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Delete status (not authorized)
    When I send a DELETE request to "/api/v1/EN/status/@workflow_first_status_code@"
    Then the response status code should be 401

  Scenario: Delete status (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/status/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Delete first status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/status/@workflow_first_status_code@"
    Then the response status code should be 409

  Scenario: Delete second status
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/status/@workflow_second_status_code@"
    Then the response status code should be 204
