Feature: Add comment
  As a guest
  In order to respond to the post
  I need to be able to add comment

Scenario: Add comment
  Given there is a post titled "1st post"
  And it has no comments
  When I add "Great post!" comment to it
  Then it should have 1 comment

Scenario: Add comment to non existing post
  Given there are no posts yet
  And there are no comments
  When I add "Great post!" comment to post titled "Foo"
  Then there should be 0 comments
