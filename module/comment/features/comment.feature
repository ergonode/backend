Feature: Comment module

  Scenario: Create first comment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given remember param "object_id" with value "@@random_uuid@@"
    When I send a POST request to "/api/v1/en_GB/comments" with body:
      """
      {
          "object_id": "@object_id@",
          "content": "Comment to object @object_id@ by"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "comment_id"

  Scenario: Create second comment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given remember param "second_object_id" with value "@@random_uuid@@"
    When I send a POST request to "/api/v1/en_GB/comments" with body:
      """
      {
          "object_id": "@second_object_id@",
          "content": "Comment to object @second_object_id@ by"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "second_comment_id"

  Scenario: Create invalid comment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/comments" with body:
      """
      {
          "object_id": "invalid uuid",
          "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex."
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.object_id[0]" should contain "This is not a valid UUID"
    And the JSON node "errors.content[0]" should contain "Comment to long, max length is 4000 characters"

  Scenario: Get comment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/comments/@comment_id@"
    Then the response status code should be 200

  Scenario: Request comment grid
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en_GB/comments"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Request comment grid for given object_id
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en_GB/comments?filter=object_id=@object_id@"
    Then the response status code should be 200
    And the JSON node "info.filtered" should be equal to "1"
    And the JSON nodes should contain:
     | collection[0].content | Comment to object |
    And the JSON node "collection[0]._links.edit" should exist
    And the JSON node "collection[0]._links.delete" should exist

  Scenario: Change comment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/comments/@comment_id@" with body:
      """
      {
          "content": "New comment for comment to object @object_id@ by user @comment_random@"
      }
      """
    Then the response status code should be 204

  Scenario: Change invalid comment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/comments/@comment_id@" with body:
      """
      {
          "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex."
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.content[0]" should contain "Comment to long, max length is 4000 characters"

  Scenario: Get comment after update
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/comments/@comment_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | content | New comment for comment to object |

  Scenario: Delete comment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "api/v1/en_GB/comments/@comment_id@"
    Then the response status code should be 204

  Scenario: Get removed comment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/comments/@comment_id@"
    Then the response status code should be 404

  Scenario: Request empty comment grid for given object_id
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en_GB/comments?filter=object_id=@object_id@"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/0/"

#privileges
  Scenario: Create role
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "Test comment role (@@random_uuid@@)",
         "description": "Test comment role",
         "privileges": ["PRODUCT_CREATE","PRODUCT_UPDATE","PRODUCT_READ","PRODUCT_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "comment_role"
    And store response param "id" as "object_id"

  Scenario: Create user
    Given remember param "test_username" with value "@@random_uuid@@@ergonode.com"
    Given remember param "test_password" with value "12345678"
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@test_username@",
          "firstName": "Author",
          "lastName": "Comment",
          "language": "en_GB",
          "password": "@test_password@",
          "passwordRepeat": 12345678,
          "roleId": "@comment_role@",
          "isActive": true
      }
      """
    Then the response status code should be 201
    And store response param "id" as "test_author"

  Scenario: Login as different user
    Given I am Authenticated as "@test_username@"

  Scenario: Change comment
    Given I am Authenticated as "@test_username@"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/comments/@second_comment_id@" with body:
      """
      {
          "content": "New comment for comment to object @object_id@ by user @comment_random@"
      }
      """
    Then the response status code should be 403

  Scenario: Delete comment
    Given I am Authenticated as "@test_username@"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "api/v1/en_GB/comments/@second_comment_id@"
    Then the response status code should be 403

  Scenario: Request comment grid for given second_object_id (no edit and delete)
    Given I am Authenticated as "@test_username@"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en_GB/comments?filter=object_id=@second_object_id@"
    Then the response status code should be 200
    And the JSON node "info.filtered" should be equal to "1"
    And the JSON nodes should contain:
      | collection[0].content | Comment to object |
    And the JSON node "collection[0]._links.edit" should not exist
    And the JSON node "collection[0]._links.delete" should not exist
