Feature: Transformer module

  Scenario: Create transformer
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/transformers/create" with parameters:
      | key  | value                       |
      | name | TRANSFORMER_@@random_uuid@@ |
    Then the response status code should be 201
    And store response param "id" as "transformer"

  Scenario: Create transformer (not authorized)
    When I send a POST request to "/api/v1/transformers/create" with parameters:
      | key  | value                       |
      | name | TRANSFORMER_@@random_uuid@@ |

    Then the response status code should be 401

  Scenario: Delete transformer (not authorized)
    When I send a DELETE request to "/api/v1/transformers/@transformer@"
    Then the response status code should be 401

  Scenario: Delete transformer (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/transformers/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete transformer
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/transformers/@transformer@"
    Then the response status code should be 204

  # TODO Check create transformer action with all incorrect possibilities
  # TODO Check get transformer action with all incorrect and correct possibilities
  # TODO Check generate transformer action with all incorrect and correct possibilities
  # TODO Check processors transformer action with all incorrect and correct possibilities
