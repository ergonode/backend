Feature: Export Profile module

  Scenario: Get profile type
    Given current authentication token
    When I request "/api/v1/EN/dictionary/export-profile" using HTTP GET
    Then the response code is 200

  Scenario: Post Create Export profile to magento 2 csv
    Given current authentication token
    Given the request body is:
      """
        {
          "type": "magento-2-csv",
          "name": "Magento 2 csv",
          "params": {
            "filename": "m2.csv"
          }
        }
      """
    When I request "/api/v1/EN/export-profile" using HTTP POST
    Then created response is received
    And remember response param "id" as "export-profile"

  Scenario: Post Create Export profile to magento 2 csv no file name
    Given current authentication token
    Given the request body is:
      """
        {
          "type": "magento-2-csv",
          "name": "Magento 2 csv",
          "params": {
          }
        }
      """
    When I request "/api/v1/EN/export-profile" using HTTP POST
    Then validation error response is received

  Scenario: Get export profile
    Given current authentication token
    When I request "/api/v1/EN/export-profile/@export-profile@" using HTTP GET
    Then the response code is 200

  Scenario: Update Export Profile
    Given current authentication token
    Given the request body is:
      """
        {
          "type": "magento-2-csv",
          "name": "Magento 2 csv",
          "params": {
            "filename": "maaa2.csv"
          }
        }
      """
    When I request "/api/v1/EN/export-profile/@export-profile@" using HTTP PUT
    Then the response code is 204

  Scenario: Delete export profile
    Given current authentication token
    When I request "/api/v1/EN/export-profile/@export-profile@" using HTTP DELETE
    Then the response code is 204
