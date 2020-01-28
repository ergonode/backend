Feature: Reader module

  Scenario: Create reader
    Given current authentication token
    Given the following form parameters are set:
      | name | value                  |
      | name | READER_@@random_uuid@@ |
      | type | csv                    |
    When I request "/api/v1/EN/readers" using HTTP POST
    Then created response is received
    And remember response param "id" as "reader"

  Scenario: Create reader (not authorized)
    Given the following form parameters are set:
      | name | value                  |
      | name | READER_@@random_uuid@@ |
      | type | csv                    |
    When I request "/api/v1/EN/readers" using HTTP POST
    Then unauthorized response is received

  Scenario: Delete reader (not authorized)
    When I request "/api/v1/EN/readers/@reader@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete reader (not found)
    Given current authentication token
    When I request "/api/v1/EN/readers/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete reader
    Given current authentication token
    When I request "/api/v1/EN/readers/@reader@" using HTTP DELETE
    Then empty response is received

  # TODO Check create reader action with all incorrect possibilities
  # TODO Check get reader action with all incorrect and correct possibilities
  # TODO Check grid reader action with all incorrect and correct possibilities
