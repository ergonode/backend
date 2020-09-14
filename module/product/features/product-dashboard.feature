Feature: Product dashboard

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get product count dashboard widget information
    When I send a GET request to "/api/v1/en_EN/dashboard/widget/product-count"
    Then the response status code should be 200
