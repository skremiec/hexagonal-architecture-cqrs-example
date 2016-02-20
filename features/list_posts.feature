Feature: List posts
  As a guest
  In order to find an interesting post
  I need to be able to list all posts

Scenario: Listing posts
  Given there is a post titled "My 1st post"
  And there is a post titled "My 2nd post"
  When I list posts
  Then I should see 2 posts

Scenario: Listing posts on empty blog
  Given there are no posts yet
  When I list posts
  Then I should see no posts
