Feature: Core module - api doc

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get api doc
    And I send a "GET" request to "/api/doc"
    Then the response status code should be 200