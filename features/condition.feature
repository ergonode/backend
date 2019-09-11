Feature: Condition

  Scenario: Create condition set (not authorized)
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then unauthorized response is received

  Scenario: Create condition set
    Given current authentication token
    Given the request body is:
      """
      {
         "code": "CONDITION_@@random_uuid@@",
         "name": {
            "PL": "Zbiór warunków",
            "EN": "Condition set"
         },
         "description": {
            "PL": "Opis do zbioru warunków",
            "EN": "Condition set description"
         }
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    And remember response param "id" as "conditionset"

  Scenario: Create condition set (without code)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": {
            "PL": "Zbiór warunków",
            "EN": "Condition set"
         },
         "description": {
            "PL": "Opis do zbioru warunków",
            "EN": "Condition set description"
         }
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received

  Scenario: Create condition set (short name)
    Given current authentication token
    Given the request body is:
      """
      {
         "code": "CONDITION_@@random_uuid@@",
         "name": {
            "PL": "Z",
            "EN": "C"
         },
         "description": {
            "PL": "Opis do zbioru warunków",
            "EN": "Condition set description"
         }
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received

  Scenario: Create condition set (long name)
    Given current authentication token
    Given the request body is:
      """
      {
         "code": "CONDITION_@@random_uuid@@",
         "name": {
            "PL": "4YdShn9FoFZechRRsBxscsyHHRCUmJYFRBAWMrfvUjvbsfPPaMPVqPYGzZQqvzFlaxuPazm4baTBHudDM3jWpJJi7npm4bt9CD9OM",
            "EN": "Condition set"
         },
         "description": {
            "PL": "Opis do zbioru warunków",
            "EN": "Condition set description"
         }
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received

  Scenario: Create condition set (without description)
    Given current authentication token
    Given the request body is:
      """
      {
         "code": "CONDITION_@@random_uuid@@",
         "name": {
            "PL": "Zbiór warunków",
            "EN": "Condition set"
         }
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received

  Scenario: Create condition set (long description)
    Given current authentication token
    Given the request body is:
      """
      {
         "code": "CONDITION_@@random_uuid@@",
         "name": {
            "PL": "Zbiór warunków",
            "EN": "Condition set"
         },
         "description": {
            "PL": "Opis do zbioru warunków",
            "EN": "ceqvqEO1AsN92sTa0yn6vtYKc4Wkegfw7P5IQO34hhmtNWPYUKZXF8npJg55qGTUG4unmQPlaqRRvAzuaQLST2RP030V9gbqx5gekGPRnRqwVi03Cs0SDvmZe0jmMNm4lOm2w02kyHA1wtMapqgv3GGtQFTsXBegVFFu3aGlpZyfyWRl4TLSm4rTWMSRC89u2A3mxEAWv1AXn64ouBL4AoqwRGomgeU58ewRWiEwPv55BMmMfa0SxQOfiplqksmQ"
         }
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then validation error response is received

  Scenario: Get condition set (not authorized)
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get condition set (not found)
    Given current authentication token
    Given I request "/api/v1/EN/conditionsets/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get condition set
    Given current authentication token
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP GET
    Then the response code is 200
