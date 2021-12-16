Feature: Workflow Condition

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get condition configuration (not found)
    When I send a GET request to "/api/v1/en_GB/workflow/condition/asd"
    Then the response status code should be 404

  Scenario Outline: Get condition configuration (<type>)
    When I send a GET request to "/api/v1/en_GB/workflow/condition/<type>"
    Then the response status code should be 200
    And print last response
    And the JSON node "type" should contain "<type>"
    And the JSON node "name" should exist
    And the JSON node "phrase" should exist
    Examples:
      | type                       |
      | ATTRIBUTE_EXISTS_CONDITION |
      | ATTRIBUTE_EXISTS_CONDITION |
      | ROLE_IS_CONDITION          |
      | USER_IS_CONDITION          |
