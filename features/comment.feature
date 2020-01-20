Feature: Comment module

  Scenario: Create first comment
    Given current authentication token
    Given remember param "object_id" with value "@@random_uuid@@"
    Given the request body is:
      """
      {
          "object_id": "@object_id@",
          "content": "Comment to object @object_id@ by"
      }
      """
    When I request "/api/v1/EN/comments" using HTTP POST
    Then created response is received
    And remember response param "id" as "comment_id"

  Scenario: Create second comment
    Given current authentication token
    Given remember param "second_object_id" with value "@@random_uuid@@"
    Given the request body is:
      """
      {
          "object_id": "@second_object_id@",
          "content": "Comment to object @second_object_id@ by"
      }
      """
    When I request "/api/v1/EN/comments" using HTTP POST
    Then created response is received
    And remember response param "id" as "second_comment_id"

  Scenario: Create invalid comment
    Given current authentication token
    Given the request body is:
      """
      {
          "object_id": "invalid uuid",
          "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex."
      }
      """
    When I request "/api/v1/EN/comments" using HTTP POST
    Then validation error response is received
    And the response body matches:
    """
      /"object_id": [\n[ ]*"This is not a valid UUID."/
    """
    And the response body matches:
    """
      /"content": [\n[ ]*"Comment to long, max length is 4000 characters"/
    """

  Scenario: Get comment
    Given current authentication token
    When I request "/api/v1/EN/comments/@comment_id@" using HTTP GET
    Then the response code is 200

  Scenario: Request comment grid
    Given current authentication token
    When I request "api/v1/EN/comments" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Request comment grid for given object_id
    Given current authentication token
    When I request "api/v1/EN/comments?filter=object_id=@object_id@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /Comment to object/
    """
    And the response body matches:
    """
      /"edit"/
    """
    And the response body matches:
    """
      /"delete"/
    """

  Scenario: Change comment
    Given current authentication token
    Given the request body is:
      """
      {
          "content": "New comment for comment to object @object_id@ by user @comment_random@"
      }
      """
    When I request "/api/v1/EN/comments/@comment_id@" using HTTP PUT
    Then empty response is received

  Scenario: Change invalid comment
    Given current authentication token
    Given the request body is:
      """
      {
          "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex.          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ut libero eget ex scelerisque malesuada. Aenean in dolor in sapien sagittis dictum. Vestibulum viverra efficitur tristique. Aliquam eget urna nulla. Duis accumsan leo ac justo accumsan pellentesque. Fusce efficitur vehicula leo eget eleifend. Nam facilisis, ante at vulputate malesuada, nibh diam laoreet magna, at sagittis ipsum leo faucibus eros. Donec vel urna vel dolor luctus tincidunt. Morbi vitae justo velit. Proin vitae purus mauris. Donec nec lorem sagittis lacus tempor rhoncus egestas non ex."
      }
      """
    When I request "/api/v1/EN/comments/@comment_id@" using HTTP PUT
    Then validation error response is received
    And the response body matches:
    """
      /"content": [\n[ ]*"Comment to long, max length is 4000 characters"/
    """

  Scenario: Get comment after update
    Given current authentication token
    When I request "/api/v1/EN/comments/@comment_id@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /New comment for comment to object/
    """

  Scenario: Delete comment
    Given current authentication token
    When I request "api/v1/EN/comments/@comment_id@" using HTTP DELETE
    Then empty response is received

  Scenario: Get removed comment
    Given current authentication token
    When I request "/api/v1/EN/comments/@comment_id@" using HTTP GET
    Then not found response is received

  Scenario: Request empty comment grid for given object_id
    Given current authentication token
    When I request "api/v1/EN/comments?filter=object_id=@object_id@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 0/
    """

#privileges
  Scenario: Create role
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test comment role (@@random_uuid@@)",
         "description": "Test comment role",
         "privileges": ["PRODUCT_CREATE","PRODUCT_UPDATE","PRODUCT_READ","PRODUCT_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then created response is received
    And remember response param "id" as "comment_role"
    And remember response param "id" as "object_id"

  Scenario: Create user
    Given remember param "test_username" with value "@@random_uuid@@@ergonode.com"
    Given remember param "test_password" with value "12345678"
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@test_username@",
          "firstName": "Author",
          "lastName": "Comment",
          "language": "EN",
          "password": "@test_password@",
          "passwordRepeat": 12345678,
          "roleId": "@comment_role@",
          "isActive": true
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then created response is received
    And remember response param "id" as "test_author"

  Scenario: Login as different user
    Given Authenticate as user "@test_username@" with password "@test_password@"

  Scenario: Change comment
    Given current authentication token
    Given the request body is:
      """
      {
          "content": "New comment for comment to object @object_id@ by user @comment_random@"
      }
      """
    When I request "/api/v1/EN/comments/@second_comment_id@" using HTTP PUT
    Then access denied response is received

  Scenario: Delete comment
    Given current authentication token
    When I request "api/v1/EN/comments/@second_comment_id@" using HTTP DELETE
    Then access denied response is received

  Scenario: Request comment grid for given second_object_id (no edit and delete)
    Given current authentication token
    When I request "api/v1/EN/comments?filter=object_id=@second_object_id@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /Comment to object/
    """
    And the response body matches:
    """
      /[^"edit"]/
    """
    And the response body matches:
    """
      /[^"delete"]/
    """

  Scenario: Login s different user
    Given Authenticate as user "@@default_user_username@@" with password "@@default_user_password@@"
