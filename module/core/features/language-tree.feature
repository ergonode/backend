Feature: Core module - language tree

  Scenario: Get Tree
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/v1/en/language/tree"
    Then the response status code should be 200

  Scenario: Update Tree
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/language/tree" with body:
      """
        {
            "languages":
                {
                    "language":"en",
                    "children":[
                        {
                            "language":"pl",
                            "children":[
                                {
                                    "language":"fr",
                                    "children":[]
                                }
                            ]
                        },
                        {
                            "language":"de",
                            "children":[]
                        },
                        {
                            "language":"ar",
                            "children":[]
                        }
                    ]
                }

        }
      """
    Then the response status code should be 204
