Feature: Attribute module

  Scenario: Get attribute groups autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/attributes/groups/autocomplete"
    Then the response status code should be 401

  Scenario: Get attribute groups autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/attributes/groups/autocomplete"
    Then the response status code should be 401

  Scenario: Get attribute autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/attributes/autocomplete"
    Then the response status code should be 401
