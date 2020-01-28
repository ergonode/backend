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

  Scenario: Get languages (order by code)
    Given current authentication token
    When I request "/api/v1/EN/languages?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get languages (order by name)
    Given current authentication token
    When I request "/api/v1/EN/languages?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get languages (order by active)
    Given current authentication token
    When I request "/api/v1/EN/languages?field=active" using HTTP GET
    Then grid response is received

  Scenario: Get languages (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/languages?field=name&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get languages (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/languages?field=name&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get languages (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/languages?limit=25&offset=0&filter=code%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get languages (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/languages?limit=25&offset=0&filter=name%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get languages (filter by iso)
    Given current authentication token
    When I request "/api/v1/EN/languages?limit=25&offset=0&filter=iso%3DEN" using HTTP GET
    Then grid response is received

  Scenario: Get languages (filter by active)
    Given current authentication token
    When I request "/api/v1/EN/languages?limit=25&offset=0&filter=active%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get languages (not authorized)
    When I request "/api/v1/EN/languages" using HTTP GET
    Then unauthorized response is received

  Scenario: Update language (not authorized)
    When I request "/api/v1/EN/languages" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update language
    Given current authentication token
    Given the request body is:
    """
      {
        "collection":["EN","PL","SQ"]
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
               "code":"EN"
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
               "code":"ZZ"
            }
         ]
      }
    """
    When I request "/api/v1/EN/languages" using HTTP PUT
    Then validation error response is received

  Scenario: Update language (wrong structure)
    Given current authentication token
    Given the request body is:
    """
    {
      "code": "ZZ"
    }
    """
    When I request "/api/v1/EN/languages" using HTTP PUT
    Then validation error response is received


  Scenario: Get language autocomplete
    Given current authentication token
    When I request "/api/v1/EN/language/autocomplete" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"id"/
    """

  Scenario: Get language autocomplete (not authorized)
    When I request "/api/v1/EN/language/autocomplete" using HTTP GET
    Then unauthorized response is received

  Scenario: Get language autocomplete (order by code)
    Given current authentication token
    When I request "/api/v1/EN/language/autocomplete?field=code" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"id"/
    """

  Scenario: Get language autocomplete (order by name)
    Given current authentication token
    When I request "/api/v1/EN/language/autocomplete?field=name" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"id"/
    """

  Scenario: Get language autocomplete (order by active)
    Given current authentication token
    When I request "/api/v1/EN/language/autocomplete?field=active" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"id"/
    """

  Scenario: Get language autocomplete (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/language/autocomplete?field=name&order=ASC" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"id"/
    """

  Scenario: Get language autocomplete (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/language/autocomplete?field=name&order=DESC" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"id"/
    """

  Scenario: Get language autocomplete (search f limit 1)
    Given current authentication token
    When I request "/api/v1/EN/language/autocomplete?search=f&limit=1" using HTTP GET
    Then the response code is 200
    And the response body is a JSON array of length 1
    And the response body matches:
    """
  /"name": [\n[ ]*"F"*/
    """
