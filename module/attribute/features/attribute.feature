Feature: Attribute module

  Scenario: Get attribute types dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/attributes/types" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute types dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/attributes/types" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute groups dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/attributes/groups" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attributes (order by id)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=id" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by label)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=label" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=label&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=label&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by label)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=label%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by id)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=id%3DEN" using HTTP GET
    Then grid response is received

  Scenario: Delete attribute (not found)
    Given current authentication token
    When I request "/api/v1/EN/attributes/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Create attribute (not authorized)
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then unauthorized response is received

  Scenario: Get attributes (order by code)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by label)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=label" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by type)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=type" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by multilingual)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=multilingual" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by index)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=index%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=code%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by label)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=label%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by type)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=type%3DTEXT" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by groups)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=groups%3Dd653cce6-66fb-4772-800b-281af35fc5bc" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (not authorized)
    When I request "/api/v1/EN/attributes" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute image formats dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/image_format" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute image formats dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/image_format" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute units dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/units" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute units dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/units" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute currencies dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/currencies" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute currencies dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/currencies" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute date formats dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/date_format" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute currencies date formats (not authorized)
    When I request "/api/v1/EN/dictionary/date_format" using HTTP GET
    Then unauthorized response is received
