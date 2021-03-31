Feature: Product edit feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Multimedia upload image
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value           |
      | upload | @image/test.jpg |
    Then the response status code should be 201
    And store response param "id" as "multimedia_id"

  Scenario Outline: Create <type> attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "<name>_@@random_code@@",
        "type": "<type>",
        "scope": "local",
        "parameters": <parameters>
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<code>_id"
    Examples:
      | name                                                                                  | code                   | type         | parameters              |
      | TEXT                                                                                  | text_attribute         | TEXT         | null                    |
      | LONG_CODE_ATTRIBUTE_1234567890_1234567890_1234567890_1234567890_1234567890_1234567890 | text_attribute_long    | TEXT         | null                    |
      | SELECT                                                                                | select_attribute       | SELECT       | null                    |
      | MULTI_SELECT                                                                          | multi_select_attribute | MULTI_SELECT | null                    |
      | DATE                                                                                  | date_attribute         | DATE         | {"format":"yyyy-MM-dd"} |
      | NUMERIC                                                                               | numeric_attribute      | NUMERIC      | null                    |
      | PRICE                                                                                 | price_attribute        | PRICE        | {"currency": "PLN"}     |
      | IMAGE                                                                                 | image_attribute        | IMAGE        | null                    |
      | GALLERY                                                                               | gallery_attribute      | GALLERY      | null                    |
      | FILE                                                                                  | file_attribute         | GALLERY      | null                    |

  Scenario Outline: Create option <name> for select <code>
    When I send a "POST" request to "/api/v1/en_GB/attributes/@<code>_id@/options" with body:
      """
      {
        "code": "<value>",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<name>"
    Examples:
      | name                  | code                   | value  |
      | select_option_1       | select_attribute       | key_1  |
      | select_option_2       | select_attribute       | key_12 |
      | multi_select_option_1 | multi_select_attribute | key_1  |
      | multi_select_option_2 | multi_select_attribute | key_12 |

  Scenario Outline: Get attribute <code> code
    When I send a GET request to "/api/v1/en_GB/attributes/@<code>_id@"
    Then the response status code should be 200
    And store response param "code" as "<code>_code"
    Examples:
      | code                   |
      | text_attribute         |
      | text_attribute_long    |
      | select_attribute       |
      | multi_select_attribute |
      | numeric_attribute      |
      | price_attribute        |
      | image_attribute        |
      | gallery_attribute      |
      | file_attribute         |

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "elements": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "template_id"

  Scenario Outline: Create <product> product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<product>"
    Examples:
      | product      |
      | product_1_id |
      | product_2_id |

  Scenario Outline: Get <product> product sku
    When I send a GET request to "/api/v1/en_GB/products/<id>"
    Then the response status code should be 200
    And store response param "sku" as "<sku>"
    Examples:
      | id             | sku           |
      | @product_1_id@ | product_1_sku |
      | @product_2_id@ | product_2_sku |

  Scenario Outline: Add product <code> value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_1_id@",
            "payload": [
              {
                "id": "@<code>_id@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": <value>
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 200
    Examples:
      | code                   | value                                 |
      | text_attribute         | "text attribute value"                |
      | text_attribute_long    | "text with long code attribute value" |
      | select_attribute       | "@select_option_1@"                   |
      | multi_select_attribute | ["@multi_select_option_1@"]           |
      | numeric_attribute      | 10.99                                 |
      | price_attribute        | 12.66                                 |
      | image_attribute        | "@multimedia_id@"                     |
      | gallery_attribute      | ["@multimedia_id@"]                   |
      | file_attribute         | ["@multimedia_id@"]                   |

  Scenario Outline: Request product grid filtered by <code> attribute
    When I send a GET request to "api/v1/en_GB/products?columns=<code>&filter=<code>=<filter>"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id         | <code>         |
      | columns[0].type       | <type>         |
      | columns[0].visible    | 1              |
      | columns[0].editable   | 1              |
      | columns[0].deletable  | 1              |
      | collection[0].<field> | <result>       |
      | collection[0].id      | @product_1_id@ |
      | info.filtered         | 1              |
    Examples:
      | type         | field                            | code                          | filter                              | result                              |
      | TEXT         | @text_attribute_code@            | @text_attribute_code@         | text attribute value                | text attribute value                |
      | TEXT         | @text_attribute_long_code@       | @text_attribute_long_code@    | text with long code attribute value | text with long code attribute value |
      | SELECT       | @select_attribute_code@          | @select_attribute_code@       | @select_option_1@                   | @select_option_1@                   |
      | MULTI_SELECT | @multi_select_attribute_code@[0] | @multi_select_attribute_code@ | @multi_select_option_1@             | @multi_select_option_1@             |
      | NUMERIC      | @numeric_attribute_code@         | @numeric_attribute_code@      | 10.99                               | 10.99                               |
      | NUMERIC      | @price_attribute_code@           | @price_attribute_code@        | 12.66                               | 12.66                               |

  Scenario Outline: Request product grid filtered by @product_1_sku@ attribute
    When I send a GET request to "api/v1/en_GB/products?columns=<code>&filter=sku=@product_1_sku@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].<field> | @multimedia_id@ |
    Examples:
      | code                     | field                     |
      | @image_attribute_code@   | @image_attribute_code@    |
      | @gallery_attribute_code@ | @gallery_attribute_code@[0] |
      | @file_attribute_code@    | @file_attribute_code@[0]  |

  Scenario Outline: Request product grid filtered by <code> attribute with extended flag
    When I send a GET request to "api/v1/en_GB/products?extended&columns=<code>&filter=<code>=<filter>"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id          | <code>         |
      | columns[0].type        | <type>         |
      | columns[0].visible     | 1              |
      | columns[0].editable    | 1              |
      | columns[0].deletable   | 1              |
      | collection[0].<field>  | <result>       |
      | collection[0].id.value | @product_1_id@ |
      | info.filtered          | 1              |
    Examples:
      | type         | field                                  | code                          | filter                              | result                              |
      | TEXT         | @text_attribute_code@.value            | @text_attribute_code@         | text attribute value                | text attribute value                |
      | TEXT         | @text_attribute_long_code@.value       | @text_attribute_long_code@    | text with long code attribute value | text with long code attribute value |
      | SELECT       | @select_attribute_code@.value          | @select_attribute_code@       | @select_option_1@                   | @select_option_1@                   |
      | MULTI_SELECT | @multi_select_attribute_code@.value[0] | @multi_select_attribute_code@ | @multi_select_option_1@             | @multi_select_option_1@             |
      | NUMERIC      | @numeric_attribute_code@.value         | @numeric_attribute_code@      | 10.99                               | 10.99                               |
      | NUMERIC      | @price_attribute_code@.value           | @price_attribute_code@        | 12.66                               | 12.66                               |

  Scenario Outline: Request product grid filtered by <code> attribute for null values
    When I send a GET request to "api/v1/en_GB/products?columns=<code>&filter=<code>="
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id        | <code> |
      | columns[0].type      | <type> |
      | columns[0].visible   | 1      |
      | columns[0].editable  | 1      |
      | columns[0].deletable | 1      |
      | collection[0].<code> |        |
    Examples:
      | type    | code                       |
      | TEXT    | @text_attribute_code@      |
      | TEXT    | @text_attribute_long_code@ |
      | SELECT  | @select_attribute_code@    |
      | NUMERIC | @numeric_attribute_code@   |
      | NUMERIC | @price_attribute_code@     |

  Scenario Outline: Request product grid filtered by <code> attribute for not null values
    When I send a GET request to "api/v1/en_GB/products?columns=<code>&filter=<code>!="
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id         | <code>   |
      | columns[0].type       | <type>   |
      | columns[0].visible    | 1        |
      | columns[0].editable   | 1        |
      | columns[0].deletable  | 1        |
      | collection[0].<code>  |          |
      | collection[0].<field> | <result> |
    Examples:
      | type    | field                      | code                       | result                              |
      | TEXT    | @text_attribute_code@      | @text_attribute_code@      | text attribute value                |
      | TEXT    | @text_attribute_long_code@ | @text_attribute_long_code@ | text with long code attribute value |
      | SELECT  | @select_attribute_code@    | @select_attribute_code@    | @select_option_1@                   |
      | NUMERIC | @numeric_attribute_code@   | @numeric_attribute_code@   | 10.99                               |
      | NUMERIC | @price_attribute_code@     | @price_attribute_code@     | 12.66                               |

  Scenario: Request product grid filtered by text attribute null
    When I send a GET request to "api/v1/en_GB/products?columns=@text_attribute_id@&filter=@text_attribute_id@="
    Then the response status code should be 200

  Scenario: Request product date range
    When I send a GET request to "api/v1/en_GB/products?columns=@product_edit_date_attribute_code@&filter=esa_created_aten%3E%3D2020-01-06%3Besa_created_aten%3C%3D2020-01-08"
    Then the response status code should be 200

  Scenario: Request product numeric range
    When I send a GET request to "api/v1/en_GB/products?columns=@numeric_attribute_id@&filter=@numeric_attribute_id@%3E%3D1%3B@numeric_attribute_id@%3C%3D3"
    Then the response status code should be 200

  Scenario: Request product order by index
    When I send a GET request to "api/v1/en_GB/products?columns=@text_attribute_id@&,index&field=index&order=DESC"
    Then the response status code should be 200

  Scenario: Request product order by attribute
    When I send a GET request to "api/v1/en_GB/products?columns=@text_attribute_id@&,index&field=@text_attribute_id@&order=DESC"
    Then the response status code should be 200

  Scenario: Request product order by not exists attribute
    When I send a GET request to "api/v1/en_GB/products?columns=@text_attribute_id@&,index&field=xxxxxxx@&order=DESC"
    Then the response status code should be 200

  Scenario Outline: Delete products
    When I send a DELETE request to "/api/v1/en_GB/products/<product>"
    Then the response status code should be 204
    Examples:
      | product        |
      | @product_1_id@ |
      | @product_2_id@ |

  Scenario Outline: Delete attributes
    When I send a DELETE request to "/api/v1/en_GB/attributes/<attribute>"
    Then the response status code should be 204
    Examples:
      | attribute                   |
      | @text_attribute_id@         |
      | @text_attribute_long_id@    |
      | @select_attribute_id@       |
      | @multi_select_attribute_id@ |
      | @numeric_attribute_id@      |
      | @price_attribute_id@        |
      | @image_attribute_id@        |
      | @gallery_attribute_id@      |

  Scenario Outline: Delete templates
    When I send a DELETE request to "/api/v1/en_GB/templates/<template>"
    Then the response status code should be 204
    Examples:
      | template      |
      | @template_id@ |
