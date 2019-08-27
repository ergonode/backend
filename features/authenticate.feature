Feature: Authentication module

  Scenario: Authenticate with incorrect credentials
    When Authenticate as user "not@existing.email" with password "1"
    Then the response code is 401
