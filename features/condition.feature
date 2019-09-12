Feature: Condition

  Scenario: Get attribute groups dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/attributes/groups" using HTTP GET
    Then the response code is 200
    And remember first attribute group as "condition_attribute_group"

  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "CONDITION_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": ["@condition_attribute_group@"],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "condition_text_attribute"

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

  Scenario: Create condition set (without description)
    Given current authentication token
    Given the request body is:
      """
      {
         "code": "CONDITION_2_@@random_uuid@@",
         "name": {
            "PL": "Zbiór warunków",
            "EN": "Condition set"
         }
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received

  Scenario: Create condition set (only code)
    Given current authentication token
    Given the request body is:
      """
      {
         "code": "CONDITION_3_@@random_uuid@@"
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received

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
            "PL": "ceqvqEO1AsN92sTa0yn6vtYKc4Wkegfw7P5IQO34hhmtNWPYUKZXF8npJg55qGTUG4unmQPlaqRRvAzuaQLST2RP030V9gbqx5gekGPRnRqwVi03Cs0SDvmZe0jmMNm4lOm2w02kyHA1wtMapqgv3GGtQFTsXBegVFFu3aGlpZyfyWRl4TLSm4rTWMSRC89u2A3mxEAWv1AXn64ouBL4AoqwRGomgeU58ewRWiEwPv55BMmMfa0SxQOfiplqksmQ",
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

  Scenario: Update condition set
    Given current authentication token
    Given the request body is:
      """
      {
         "name": {
            "PL": "Zbiór warunków (changed)",
            "EN": "Condition set (changed)"
         },
         "description": {
            "PL": "Opis do zbioru warunków (changed)",
            "EN": "Condition set description (changed)"
         },
         "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "parameters": {
                "attribute": "@condition_text_attribute@"
              }
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then empty response is received

  Scenario: Update condition set (without conditions)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": {
            "PL": "Zbiór warunków (changed)",
            "EN": "Condition set (changed)"
         },
         "description": {
            "PL": "Opis do zbioru warunków (changed)",
            "EN": "Condition set description (changed)"
         }
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (without name)
    Given current authentication token
    Given the request body is:
      """
      {
         "description": {
            "PL": "Opis do zbioru warunków (changed)",
            "EN": "Condition set description (changed)"
         },
         "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "parameters": {
                "code": "@condition_text_attribute@"
              }
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
    Then validation error response is received

  Scenario: Update condition set (without description)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": {
            "PL": "Zbiór warunków (changed)",
            "EN": "Condition set (changed)"
         },
         "conditions": [
            {
              "type": "ATTRIBUTE_EXISTS_CONDITION",
              "parameters": {
                "attribute": "@condition_text_attribute@"
              }
            }
         ]
      }
      """
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP PUT
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

  Scenario: Delete condition set (not authorized)
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete condition set (not found)
    Given current authentication token
    Given I request "/api/v1/EN/conditionsets/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete condition set
    Given current authentication token
    Given I request "/api/v1/EN/conditionsets/@conditionset@" using HTTP DELETE
    Then empty response is received

  Scenario: Get condition sets
    Given current authentication token
    When I request "/api/v1/EN/conditionsets" using HTTP GET
    Then grid response is received

  Scenario: Get condition sets (not authorized)
    When I request "/api/v1/EN/conditionsets" using HTTP GET
    Then unauthorized response is received

  Scenario: Get condition sets (order by code)
    Given current authentication token
    When I request "/api/v1/EN/conditionsets?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get condition sets (order by name)
    Given current authentication token
    When I request "/api/v1/EN/conditionsets?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get condition sets (order by description)
    Given current authentication token
    When I request "/api/v1/EN/conditionsets?field=description" using HTTP GET
    Then grid response is received

  Scenario: Get condition sets (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/conditionsets?limit=25&offset=0&filter=code%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Get condition sets (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/conditionsets?limit=25&offset=0&filter=name%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Get condition sets (filter by description)
    Given current authentication token
    When I request "/api/v1/EN/conditionsets?limit=25&offset=0&filter=description%3Dsuper" using HTTP GET
    Then grid response is received
