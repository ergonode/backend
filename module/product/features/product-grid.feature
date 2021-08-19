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
    Examples:
      | code                        | type         |
      | text_attribute_local        | TEXT         |
      | textarea_attribute_local    | TEXT_AREA    |
      | select_attribute_local      | SELECT       |
      | multiselect_attribute_local | MULTI_SELECT |
      | date_attribute_local        | DATE         |
      | numeric_attribute_local     | NUMERIC      |
      | price_attribute_local       | PRICE        |
      | unit_attribute_local        | UNIT         |
      | image_attribute_local       | IMAGE        |

  Scenario Outline: Get option <name> for select <code>
    When I send a "GET" request to "/api/v1/en_GB/attributes/@<code>_id@/options/grid?filter=code=<key>&view=list"
    Then the response status code should be 200
    And the JSON node "info.filtered" should be equal to 1
    And store response param "collection[0].id" as "<name>"
    Examples:
      | name                  | code                        | key      |
      | select_option_1       | select_attribute_local      | option_1 |
      | select_option_2       | select_attribute_local      | option_2 |
      | multi_select_option_1 | multiselect_attribute_local | option_1 |
      | multi_select_option_2 | multiselect_attribute_local | option_2 |

  Scenario Outline: Get <product> product sku
    When I send a GET request to "/api/v1/en_GB/products?columns=id,esa_sku&filter=esa_sku=<sku>&view=list"
    Then the response status code should be 200
    And the JSON node "info.filtered" should be equal to 1
    And store response param "collection[0].esa_sku" as "<name>_sku"
    And store response param "collection[0].id" as "<name>_id"
    Examples:
      | sku        | name      |
      | sku_test_1 | product_1 |
      | sku_test_2 | product_2 |
      | sku_test_3 | product_3 |

  Scenario Outline: Request product grid filtered by <code> attribute
    When I send a GET request to "api/v1/en_GB/products?columns=<code>&filter=<code>=<filter>;esa_sku=sku_test_"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id         | <code>        |
      | columns[0].type       | <column_type> |
      | columns[0].visible    | 1             |
      | columns[0].editable   | 1             |
      | columns[0].deletable  | 1             |
      | collection[0].<field> | <filter>      |
    Examples:
      | column_type  | field                          | code                        | filter                   |
      | TEXT         | text_attribute_local           | text_attribute_local        | text attribute value     |
      | TEXT_AREA    | textarea_attribute_local       | textarea_attribute_local    | textarea attribute value |
      | SELECT       | select_attribute_local         | select_attribute_local      | @select_option_1@        |
      | MULTI_SELECT | multiselect_attribute_local[0] | multiselect_attribute_local | @multi_select_option_1@  |
      | NUMERIC      | numeric_attribute_local        | numeric_attribute_local     | 10.99                    |
      | NUMERIC      | price_attribute_local          | price_attribute_local       | 12.66                    |
      | NUMERIC      | unit_attribute_local           | unit_attribute_local        | 99.99                    |

  Scenario Outline: Request product grid filtered by <code> attribute with extended flag
    When I send a GET request to "api/v1/en_GB/products?extended&columns=<code>&filter=<code>=<filter>;esa_sku=sku_test_"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | columns[0].id         | <code>        |
      | columns[0].type       | <column_type> |
      | columns[0].visible    | 1             |
      | columns[0].editable   | 1             |
      | columns[0].deletable  | 1             |
      | collection[0].<field> | <filter>      |
    Examples:
      | column_type  | field                          | code                        | filter                   |
      | TEXT         | text_attribute_local.value           | text_attribute_local        | text attribute value     |
      | TEXT_AREA    | textarea_attribute_local.value       | textarea_attribute_local    | textarea attribute value |
      | SELECT       | select_attribute_local.value         | select_attribute_local      | @select_option_1@        |
      | MULTI_SELECT | multiselect_attribute_local.value[0] | multiselect_attribute_local | @multi_select_option_1@  |
      | NUMERIC      | numeric_attribute_local.value        | numeric_attribute_local     | 10.99                    |
      | NUMERIC      | price_attribute_local.value          | price_attribute_local       | 12.66                    |
      | NUMERIC      | unit_attribute_local.value           | unit_attribute_local        | 99.99                    |
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
      | type      | code                     |
      | TEXT      | text_attribute_local     |
      | TEXT_AREA | textarea_attribute_local |
      | SELECT    | select_attribute_local   |
      | NUMERIC   | numeric_attribute_local  |
      | NUMERIC   | price_attribute_local    |

  Scenario Outline: Request product grid filtered by <code> attribute for not null values
    When I send a GET request to "api/v1/en_GB/products?columns=<code>&filter=<code>!=;esa_sku=sku_test_"
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
      | type      | field                    | code                     | result                   |
      | TEXT      | text_attribute_local     | text_attribute_local     | text attribute value     |
      | TEXT_AREA | textarea_attribute_local | textarea_attribute_local | textarea attribute value |
      | SELECT    | select_attribute_local   | select_attribute_local   | @select_option_1@        |
      | NUMERIC   | numeric_attribute_local  | numeric_attribute_local  | 10.99                    |
      | NUMERIC   | price_attribute_local    | price_attribute_local    | 12.66                    |

  Scenario: Request product grid filtered by product id
    When I send a GET request to "api/v1/en_GB/products?columns=id&filter=id=@product_1_id@,@product_2_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].id | @product_1_id@ |
      | collection[1].id | @product_2_id@ |
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
    When I send a GET request to "api/v1/en_GB/products?columns=@attribute_text_id@&,esa_index&field=esa_index&order=DESC"
    Then the response status code should be 200

  Scenario: Request product order by attribute
    When I send a GET request to "api/v1/en_GB/products?columns=@attribute_text_id@&,esa_index&field=@attribute_text_id@&order=DESC"
    Then the response status code should be 200

  Scenario: Request product order by not exists attribute
    When I send a GET request to "api/v1/en_GB/products?columns=@attribute_text_id@&,esa_index&field=xxxxxxx@&order=DESC"
    Then the response status code should be 200
