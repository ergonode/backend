Feature: Attribute module

  Scenario: Create attribute group
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "ATTRIBUTE_GROUP_@@random_code@@",
          "name": {"PL": "Grupa atrybutów PL", "EN": "Attribute group EN"}
      }
      """
    When I request "/api/v1/EN/attributes/groups" using HTTP POST
    Then created response is received
    And remember response param "id" as "attribute_group_id"

  Scenario: Create attribute group (not authorized)
    When I request "/api/v1/EN/attributes/groups" using HTTP POST
    Then unauthorized response is received

  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": ["@attribute_group_id@"],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "attribute_id"

  Scenario: Get attribute group
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups/@attribute_group_id@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"PL": "Grupa atrybutów PL"/
    """
    And the response body matches:
    """
      /"EN": "Attribute group EN"/
    """

  Scenario: Ger attribute group (not authorized)
    When I request "/api/v1/EN/attributes/groups/@attribute_group_id@" using HTTP GET
    Then unauthorized response is received

  Scenario: Ger attribute group (not found)
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups/@static_uuid@" using HTTP GET
    Then not found response is received

  Scenario: Get attributes groups
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups?filter=id=@attribute_group_id@" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"value": "Attribute group EN"/
    """

  Scenario: Get attribute groups (not authorized)
    When I request "/api/v1/EN/attributes/groups" using HTTP GET
    Then unauthorized response is received

  Scenario: Update attribute group
    Given current authentication token
    Given the request body is:
      """
      {
          "name": {"PL": "PL", "EN": "EN"}
      }
      """
    When I request "/api/v1/EN/attributes/groups/@attribute_group_id@" using HTTP PUT
    Then empty response is received

  Scenario: Update attribute group (not authorized)
    When I request "/api/v1/EN/attributes/groups/@attribute_group_id@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update attribute group (not found)
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups/@static_uuid@" using HTTP PUT
    Then not found response is received

  Scenario: Get attribute group after update
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups/@attribute_group_id@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"PL": "PL"/
    """
    And the response body matches:
    """
      /"EN": "EN"/
    """

  Scenario: Get attributes after Update
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups?filter=id=@attribute_group_id@" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"value": "EN"/
    """

  Scenario: Delete attribute group
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups/@attribute_group_id@" using HTTP DELETE
    Then empty response is received

  Scenario: Delete attribute group (not found)
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups/@static_uuid@" using HTTP DELETE
    Then not found response is received

  Scenario: Get attribute group
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups/@attribute_group_id@" using HTTP GET
    Then not found response is received

