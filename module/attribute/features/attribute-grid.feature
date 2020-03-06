Feature: Attribute grid

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get attributes (order by id)
    And I send a "GET" request to "/api/v1/EN/attributes?field=id"
    Then the response status code should be 200

  Scenario: Get attributes (order by label)
    And I send a "GET" request to "/api/v1/EN/attributes?field=label"
    Then the response status code should be 200

  Scenario: Get attributes (order ASC)
    And I send a "GET" request to "/api/v1/EN/attributes?field=label&order=ASC"
    Then the response status code should be 200

  Scenario: Get attributes (order DESC)
    And I send a "GET" request to "/api/v1/EN/attributes?field=label&order=DESC"
    Then the response status code should be 200

  Scenario: Get attributes (filter by label)
    And I send a "GET" request to "/api/v1/EN/attributes?limit=25&offset=0&filter=label%3Dasd"
    Then the response status code should be 200

  Scenario: Get attributes (filter by id)
    And I send a "GET" request to "/api/v1/EN/attributes?limit=25&offset=0&filter=id%3DEN"
    Then the response status code should be 200

  Scenario: Get attributes (order by code)
    And I send a "GET" request to "/api/v1/EN/attributes?field=code"
    Then the response status code should be 200

  Scenario: Get attributes (order by label)
    And I send a "GET" request to "/api/v1/EN/attributes?field=label"
    Then the response status code should be 200

  Scenario: Get attributes (order by type)
    And I send a "GET" request to "/api/v1/EN/attributes?field=type"
    Then the response status code should be 200

  Scenario: Get attributes (order by multilingual)
    And I send a "GET" request to "/api/v1/EN/attributes?field=multilingual"
    Then the response status code should be 200

  Scenario: Get attributes (filter by index)
    And I send a "GET" request to "/api/v1/EN/attributes?limit=25&offset=0&filter=index%3Dasd"
    Then the response status code should be 200

  Scenario: Get attributes (filter by code)
    And I send a "GET" request to "/api/v1/EN/attributes?limit=25&offset=0&filter=code%3Dasd"
    Then the response status code should be 200

  Scenario: Get attributes (filter by label)
    And I send a "GET" request to "/api/v1/EN/attributes?limit=25&offset=0&filter=label%3Dasd"
    Then the response status code should be 200

  Scenario: Get attributes (filter by type)
    And I send a "GET" request to "/api/v1/EN/attributes?limit=25&offset=0&filter=type%3DTEXT"
    Then the response status code should be 200

  Scenario: Get attributes (filter by groups)
    And I send a "GET" request to "/api/v1/EN/attributes?limit=25&offset=0&filter=groups%3Dd653cce6-66fb-4772-800b-281af35fc5bc"
    Then the response status code should be 200
