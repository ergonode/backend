Feature: Multimedia grid feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Request multimedia grid filtered by type
    When I send a GET request to "api/v1/en_GB/multimedia?columns=id&filter=type%3Dapplication"
    Then the response status code should be 200

  Scenario: Request multimedia grid filtered by type
    When I send a GET request to "api/v1/en_GB/multimedia?columns=id&filter=size%3D12"
    Then the response status code should be 200

  Scenario Outline: Request multimedia grid filtered by type <code> attribute
    When I send a GET request to "api/v1/en_GB/multimedia?columns=<code>&filter=<code>=<filter>"
    Then the response status code should be 200
    Examples:
      | code       | filter      |
      | type       | application |
      | name       | name test   |
      | extension  | doc,docx    |
      | size_mb    | 12          |
      | relations  | 0           |
      | created_at | 2020-08-19  |
