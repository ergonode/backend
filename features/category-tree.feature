Feature: Category tree module

  Scenario: Create category tree
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/trees" using HTTP POST
    Then created response is received
    And remember response param "id" as "category_tree"

  Scenario: Create category tree (not authorized)
    When I request "/api/v1/EN/trees" using HTTP POST
    Then unauthorized response is received

  Scenario: Create category for update
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/categories" using HTTP POST
    Then created response is received
    And remember response param "id" as "tree_category"

  Scenario: Update category tree
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        },
        "categories": [
          {
            "category_id": "@tree_category@",
            "childrens": []
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then empty response is received

  Scenario: Update category tree (not authorized)
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update category tree (not found)
    Given current authentication token
    When I request "/api/v1/EN/trees/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get category tree
    Given current authentication token
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP GET
    Then the response code is 200

  Scenario: Get category tree (not authorized)
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get category tree (not found)
    Given current authentication token
    When I request "/api/v1/EN/trees/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get category trees (order by name)
    Given current authentication token
    When I request "/api/v1/EN/trees?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (not authorized)
    When I request "/api/v1/EN/trees" using HTTP GET
    Then unauthorized response is received

  # TODO Check add category to category tree action
  # TODO Check create category tree action with all incorrect possibilities
  # TODO Check update category tree action with all incorrect possibilities
