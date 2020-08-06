Feature: Core module - language tree

  Scenario: Get Tree
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/v1/en_GB/language/tree"
    Then the response status code should be 200

  Scenario: Get language en
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/languages/en_GB"
    Then the response status code should be 200
    And store response param "id" as "language_id_en"

  Scenario: Get language pl
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/languages/pl_PL"
    Then the response status code should be 200
    And store response param "id" as "language_id_pl"

  Scenario: Get language fr
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/languages/fr_FR"
    Then the response status code should be 200
    And store response param "id" as "language_id_fr"

  Scenario: Get language de
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/languages/de_DE"
    Then the response status code should be 200
    And store response param "id" as "language_id_de"

  Scenario: Get language uk
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/languages/uk_UA"
    Then the response status code should be 200
    And store response param "id" as "language_id_uk"


  Scenario: Update Tree
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/language/tree" with body:
      """
        {

        }
      """
    Then the response status code should be 400

  Scenario: Update Tree(empty languages)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/language/tree" with body:
      """
        {
            "languages":
                { }
        }
      """
    Then the response status code should be 400

  Scenario: Update Tree(language doesynt exist)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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
