Feature: Category tree module

  Scenario: Create category tree
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/trees" with body:
      """
      {
        "code": "TREE_@@random_code@@",
        "name": {
          "de": "Test de",
          "en": "Test en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category_tree"

  Scenario: Create category tree (not authorized)
    When I send a POST request to "/api/v1/en/trees"
    Then the response status code should be 401

  Scenario: Create category for update 1
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/categories" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
          "de": "Test de",
          "en": "Test en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category_1"

  Scenario: Create category for update 2
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/categories" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
          "de": "Test de",
          "en": "Test en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category_2"

  Scenario: Create category tree (no Name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/trees" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@"
      }
      """
    Then the response status code should be 201

  Scenario: Create category tree (empty Name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/trees" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
        }
      }
      """
    Then the response status code should be 201

  Scenario: Create category tree (name with language with empty string value)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/trees" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
          "de": "",
          "en": "Test en"
        }
      }
      """
    Then the response status code should be 400

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Create category tree (name with wrong language code)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a POST request to "/api/v1/en/trees" with body:
#      """
#      {
#        "code": "TREE_CAT_@@random_code@@",
#        "name": {
#          "test": "Test de",
#          "en": "Test en"
#        }
#      }
#      """
#    Then the response status code should be 400

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Create category tree (name with no existing language code)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a POST request to "/api/v1/en/trees" with body:
#      """
#      {
#        "code": "TREE_CAT_@@random_code@@",
#        "name": {
#          "ZZ": "Test de",
#          "en": "Test en"
#        }
#      }
#      """
#    Then the response status code should be 400

  Scenario: Update category tree
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "Test de (changed)",
          "en": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": []
          }
        ]
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (not authorized)
    When I send a PUT request to "/api/v1/en/trees/@category_tree@"
    Then the response status code should be 401

  Scenario: Update category tree (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update category tree (no name field)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
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
    Then the response status code should be 204

  Scenario: Update category tree (empty name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
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
    Then the response status code should be 204

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Update category tree (wrong language code)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
#    """
#      {
#        "name": {
#          "test": "Test de (changed)",
#          "en": "Test en (changed)"
#        },
#        "categories": [
#          {
#            "category_id": "@category_1@",
#            "childrens": []
#          }
#        ]
#      }
#    """
#    Then the response status code should be 400

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Update category tree (incorrect language code)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
#    """
#      {
#        "name": {
#          "ZZ": "Test de (changed)",
#          "en": "Test en (changed)"
#        },
#        "categories": [
#          {
#            "category_id": "@category_1@",
#            "childrens": []
#          }
#        ]
#      }
#    """
#    Then the response status code should be 400

  Scenario: Update category tree (no categories)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "Test de (changed)",
          "en": "Test en (changed)"
        }
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (incorrect category Id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "Test de (changed)",
          "en": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "test",
            "childrens": []
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (empty categotry id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "Test de (changed)",
          "en": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "",
            "childrens": []
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (wrong categories key)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "Test de (changed)",
          "en": "Test en (changed)"
        },
        "categories": [
          {
            "test": "@category_1@",
            "childrens": []
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (with childrens)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "Test de (changed)",
          "en": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": [{"category_id":"@category_2@"}]
          }
        ]
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (no childrens)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "Test de (changed)",
          "en": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@"
          }
        ]
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (with empty childrens category Id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "Test de (changed)",
          "en": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": [{"category_id":""}]
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (with empty childrens wrong key)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "Test de (changed)",
          "en": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": [{"test":"@category_2@"}]
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (empty translation)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de": "",
          "en": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "childrens": []
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Get category tree
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees/@category_tree@"
    Then the response status code should be 200

  Scenario: Get category tree (not authorized)
    When I send a GET request to "/api/v1/en/trees/@category_tree@"
    Then the response status code should be 401

  Scenario: Get category tree (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete category tree (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/trees/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete category tree (not authorized)
    When I send a DELETE request to "/api/v1/en/trees/@category_tree@"
    Then the response status code should be 401

  Scenario: Delete category tree
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/trees/@category_tree@"
    Then the response status code should be 204

  Scenario: Get category trees (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get category trees (not authorized)
    When I send a GET request to "/api/v1/en/trees"
    Then the response status code should be 401

  Scenario: Get category trees
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get category trees (order by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees?field=id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get category trees (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get category trees (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get category trees (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees?field=name&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get category trees (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees?field=name&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get category trees (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get category trees (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees?limit=25&offset=0&filter=code%3DCAT"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get category trees (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/trees?limit=25&offset=0&filter=name%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
