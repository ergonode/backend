Feature: Category module

  Scenario: Create category
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/categories" using HTTP POST
    Then created response is received
    And remember response param "id" as "category"

  Scenario: Create category (not authorized)
    When I request "/api/v1/EN/categories" using HTTP POST
    Then unauthorized response is received

  Scenario: Create category (no Name)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TREE_CAT_@@random_code@@"
      }
      """
    When I request "/api/v1/EN/categories" using HTTP POST
    Then created response is received

  Scenario: Create category (empty Name)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
        }
      }
      """
    When I request "/api/v1/EN/categories" using HTTP POST
    Then created response is received

  Scenario: Create category (name with language with empty string value)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
          "DE": "",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/categories" using HTTP POST
    Then validation error response is received

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Create category (name with wrong language code)
#    Given current authentication token
#    Given the request body is:
#      """
#      {
#        "code": "TREE_CAT_@@random_code@@",
#        "name": {
#          "test": "Test DE",
#          "EN": "Test EN"
#        }
#      }
#      """
#    When I request "/api/v1/EN/categories" using HTTP POST
#    Then validation error response is received

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Create category (name with no existing language code)
#    Given current authentication token
#    Given the request body is:
#      """
#      {
#        "code": "TREE_CAT_@@random_code@@",
#        "name": {
#          "ZZ": "Test DE",
#          "EN": "Test EN"
#        }
#      }
#      """
#    When I request "/api/v1/EN/categories" using HTTP POST
#    Then validation error response is received

  Scenario: Update category
    Given current authentication token
    Given the request body is:
      """
      {
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
    Then empty response is received

  Scenario: Update category (not authorized)
    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update category (not found)
    Given current authentication token
    When I request "/api/v1/EN/categories/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update category (empty name)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": {
        }
      }
      """
    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
    Then empty response is received

  Scenario: Update category (wrong parameter)
    Given current authentication token
    Given the request body is:
      """
      {
        "test": {
        }
      }
      """
    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
    Then validation error response is received

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Update category (wrong language code)
#    Given current authentication token
#    Given the request body is:
#      """
#      {
#        "name": {
#          "test": "Test DE (changed)",
#          "EN": "Test EN (changed)"
#        }
#      }
#      """
#    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
#    Then validation error response is received

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Update category (incorrect language code)
#    Given current authentication token
#    Given the request body is:
#      """
#      {
#        "name": {
#          "ZZ": "Test DE (changed)",
#          "EN": "Test EN (changed)"
#        }
#      }
#      """
#    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
#    Then validation error response is received

  Scenario: Update category (empty translation)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": {
          "DE": "",
          "EN": "Test EN (changed)"
        }
      }
      """
    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
    Then validation error response is received

  Scenario: Get category
    Given current authentication token
    When I request "/api/v1/EN/categories/@category@" using HTTP GET
    Then the response code is 200

  Scenario: Get category (not authorized)
    When I request "/api/v1/EN/categories/@category@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get category (not found)
    Given current authentication token
    When I request "/api/v1/EN/categories/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Delete category (not authorized)
    When I request "/api/v1/EN/categories/@category@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete category (not found)
    Given current authentication token
    When I request "/api/v1/EN/categories/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete category
    Given current authentication token
    When I request "/api/v1/EN/categories/@category@" using HTTP DELETE
    Then empty response is received

  Scenario: Get categories (order by code)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order by name)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order by elements_count)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=elements_count" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order by sequence)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=sequence" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=name&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=name&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get categories (filter by sequence)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=sequence%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get categories (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=name%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get categories (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=code%3DCAT" using HTTP GET
    Then grid response is received

  Scenario: Get categories (filter by elements_count)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get categories (filter by elements_count = 0)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count=0" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get categories (filter by elements_count = 9999999)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count=9999999" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get categories (filter by elements_count >= 9999999)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count>=9999999" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get categories (filter by elements_count <= 9999999)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count<=9999999" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get categories (filter by elements_count >= 888888 <= 9999999)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count>=8888888;elements_count<=9999999" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get categories (not authorized)
    When I request "/api/v1/EN/categories" using HTTP GET
    Then unauthorized response is received
