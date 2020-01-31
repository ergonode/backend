Feature: Account module

  Scenario: Get profile
    Given I am Authenticated as "test@ergonode.com"
    When I send a GET request to "/api/v1/profile"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | first_name        | Johnny            |
      | email             | test@ergonode.com |


