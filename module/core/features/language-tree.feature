Feature: Core module - language tree

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get Tree
    And I send a "GET" request to "/api/v1/en_GB/language/tree"
    Then the response status code should be 200

  Scenario Outline: Get language <language>
    When I send a GET request to "/api/v1/en_GB/languages/<language>"
    Then the response status code should be 200
    And store response param "id" as "<id>"
    Examples:
      | language | id             |
      | en_GB    | language_id_en |
      | pl_PL    | language_id_pl |
      | fr_FR    | language_id_fr |
      | de_DE    | language_id_de |
      | uk_UA    | language_id_uk |

  Scenario: Update Tree
    When I send a PUT request to "/api/v1/en_GB/language/tree" with body:
      """
        {
            "languages":
                {
                    "language_id":"@language_id_en@",
                    "children":[
                        {
                            "language_id":"@language_id_pl@",
                            "children":[
                                {
                                    "language_id":"@language_id_de@",
                                    "children":[]
                                }
                            ]
                        },
                        {
                            "language_id":"@language_id_fr@",
                            "children":[]
                        },
                        {
                            "language_id":"@language_id_uk@",
                            "children":[]
                        }
                    ]
                }

        }
      """
    Then the response status code should be 204

  Scenario: Update Tree(empty data)
    When I send a PUT request to "/api/v1/en_GB/language/tree" with body:
      """
        {

        }
      """
    Then the response status code should be 400

  Scenario: Update Tree(empty languages)
    When I send a PUT request to "/api/v1/en_GB/language/tree" with body:
      """
        {
            "languages":
                { }
        }
      """
    Then the response status code should be 400

  Scenario: Update Tree(language doesynt exist)
    When I send a PUT request to "/api/v1/en_GB/language/tree" with body:
      """
        {
            "languages":
                {
                    "language_id":"@@random_uuid@@",
                    "children":[]
                }
        }
      """
    Then the response status code should be 400
