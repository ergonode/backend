Feature: Account module

  Scenario: Create text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
    """
    {
        "code": "TEXT_@@random_code@@",
        "type": "TEXT",
        "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
        "groups": [],
        "parameters": []
    }
    """
    Then the response status code should be 201
    And store response param "id" as "text_attribute"

  Scenario: Update textarea attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/v1/EN/attributes/@text_attribute@" with body:
    """
    {
        "type": "TEXT_AREA",
        "groups": [],
        "parameters": []
    }
    """
    Then the response should be empty
    And the response status code should be 204
