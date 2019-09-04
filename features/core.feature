Feature: Core module

  Scenario: Get languages
    Given current authentication token
    When I request "/api/v1/EN/dictionary/languages" using HTTP GET
    Then the response code is 200

  Scenario: Get languages (not authorized)
    When I request "/api/v1/EN/dictionary/languages" using HTTP GET
    Then unauthorized response is received

  Scenario: Get translation language (not authorized)
    When I request "/api/v1/EN/languages/EN" using HTTP GET
    Then unauthorized response is received

  Scenario: Get translation language
    Given current authentication token
    When I request "/api/v1/EN/languages/EN" using HTTP GET
    Then the response code is 200

  Scenario: Get translation language (not found)
    Given current authentication token
    When I request "/api/v1/EN/languages/ZZ" using HTTP GET
    Then not found response is received

  Scenario: Update language (not authorized)
    When I request "/api/v1/EN/languages" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update language
    Given current authentication token
    Given the request body is:
    """
     {
       "collection":[
          {
             "code":"EN",
             "active":true
          }
       ]
     }
    """
    When I request "/api/v1/EN/languages" using HTTP PUT
    Then the response code is 204

  Scenario: Update language (wrong active - bad request)
    Given current authentication token
    Given the request body is:
    """
      {
         "collection":[
            {
               "code":"EN",
               "active":"test"
            }
         ]
      }
    """
    When I request "/api/v1/EN/languages" using HTTP PUT
    Then validation error response is received

  Scenario: Update language (wrong code - bad request)
    Given current authentication token
    Given the request body is:
    """
      {
         "collection":[
            {
               "code":"ZZ",
               "active":true
            }
         ]
      }
    """
    When I request "/api/v1/EN/languages" using HTTP PUT
    Then validation error response is received

  Scenario: Update language (wrong structure - bad request)
    Given current authentication token
    Given the request body is:
    """
    {
      "code": "ZZ",
      "active": true
    }
    """
    When I request "/api/v1/EN/languages" using HTTP PUT
    Then validation error response is received
