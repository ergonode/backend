Feature: Account module

  Scenario: Get profile
    Given I am Authenticated as "test@ergonode.com"
    When I send a GET request to "/api/v1/profile"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | first_name        | Johnny            |
      | email             | test@ergonode.com |


  Scenario: Create text attribute
    Given I am Authenticated as "test@ergonode.com"
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
    Then  print last JSON response
    Then the response status code should be 200
    And remember response param "id" as "text_attribute"


  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
    """
    {
        "code": "TEXT_@@random_code@@",
        "type": "TEXT",
        "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
        "groups": [],
        "parameters": []
    }
    """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "text_attribute"

  Scenario: Update textarea attribute
    Given current authentication token
    Given the request body is:
    """
    {
        "type": "TEXTAREA",
        "groups": [],
        "parameters": []
    }
    """
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP PUT
    Then empty response is received