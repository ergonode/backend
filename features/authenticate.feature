Feature: Authentication module

  Scenario: Authenticate with incorrect credentials
    When Authenticate as user "not@existing.email" with password "1"
    Then unauthorized response is received
