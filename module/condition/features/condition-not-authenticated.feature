Feature: Condition module

  Scenario: Get product belong category tree exists condition
    When I send a GET request to "/api/v1/en_GB/conditions/PRODUCT_BELONG_CATEGORY_TREE_CONDITION"
    Then the response status code should be 401

  Scenario: Get conditions dictionary (not authenticated)
    When I send a GET request to "/api/v1/en_GB/dictionary/conditions"
    Then the response status code should be 401

  Scenario: Create condition set (not authenticated)
    Given I send a POST request to "/api/v1/en_GB/conditionsets"
    Then the response status code should be 401

  Scenario: Get condition set (not authenticated)
    Given I send a GET request to "/api/v1/en_GB/conditionsets/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Delete condition set (not authenticated)
    Given I send a DELETE request to "/api/v1/en_GB/conditionsets/@@random_uuid@@"
    Then the response status code should be 401
