Feature: Create post
  As an author
  In order to publish my thoughts
  I need to be able to create a post

Scenario: Creating post
  Given there are no posts yet
  When I create post titled "My first post" with a content: "This is my very first post"
  Then there should be 1 post

Scenario: Creating post with empty title
  Given there are no posts yet
  When I create post titled "" with a content: "This is my very first post"
  Then there should still be no posts

Scenario: Creating post with non-unique title
  Given there is a post titled "My first post"
  When I create post titled "My first post" with a content: "There is only one first post"
  Then there should still be 1 post
