Feature: Category tree module

  Scenario: Create category tree
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TREE_@@random_code@@",
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

  Scenario: Create category for update 1
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
    And remember response param "id" as "category_1"

  Scenario: Create category for update 2
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
    And remember response param "id" as "category_2"

  Scenario: Create category tree (no Name)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TREE_CAT_@@random_code@@"
      }
      """
    When I request "/api/v1/EN/trees" using HTTP POST
    Then created response is received

  Scenario: Create category tree (empty Name)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
        }
      }
      """
    When I request "/api/v1/EN/trees" using HTTP POST
    Then created response is received

  Scenario: Create category tree (name with language with empty string value)
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
    When I request "/api/v1/EN/trees" using HTTP POST
    Then validation error response is received

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Create category tree (name with wrong language code)
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
#    When I request "/api/v1/EN/trees" using HTTP POST
#    Then validation error response is received

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Create category tree (name with no existing language code)
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
#    When I request "/api/v1/EN/trees" using HTTP POST
#    Then validation error response is received

  Scenario: Update category tree
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE (changed)",
          "EN": "Test EN (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
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

  Scenario: Update category tree (no name field)
    Given current authentication token
    Given the request body is:
    """
      {
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": []
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then empty response is received

  Scenario: Update category tree (empty name)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": []
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then empty response is received

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Update category tree (wrong language code)
#    Given current authentication token
#    Given the request body is:
#    """
#      {
#        "name": {
#          "test": "Test DE (changed)",
#          "EN": "Test EN (changed)"
#        },
#        "categories": [
#          {
#            "category_id": "@category_1@",
#            "childrens": []
#          }
#        ]
#      }
#    """
#    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
#    Then validation error response is received

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Update category tree (incorrect language code)
#    Given current authentication token
#    Given the request body is:
#    """
#      {
#        "name": {
#          "ZZ": "Test DE (changed)",
#          "EN": "Test EN (changed)"
#        },
#        "categories": [
#          {
#            "category_id": "@category_1@",
#            "childrens": []
#          }
#        ]
#      }
#    """
#    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
#    Then validation error response is received

  Scenario: Update category tree (no categories)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE (changed)",
          "EN": "Test EN (changed)"
        }
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then empty response is received

  Scenario: Update category tree (incorrect category Id)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE (changed)",
          "EN": "Test EN (changed)"
        },
        "categories": [
          {
            "category_id": "test",
            "childrens": []
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then validation error response is received

  Scenario: Update category tree (empty categotry id)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE (changed)",
          "EN": "Test EN (changed)"
        },
        "categories": [
          {
            "category_id": "",
            "childrens": []
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then validation error response is received

  Scenario: Update category tree (wrong categories key)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE (changed)",
          "EN": "Test EN (changed)"
        },
        "categories": [
          {
            "test": "@category_1@",
            "childrens": []
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then validation error response is received

  Scenario: Update category tree (with childrens)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE (changed)",
          "EN": "Test EN (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": [{"category_id":"@category_2@"}]
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then empty response is received

  Scenario: Update category tree (no childrens)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE (changed)",
          "EN": "Test EN (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@"
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then empty response is received

  Scenario: Update category tree (with empty childrens category Id)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE (changed)",
          "EN": "Test EN (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": [{"category_id":""}]
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then validation error response is received

  Scenario: Update category tree (with empty childrens wrong key)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "Test DE (changed)",
          "EN": "Test EN (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": [{"test":"@category_2@"}]
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then validation error response is received

  Scenario: Update category tree (empty translation)
    Given current authentication token
    Given the request body is:
    """
      {
        "name": {
          "DE": "",
          "EN": "Test EN (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": []
          }
        ]
      }
    """
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP PUT
    Then validation error response is received

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

  Scenario: Delete category tree (not found)
    Given current authentication token
    When I request "/api/v1/EN/trees/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete category tree (not authorized)
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete category tree
    Given current authentication token
    When I request "/api/v1/EN/trees/@category_tree@" using HTTP DELETE
    Then empty response is received

  Scenario: Get category trees (order by name)
    Given current authentication token
    When I request "/api/v1/EN/trees?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (not authorized)
    When I request "/api/v1/EN/trees" using HTTP GET
    Then unauthorized response is received

  Scenario: Get category trees
    Given current authentication token
    When I request "/api/v1/EN/trees" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (order by id)
    Given current authentication token
    When I request "/api/v1/EN/trees?field=id" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (order by code)
    Given current authentication token
    When I request "/api/v1/EN/trees?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (order by name)
    Given current authentication token
    When I request "/api/v1/EN/trees?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/trees?field=name&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/trees?field=name&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/trees?limit=25&offset=0&filter=name%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/trees?limit=25&offset=0&filter=code%3DCAT" using HTTP GET
    Then grid response is received

  Scenario: Get category trees (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/trees?limit=25&offset=0&filter=name%3D1" using HTTP GET
    Then grid response is received
