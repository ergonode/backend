Feature: Transformer module

  Scenario: Create transformer
    Given current authentication token
    Given the following form parameters are set:
      | name | value                       |
      | name | TRANSFORMER_@@random_uuid@@ |
    When I request "/api/v1/transformers/create" using HTTP POST
    Then created response is received
    And remember response param "id" as "transformer"

  Scenario: Create transformer (not authorized)
    Given the following form parameters are set:
      | name | value                  |
      | name | READER_@@random_uuid@@ |
    When I request "/api/v1/transformers/create" using HTTP POST
    Then unauthorized response is received

  Scenario: Delete transformer (not authorized)
    When I request "/api/v1/transformers/@transformer@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete transformer (not found)
    Given current authentication token
    When I request "/api/v1/transformers/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete transformer
    Given current authentication token
    When I request "/api/v1/transformers/@transformer@" using HTTP DELETE
    Then empty response is received

  # TODO Check create transformer action with all incorrect possibilities
  # TODO Check get transformer action with all incorrect and correct possibilities
  # TODO Check generate transformer action with all incorrect and correct possibilities
  # TODO Check processors transformer action with all incorrect and correct possibilities
