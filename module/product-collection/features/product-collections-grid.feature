Feature: Product collection module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario Outline: Get product collection (order by code)
    When I send a GET request to "/api/v1/en_GB/collections?field=<field>&filter=<field>=<filter>"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    Examples:
      | field       | filter      |
      | code        | Code        |
      | name        | Name        |
      | description | Description |
      | type_id     | TypeId      |

  Scenario Outline: Get product collection (order <order>)
    When I send a GET request to "/api/v1/en_GB/collections?order=<order>"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    Examples:
      | order |
      | ASC   |
      | DESC  |
