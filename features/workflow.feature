Feature: Workflow

  Scenario: Create default status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
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
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received
    And remember response param "id" as "workflow_status"

  Scenario: Create status (color wrong format)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "test",
        "code": "ST @@random_md5@@",
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
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (color empty)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "",
        "code": "ST @@random_md5@@",
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
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (without color)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "ST @@random_md5@@",
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
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (empty code)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "",
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
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (without code)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
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
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (wrong parameter)
    Given current authentication token
    Given the request body is:
      """
      {
        "test": "#ff0",
        "code": "ST @@random_md5@@",
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
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (wrong language parameter)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {
          "ZZ": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (empty name)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {},
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received

  Scenario: Create status (without name)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received

  Scenario: Create status (wrong language parameter)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {
          "ZZ": "PL",
          "EN": "EN"
        },
        "description": {
          "ZZ": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received

  Scenario: Create status (empty description)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description" : {}
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received

  Scenario: Create status (without description)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received

  Scenario: Create default status (not authorized)
    When I request "/api/v1/EN/status" using HTTP POST
    Then unauthorized response is received

  Scenario: Get default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_status_code"

  Scenario: Update default status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {
          "PL": "PL (changed)",
          "EN": "EN (changed)"
        },
        "description": {
          "PL": "PL (changed)",
          "EN": "EN (changed)"
        }
      }
      """
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then empty response is received

  Scenario: Update default status (not authorized)
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/status/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update status (color wrong format)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "test",
        "code": "ST @@random_md5@@",
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
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then validation error response is received

  Scenario: Update status (color empty)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "",
        "code": "ST @@random_md5@@",
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
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then validation error response is received

  Scenario: Update status (without color)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "ST @@random_md5@@",
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
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then validation error response is received

  Scenario: Update status (empty code)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "",
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
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then validation error response is received

  Scenario: Update status (without code)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
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
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then validation error response is received

  Scenario: Update status (wrong parameter)
    Given current authentication token
    Given the request body is:
      """
      {
        "test": "#ff0",
        "code": "ST @@random_md5@@",
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
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then validation error response is received

  Scenario: Update status (wrong language parameter)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {
          "ZZ": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then validation error response is received

  Scenario: Update status (empty name)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": "",
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then validation error response is received

  Scenario: Update status (without name)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then empty response is received

  Scenario: Update status (wrong language parameter)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {
          "ZZ": "PL",
          "EN": "EN"
        },
        "description": {
          "ZZ": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then created response is received

  Scenario: Update status (empty description)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description" :""
      }
      """
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then validation error response is received

  Scenario: Update status (without description)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then empty response is received

  Scenario: Get default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP GET
    Then the response code is 200

  Scenario: Get default status (not authorized)
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/status/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Update default workflow
    Given current authentication token
    Given the request body is:
    """
      {
        "code": "TEST_@@random_code@@",
        "statuses": ["@workflow_status_code@"],
        "transitions": []
      }
    """
    When I request "/api/v1/EN/workflow/default" using HTTP PUT
    Then empty response is received

  Scenario: Update default workflow (wrong status)
    Given current authentication token
    Given the request body is:
    """
    {
      "code": "TEST_@@random_code@@",
      "statuses": ["test"],
      "transitions": []
    }
    """
    When I request "/api/v1/EN/workflow/default" using HTTP PUT
    Then validation error response is received

  Scenario: Get default statuses
    Given current authentication token
    When I request "/api/v1/EN/status" using HTTP GET
    Then grid response is received

  Scenario: Get default statuses (not authorized)
    When I request "/api/v1/EN/status" using HTTP GET
    Then unauthorized response is received

  Scenario: Get status (order by id)
    Given current authentication token
    When I request "/api/v1/EN/status?field=id" using HTTP GET
    Then grid response is received

  Scenario: Get status (order by code)
    Given current authentication token
    When I request "/api/v1/EN/status?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get status (order by name)
    Given current authentication token
    When I request "/api/v1/EN/status?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get status (order by description)
    Given current authentication token
    When I request "/api/v1/EN/status?field=description" using HTTP GET
    Then grid response is received

  Scenario: Get status (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/status?field=name&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get status (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/status?field=name&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get status (filter by id)
    Given current authentication token
    When I request "/api/v1/EN/status?limit=25&offset=0&filter=id%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get status (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/status?limit=25&offset=0&filter=name%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get status (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/status?limit=25&offset=0&filter=code%3DEN" using HTTP GET
    Then grid response is received

  Scenario: Get status (filter by description)
    Given current authentication token
    When I request "/api/v1/EN/status?limit=25&offset=0&filter=description%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get status (not authorized)
    When I request "/api/v1/EN/status" using HTTP GET
    Then unauthorized response is received

  Scenario: Create workflow
    Given current authentication token
    Given the request body is:
    """
      {
        "code": "WRK_@@random_code@@",
        "statuses": ["@workflow_status_code@"],
        "transitions": []
      }
    """
    When I request "/api/v1/EN/workflow" using HTTP POST
    Then created response is received
    And remember response param "id" as "workflow"

  Scenario: Create workflow (wrong statuses)
    Given current authentication token
    Given the request body is:
    """
    {
      "code": "WRK_@@random_code@@",
      "statuses": ["test"],
      "transitions": []
    }
    """
    When I request "/api/v1/EN/workflow" using HTTP POST
    Then validation error response is received

  Scenario: Create workflow (not authorized)
    When I request "/api/v1/EN/workflow" using HTTP POST
    Then unauthorized response is received

  Scenario: Update default workflow (not authorized)
    When I request "/api/v1/EN/workflow/default" using HTTP PUT
    Then unauthorized response is received

  Scenario: Get default workflow
    Given current authentication token
    When I request "/api/v1/EN/workflow/default" using HTTP GET
    Then the response code is 200

  Scenario: Get default workflow (not authorized)
    When I request "/api/v1/EN/workflow/default" using HTTP GET
    Then unauthorized response is received

  Scenario: Delete workflow (not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/@static_uuid@" using HTTP DELETE
    Then not found response is received

#  Scenario: Delete workflow (not authorized)
#    When I request "/api/v1/EN/workflow/@workflow@" using HTTP DELETE
#    Then unauthorized response is received
#
#  Scenario: Delete workflow
#    Given current authentication token
#    When I request "/api/v1/EN/workflow/@workflow@" using HTTP DELETE
#    Then empty response is received

  Scenario: Delete default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP DELETE
    Then empty response is received

  Scenario: Delete default status (not authorized)
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/status/@@static_uuid@@" using HTTP DELETE
    Then not found response is received
