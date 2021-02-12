Feature: Attribute grid

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create text attribute
    And remember param "attribute_code" with value "text_@@random_code@@"
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "@attribute_code@",
          "type": "TEXT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Get attributes (filter by @attribute_code@ attribute)
    And I send a "GET" request to "/api/v1/en_GB/attributes?limit=25&offset=0&filter=code=@attribute_code@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | collection[0].id    | @attribute_id@   |
      | collection[0].code  | @attribute_code@ |
      | collection[0].scope | local            |
      | collection[0].type  | TEXT             |

  Scenario Outline: Get attributes (order by <field>)
    And I send a "GET" request to "/api/v1/en_GB/attributes?field=<field>&order=<order>"
    Then the response status code should be 200
    Examples:
      | field        | order |
      | index        | ASC   |
      | code         | ASC   |
      | label        | ASC   |
      | type         | ASC   |
      | groups       | ASC   |
      | multilingual | ASC   |
      | index        | DESC  |
      | code         | DESC  |
      | label        | DESC  |
      | type         | DESC  |
      | groups       | DESC  |
      | multilingual | DESC  |

  Scenario Outline: Get attributes (filter by <field>)
    And I send a "GET" request to "/api/v1/en_GB/attributes?limit=25&offset=0&filter=<field>=<value>"
    Then the response status code should be 200
    Examples:
      | field  | value |
      | id     | abc   |
      | index  | abc   |
      | code   | abc   |
      | label  | abc   |
      | type   | abc   |
      | groups | abc   |
