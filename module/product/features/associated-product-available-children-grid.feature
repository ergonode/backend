Feature: Variable product available children grid feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "template_id"

  Scenario Outline: Create SELECT attribute <attribute>
    Given remember param "select_<attribute>" with value "select_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@select_<attribute>@",
        "type": "SELECT",
        "scope": "local"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<attribute>"
    Examples:
      | attribute             |
      | select_attribute_1_id |
      | select_attribute_2_id |

  Scenario Outline: Create option <name> for select <code>
    When I send a "POST" request to "/api/v1/en_GB/attributes/@<attribute>_id@/options" with body:
      """
      {
        "code": "<value>",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<name>"
    Examples:
      | name                 | attribute          | value  |
      | select_1_option_1_id | select_attribute_1 | key_1  |
      | select_1_option_2_id | select_attribute_1 | key_12 |
      | select_2_option_1_id | select_attribute_2 | key_13 |
      | select_2_option_2_id | select_attribute_2 | key_14 |

  Scenario Outline: Create <product> product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "<sku>_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<product>"
    Examples:
      | product      | sku   |
      | product_1_id | sku_1 |
      | product_2_id | sku_2 |
      | product_3_id | sku_3 |

  Scenario Outline: Create variable <product> product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "VARIABLE-PRODUCT",
        "templateId": "@template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<product>"
    Examples:
      | product               |
      | variable_product_1_id |
      | variable_product_2_id |

  Scenario Outline: Create grouping <product> product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "GROUPING-PRODUCT",
        "templateId": "@template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<product>"
    Examples:
      | product               |
      | grouping_product_1_id |

  Scenario Outline: Get <product> product sku
    When I send a GET request to "/api/v1/en_GB/products/<id>"
    Then the response status code should be 200
    And store response param "sku" as "<sku>"
    Examples:
      | id             | sku           |
      | @product_1_id@ | product_1_sku |
      | @product_1_id@ | product_1_sku |
      | @product_2_id@ | product_2_sku |
      | @product_3_id@ | product_3_sku |

  Scenario: Add bind attribute to variable product 1
    When I send a POST request to "/api/v1/en_GB/products/@variable_product_1_id@/binding" with body:
      """
      {
        "bind_id": "@select_attribute_1_id@"
      }
      """
    Then the response status code should be 201

  Scenario Outline: Add bind attributes to product 2
    When I send a POST request to "/api/v1/en_GB/products/@variable_product_2_id@/binding" with body:
      """
      {
        "bind_id": "<attribute>"
      }
      """
    Then the response status code should be 201
    Examples:
      | attribute               |
      | @select_attribute_1_id@ |
      | @select_attribute_2_id@ |

  Scenario: Add product 1 option value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_1_id@",
            "payload": [
              {
                "id": "@select_attribute_1_id@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": "@select_1_option_1_id@"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Add product 1 option value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_1_id@",
            "payload": [
              {
                "id": "@select_attribute_2_id@",
                "values" : [
                  {
                    "language": "en_GB",
                     "value": "@select_2_option_1_id@"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Add product 2 option value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_2_id@",
            "payload": [
              {
                "id": "@select_attribute_1_id@",
                "values" : [
                  {
                    "language": "en_GB",
                     "value": "@select_1_option_2_id@"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Add children to variable product 1
    When I send a POST request to "/api/v1/en_GB/products/@variable_product_1_id@/children" with body:
         """
      {
       "child_id": "@product_1_id@"
      }
      """
    Then the response status code should be 204

  Scenario: Get variable product 1 children and available products
    When I send a GET request to "/api/v1/en_GB/products/@variable_product_1_id@/children-and-available-products?field=sku&order=ASC"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id    | id             |
      | columns[0].type  | TEXT           |
      | collection[0].id | @product_1_id@ |
      | collection[1].id | @product_2_id@ |
      | info.filtered    | 2              |

  Scenario: Get variable product 1 children and available products (filtered by sku)
    When I send a GET request to "/api/v1/en_GB/products/@variable_product_1_id@/children-and-available-products?field=sku&filter=sku=@product_1_sku@&order=ASC"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id    | id                             |
      | columns[0].type  | TEXT                           |
      | columns[6].id    | @select_select_attribute_1_id@ |
      | collection[0].id | @product_1_id@                 |
      | info.filtered    | 1                              |

  Scenario: Get variable product 2 children and available products
    When I send a GET request to "/api/v1/en_GB/products/@variable_product_2_id@/children-and-available-products?field=sku&order=ASC"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id    | id             |
      | columns[0].type  | TEXT           |
      | collection[0].id | @product_1_id@ |
      | info.filtered    | 1              |

  Scenario: Get variable product 1 children and available products (filtered by attached true)
    When I send a GET request to "/api/v1/en_GB/products/@variable_product_1_id@/children-and-available-products?field=sku&filter=attached=true&order=ASC"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id    | id             |
      | columns[0].type  | TEXT           |
      | collection[0].id | @product_1_id@ |
      | info.filtered    | 1              |

  Scenario: Get variable product 1 children and available products (filtered by attached true)
    When I send a GET request to "/api/v1/en_GB/products/@variable_product_1_id@/children-and-available-products?field=sku&filter=attached=false&order=ASC"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id    | id             |
      | columns[0].type  | TEXT           |
      | collection[0].id | @product_2_id@ |
      | info.filtered    | 1              |

  Scenario Outline: Get attributes (filter by <field>)
    And I send a "GET" request to "/api/v1/en_GB/attributes?limit=25&offset=0&filter=<field>=<value>"
    Then the response status code should be 200
    Examples:
      | field         | value |
      | id            | abc   |
      | sku           | abc   |
      | template      | abc   |
      | default_label | abc   |
      | attached      | abc   |

  Scenario: Get grouping product children and available products
    When I send a GET request to "/api/v1/en_GB/products/@grouping_product_1_id@/children-and-available-products?field=sku&order=ASC"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id   | id   |
      | columns[0].type | TEXT |

  Scenario: Get simple product children and available products
    When I send a GET request to "/api/v1/en_GB/products/@product_1_id@/children-and-available-products?field=sku&order=ASC"
    Then the response status code should be 404
