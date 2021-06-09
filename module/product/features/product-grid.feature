Feature: Product edit feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario Outline: get <code> attribute
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=<code>;type=<type>&view=list"
    Then the response status code should be 200
    And the JSON node "info.filtered" should be equal to 1
    And store response param "collection[0].id" as "<code>_id"
    And store response param "collection[0].code" as "<code>_code"
    Examples:
      | code                  | type         |
      | attribute_text        | TEXT         |
      | attribute_textarea    | TEXT_AREA    |
      | attribute_select      | SELECT       |
      | attribute_multiselect | MULTI_SELECT |
      | attribute_date        | DATE         |
      | attribute_numeric     | NUMERIC      |
      | attribute_price       | PRICE        |
      | attribute_unit        | UNIT         |
      | attribute_image       | IMAGE        |

  Scenario Outline: Get option <name> for select <code>
    When I send a "GET" request to "/api/v1/en_GB/attributes/@<code>_id@/options/grid?filter=code=<key>&view=list"
    Then the response status code should be 200
    And the JSON node "info.filtered" should be equal to 1
    And store response param "collection[0].id" as "<name>"
    Examples:
      | name                  | code                  | key   |
      | select_option_1       | attribute_select      | key_1 |
      | select_option_2       | attribute_select      | key_2 |
      | multi_select_option_1 | attribute_multiselect | key_1 |
      | multi_select_option_2 | attribute_multiselect | key_2 |

  Scenario Outline: Get <product> product sku
    When I send a GET request to "/api/v1/en_GB/products?columns=id,sku&filter=sku=<sku>&view=list"
    Then the response status code should be 200
    And the JSON node "info.filtered" should be equal to 1
    And store response param "collection[0].sku" as "<name>_sku"
    And store response param "collection[0].id" as "<name>_id"
    Examples:
      | sku        | name      |
      | sku_test_1 | product_1 |
      | sku_test_2 | product_2 |
      | sku_test_3 | product_3 |

  Scenario Outline: Request product grid filtered by <code> attribute
    When I send a GET request to "api/v1/en_GB/products?columns=<code>&filter=<code>=<filter>;sku=sku_test_"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id         | <code>        |
      | columns[0].type       | <column_type> |
      | columns[0].visible    | 1             |
      | columns[0].editable   | 1             |
      | columns[0].deletable  | 1             |
      | collection[0].<field> | <filter>      |
    Examples:
      | column_type  | field                           | code                         | filter                   |
      | TEXT         | @attribute_text_code@           | @attribute_text_code@        | text attribute value     |
      | TEXT_AREA    | @attribute_textarea_code@       | @attribute_textarea_code@    | textarea attribute value |
      | SELECT       | @attribute_select_code@         | @attribute_select_code@      | @select_option_1@        |
      | MULTI_SELECT | @attribute_multiselect_code@[0] | @attribute_multiselect_code@ | @multi_select_option_1@  |
      | NUMERIC      | @attribute_numeric_code@        | @attribute_numeric_code@     | 10.99                    |
      | NUMERIC      | @attribute_price_code@          | @attribute_price_code@       | 12.66                    |
      | NUMERIC      | @attribute_unit_code@           | @attribute_unit_code@        | 99.99                    |

  Scenario Outline: Request product grid filtered by <code> attribute with extended flag
    When I send a GET request to "api/v1/en_GB/products?extended&columns=<code>&filter=<code>=<filter>;sku=sku_test_"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id         | <code>        |
      | columns[0].type       | <column_type> |
      | columns[0].visible    | 1             |
      | columns[0].editable   | 1             |
      | columns[0].deletable  | 1             |
      | collection[0].<field> | <filter>      |
    Examples:
      | column_type  | field                                 | code                         | filter                   |
      | TEXT         | @attribute_text_code@.value           | @attribute_text_code@        | text attribute value     |
      | TEXT_AREA    | @attribute_textarea_code@.value       | @attribute_textarea_code@    | textarea attribute value |
      | SELECT       | @attribute_select_code@.value         | @attribute_select_code@      | @select_option_1@        |
      | MULTI_SELECT | @attribute_multiselect_code@.value[0] | @attribute_multiselect_code@ | @multi_select_option_1@  |
      | NUMERIC      | @attribute_numeric_code@.value        | @attribute_numeric_code@     | 10.99                    |
      | NUMERIC      | @attribute_price_code@.value          | @attribute_price_code@       | 12.66                    |
      | NUMERIC      | @attribute_unit_code@.value           | @attribute_unit_code@        | 99.99                    |
#
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
      | type      | code                      |
      | TEXT      | @attribute_text_code@     |
      | TEXT_AREA | @attribute_textarea_code@ |
      | SELECT    | @attribute_select_code@   |
      | NUMERIC   | @attribute_numeric_code@  |
      | NUMERIC   | @attribute_price_code@    |

  Scenario Outline: Request product grid filtered by <code> attribute for not null values
    When I send a GET request to "api/v1/en_GB/products?columns=<code>&filter=<code>!=;sku=sku_test_"
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
      | type      | field                     | code                      | result                              |
      | TEXT      | @attribute_text_code@     | @attribute_text_code@     | text attribute value                |
      | TEXT_AREA | @attribute_textarea_code@ | @attribute_textarea_code@ | textarea attribute value  |
      | SELECT    | @attribute_select_code@   | @attribute_select_code@   | @select_option_1@                   |
      | NUMERIC   | @attribute_numeric_code@  | @attribute_numeric_code@  | 10.99                               |
      | NUMERIC   | @attribute_price_code@    | @attribute_price_code@    | 12.66                               |

  Scenario: Request product grid filtered by product id
    When I send a GET request to "api/v1/en_GB/products?columns=id&filter=id=@product_1_id@,@product_2_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].id | @product_2_id@ |
      | collection[1].id | @product_1_id@ |
      | info.filtered    | 2              |

  Scenario: Request product grid filtered by product id
    When I send a GET request to "api/v1/en_GB/products?columns=id&filter=id!=@product_1_id@,@product_2_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].id | @product_3_id@ |
      | info.filtered    | 1              |
#
  Scenario: Request product grid filtered by text attribute null
    When I send a GET request to "api/v1/en_GB/products?columns=@attribute_text_id@&filter=@attribute_text_id@="
    Then the response status code should be 200

  Scenario: Request product date range
    When I send a GET request to "api/v1/en_GB/products?columns=@product_edit_date_attribute_code@&filter=esa_created_aten%3E%3D2020-01-06%3Besa_created_aten%3C%3D2020-01-08"
    Then the response status code should be 200

  Scenario: Request product numeric range
    When I send a GET request to "api/v1/en_GB/products?columns=@attribute_numeric_id@&filter=@attribute_numeric_id@%3E%3D1%3B@attribute_numeric_id@%3C%3D3"
    Then the response status code should be 200

  Scenario: Request product order by index
    When I send a GET request to "api/v1/en_GB/products?columns=@attribute_text_id@&,index&field=index&order=DESC"
    Then the response status code should be 200

  Scenario: Request product order by attribute
    When I send a GET request to "api/v1/en_GB/products?columns=@attribute_text_id@&,index&field=@attribute_text_id@&order=DESC"
    Then the response status code should be 200

  Scenario: Request product order by not exists attribute
    When I send a GET request to "api/v1/en_GB/products?columns=@attribute_text_id@&,index&field=xxxxxxx@&order=DESC"
    Then the response status code should be 200
