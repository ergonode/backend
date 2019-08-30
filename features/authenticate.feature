Feature: Testing JMS Authentication

  Scenario: I authenticate  with correct credentials
    When I login as "test@ergonode.com" with "123"
    Then I get token

  Scenario: I authenticate with incorrect credentials
    When I login as "key@test.pl" with "secret"
    Then I get 401 result code
