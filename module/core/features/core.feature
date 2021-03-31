Feature: Core module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get languages
    When I send a GET request to "/api/v1/en_GB/dictionary/languages"
    Then the response status code should be 200

  Scenario: Get translation language
    When I send a GET request to "/api/v1/en_GB/languages/en_GB"
    Then the response status code should be 200

  Scenario: Get translation language (not found)
    When I send a GET request to "/api/v1/en_GB/languages/ZZ"
    Then the response status code should be 404

  Scenario: Get languages (order by code)
    When I send a GET request to "/api/v1/en_GB/languages?field=code"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (order by label)
    When I send a GET request to "/api/v1/en_GB/languages?field=label"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (order by active)
    When I send a GET request to "/api/v1/en_GB/languages?field=active"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (order ASC)
    When I send a GET request to "/api/v1/en_GB/languages?field=label&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (order DESC)
    When I send a GET request to "/api/v1/en_GB/languages?field=label&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (filter by code)
    When I send a GET request to "/api/v1/en_GB/languages?limit=25&offset=0&filter=code%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (filter by label)
    When I send a GET request to "/api/v1/en_GB/languages?limit=25&offset=0&filter=label%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (filter by iso)
    When I send a GET request to "/api/v1/en_GB/languages?limit=25&offset=0&filter=iso%3Den"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (filter by active)
    When I send a GET request to "/api/v1/en_GB/languages?limit=25&offset=0&filter=active%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Update language
    When I send a PUT request to "/api/v1/en_GB/languages" with body:
    """
      {
        "collection":["en_GB","pl_PL","cs_CZ","fr_FR","uk_UA","de_DE"]
      }
    """
    Then the response status code should be 204

  Scenario: Update language (wrong active - bad request)
    When I send a PUT request to "/api/v1/en_GB/languages" with body:
    """
      {
         "collection":[
            {
               "code":"en"
            }
         ]
      }
    """
    Then the response status code should be 400

  Scenario: Update language (wrong code - bad request)
    When I send a PUT request to "/api/v1/en_GB/languages" with body:
    """
      {
         "collection":[
            {
               "code":"ZZ"
            }
         ]
      }
    """
    Then the response status code should be 400

  Scenario: Update language (wrong structure)
    When I send a PUT request to "/api/v1/en_GB/languages" with body:
    """
    {
      "code": "ZZ"
    }
    """
    Then the response status code should be 400


  Scenario: Get language autocomplete
    When I send a GET request to "/api/v1/en_GB/languages/autocomplete"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "core/features/language.json"

  Scenario: Get language autocomplete (order by code)
    When I send a GET request to "/api/v1/en_GB/languages/autocomplete?field=code"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "core/features/language.json"

  Scenario: Get language autocomplete (order by label)
    When I send a GET request to "/api/v1/en_GB/languages/autocomplete?field=label"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "core/features/language.json"

  Scenario: Get language autocomplete (order by active)
    When I send a GET request to "/api/v1/en_GB/languages/autocomplete?field=active"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "core/features/language.json"

  Scenario: Get language autocomplete (order ASC)
    When I send a GET request to "/api/v1/en_GB/languages/autocomplete?field=label&order=ASC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "core/features/language.json"

  Scenario: Get language autocomplete (order DESC)
    When I send a GET request to "/api/v1/en_GB/languages/autocomplete?field=label&order=DESC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "core/features/language.json"

  Scenario: Get language autocomplete (search f limit 1)
    When I send a GET request to "/api/v1/en_GB/languages/autocomplete?search=f&limit=1"
    And the JSON should be valid according to the schema "core/features/language.json"
