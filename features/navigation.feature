Feature: Testing designer component

  Background:
    When I login as "test@ergonode.com" with "123"

  Scenario: I get user profile
    When I get user profile
    Then I get 200 result code
