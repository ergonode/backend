Feature: Attribute grid

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

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

