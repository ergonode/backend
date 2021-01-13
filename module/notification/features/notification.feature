Feature: Transformer module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get notifications
    When I send a GET request to "/api/v1/profile/notifications"
    Then the response status code should be 200

  Scenario: Mark all notifications
    When I send a POST request to "/api/v1/profile/notifications/mark-all"
    Then the response status code should be 202

