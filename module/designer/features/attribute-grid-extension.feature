Feature: Designer module - attribute grid extension

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get attributes grid with template column
    And I send a "GET" request to "/api/v1/en_GB/attributes?field=template"
    Then the response status code should be 200

  Scenario: Get system attributes grid with template column
    And I send a "GET" request to "/api/v1/en_GB/attributes/system?field=template"
    Then the response status code should be 200