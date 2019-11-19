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

  Scenario: Get attributes
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups?filter=id=@attribute_group_id@" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"name": "Attribute group EN"/
    """

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

  Scenario: delete attribute group
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups/@attribute_group_id@" using HTTP DELETE
    Then empty response is received

  Scenario: Get attribute group
    Given current authentication token
    When I request "/api/v1/EN/attributes/groups/@attribute_group_id@" using HTTP GET
    Then not found response is received

