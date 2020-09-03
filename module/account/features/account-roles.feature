Feature: Account roles

Scenario: Create role with long name
Given I am Authenticated as "test@ergonode.com"
And I add "Content-Type" header equal to "application/json"
And I add "Accept" header equal to "application/json"
