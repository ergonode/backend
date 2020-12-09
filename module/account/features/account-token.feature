Feature: Account Token

  Scenario: Reset Token user
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/accounts/token/generate" with body:
      """
      {
          "email": "test@ergonode.com",
          "url": "http://localhost/rest?token="
      }
      """
    Then the response status code should be 204

  Scenario: Validate Token
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/accounts/token/validation?token=testt"
    Then the response status code should be 400
