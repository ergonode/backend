Feature: Export Profile Magento 2 CSV

  Scenario: Post Create Export profile to magento 2 csv
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/export-profile" with body:
      """
        {
          "type": "magento-2-csv",
          "name": "Magento 2 csv",
          "filename": "m2.csv",
          "default_language": "EN"
        }
      """
    Then the response status code should be 201
    And store response param "id" as "export-profile"

  Scenario: Post Create Export profile to magento 2 csv no file name
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/export-profile" with body:
      """
        {
          "type": "magento-2-csv",
          "name": "Magento 2 csv",
          "default_language": "EN"
        }
      """
    Then the response status code should be 500

  Scenario: Get export profile
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/export-profile/@export-profile@"
    Then the response status code should be 200

  Scenario: Update Export Profile
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/export-profile/@export-profile@" with body:
      """
        {
          "type": "magento-2-csv",
          "name": "Magento 2 csv",
          "default_language": "EN",
          "filename": "maaa2.csv"
        }
      """
    Then the response status code should be 204

  Scenario: Delete export profile
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/export-profile/@export-profile@"
    Then the response status code should be 204
