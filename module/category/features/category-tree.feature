Feature: Category tree module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create category tree
    When I send a POST request to "/api/v1/en_GB/trees" with body:
      """
      {
        "code": "TREE_@@random_code@@",
        "name": {
          "de_DE": "Test de",
          "en_GB": "Test en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category_tree"

  Scenario: Get 1 category id
    When I send a GET request to "/api/v1/en_GB/categories?filter=name=Category_1&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "category_1"

  Scenario: Get 2 category id
    When I send a GET request to "/api/v1/en_GB/categories?filter=name=Category_2&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "category_2"

  Scenario: Create category tree (no Name)
    When I send a POST request to "/api/v1/en_GB/trees" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@"
      }
      """
    Then the response status code should be 201

  Scenario: Create category tree (empty Name)
    When I send a POST request to "/api/v1/en_GB/trees" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
        }
      }
      """
    Then the response status code should be 201

  Scenario: Create category tree (name with language with empty string value)
    When I send a POST request to "/api/v1/en_GB/trees" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
          "de_DE": "",
          "en_GB": "Test en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update category tree
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "Test de (changed)",
          "en_GB": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "children": []
          }
        ]
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (not found)
    When I send a PUT request to "/api/v1/en_GB/trees/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update category tree (no name field)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "categories": [
          {
            "category_id": "@category_1@",
            "children": []
          }
        ]
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (empty name)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "children": []
          }
        ]
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (no categories)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "Test de (changed)",
          "en_GB": "Test en (changed)"
        }
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (incorrect category Id)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "Test de (changed)",
          "en_GB": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "test",
            "children": []
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (empty category id)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "Test de (changed)",
          "en_GB": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "",
            "children": []
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (wrong categories key)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "Test de (changed)",
          "en_GB": "Test en (changed)"
        },
        "categories": [
          {
            "test": "@category_1@",
            "children": []
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (with children)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "Test de (changed)",
          "en_GB": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "children": [{"category_id":"@category_2@"}]
          }
        ]
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (no children)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "Test de (changed)",
          "en_GB": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@"
          }
        ]
      }
    """
    Then the response status code should be 204

  Scenario: Update category tree (with empty children category Id)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "Test de (changed)",
          "en_GB": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "children": [{"category_id":""}]
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (with empty children wrong key)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "Test de (changed)",
          "en_GB": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "children": [{"test":"@category_2@"}]
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Update category tree (empty translation)
    When I send a PUT request to "/api/v1/en_GB/trees/@category_tree@" with body:
    """
      {
        "name": {
          "de_DE": "",
          "en_GB": "Test en (changed)"
        },
        "categories": [
          {
            "category_id": "@category_1@",
            "children": []
          }
        ]
      }
    """
    Then the response status code should be 400

  Scenario: Get category tree
    When I send a GET request to "/api/v1/en_GB/trees/@category_tree@"
    Then the response status code should be 200

  Scenario: Get category tree (not found)
    When I send a GET request to "/api/v1/en_GB/trees/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete category tree (not found)
    When I send a DELETE request to "/api/v1/en_GB/trees/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete category tree
    When I send a DELETE request to "/api/v1/en_GB/trees/@category_tree@"
    Then the response status code should be 204
