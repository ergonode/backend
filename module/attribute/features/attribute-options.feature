Feature: Attribute options module

  Scenario: Create select attribute with option
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SELECT_@@random_code@@",
        "type": "SELECT",
        "groups": [],
        "multilingual": true,
        "options": [
          {
            "key": "key_1",
            "value": {
              "PL": "Option PL 1",
              "EN": "Option EN 1"
            }
          }
        ]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "select_attribute"

  Scenario: Create select attribute with duplicated options
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SELECT_@@random_code@@",
        "type": "SELECT",
        "groups": [],
        "multilingual": true,
        "options": [
          {
            "key": "key_1",
            "value": {
              "PL": "Option PL 1",
              "EN": "Option EN 1"
            }
          },
          {
            "key": "key_1",
            "value": {
              "PL": "Option PL 1",
              "EN": "Option EN 1"
            }
          }
        ]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then validation error response is received

  Scenario: Update select attribute with duplicated options
    Given current authentication token
    Given the request body is:
      """
      {
       "options": [
    {
      "key": "key_1",
      "value": {
        "PL": "Option PL 1",
        "EN": "Option EN 1"
      }
    },
    {
      "key": "key_1",
      "value": {
        "PL": "Option PL 1",
        "EN": "Option EN 1"
      }
    }
  ]
      }
      """
    When I request "/api/v1/EN/attributes/@select_attribute@" using HTTP PUT
    Then validation error response is received

  Scenario: Create multiselect attribute with option
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "MULTISELECT_@@random_code@@",
          "type": "MULTI_SELECT",
          "groups": [],
          "multilingual": true,
            "options": [
    {
      "key": "key_1",
      "value": {
        "PL": "Option PL 1",
        "EN": "Option EN 1"
        }
        }
  ]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "multiselect_attribute"

  Scenario: Create multiselect attribute with duplicated options
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "MULTISELECT_@@random_code@@",
          "type": "MULTI_SELECT",
          "groups": [],
          "multilingual": true,
            "options": [
    {
      "key": "key_1",
      "value": {
        "PL": "Option PL 1",
        "EN": "Option EN 1"
      }
    },
    {
      "key": "key_1",
      "value": {
        "PL": "Option PL 1",
        "EN": "Option EN 1"
      }
    }
  ]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then validation error response is received

  Scenario: Update multiselect attribute with duplicated options
    Given current authentication token
    Given the request body is:
      """
      {
       "options": [
    {
      "key": "key_1",
      "value": {
        "PL": "Option PL 1",
        "EN": "Option EN 1"
      }
    },
    {
      "key": "key_1",
      "value": {
        "PL": "Option PL 1",
        "EN": "Option EN 1"
      }
    }
  ]
      }
      """
    When I request "/api/v1/EN/attributes/@multiselect_attribute@" using HTTP PUT
    Then validation error response is received
