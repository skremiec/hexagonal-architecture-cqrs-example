<?php declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use BlogApp\Interactor\AddCommentInteractor;
use BlogApp\Boundary\CommentsRepository;
use BlogApp\Boundary\PostsRepository;
use BlogApp\Interactor\CreatePostInteractor;
use BlogApp\Entity\Post;
use BlogApp\Interactor\ListPostsInteractor;
use BlogApp\Request\AddCommentRequest;
use BlogApp\Request\CreatePostRequest;

abstract class BaseContext implements Context, SnippetAcceptingContext
{
    /** @var PostsRepository */
    private $postsRepository;

    /** @var CommentsRepository */
    private $commentsRepository;

    /** @var CreatePostInteractor */
    private $createPost;

    /** @var ListPostsInteractor */
    private $listPosts;

    /** @var AddCommentInteractor */
    private $addComment;

    /** @var string */
    private $postTitle;

    /** @var array */
    private $posts;

    abstract protected function createPostsRepository():PostsRepository;

    abstract protected function createCommentsRepository():CommentsRepository;

    public function __construct()
    {
        $this->postsRepository = $this->createPostsRepository();
        $this->commentsRepository = $this->createCommentsRepository();

        $this->createPost = new CreatePostInteractor($this->postsRepository);
        $this->listPosts = new ListPostsInteractor($this->postsRepository, $this->commentsRepository);
        $this->addComment = new AddCommentInteractor($this->postsRepository, $this->commentsRepository);
    }

    /**
     * @Transform /^(\d+)$/
     */
    public function transformStringToInt(string $string):int
    {
        return (int) $string;
    }

    /**
     * @BeforeScenario
     *
     * @Given there are no posts yet
     */
    public function thereAreNoPostsYet()
    {
        $this->postsRepository->clear();
    }

    /**
     * @Given /^there is a post titled "(.*?)"$/
     */
    public function thereIsAPostTitled(string $title)
    {
        $this->postsRepository->add(new Post($title, '', date(DateTime::ATOM)));
        $this->postTitle = $title;
    }

    /**
     * @When /^I create post titled "(.*?)" with a content: "(.*?)"$/
     */
    public function iCreatePostTitledWithAContent(string $title, string $content)
    {
        try {
            $this->createPost->handle(new CreatePostRequest($title, $content));
        } catch (Exception $exception) {
        }
    }

    /**
     * @Then /^there should (?:still )?be (\d+) post$/
     */
    public function thereShouldBePost(int $count)
    {
        assertCount($count, $this->postsRepository->getAll());
    }

    /**
     * @Then there should still be no posts
     */
    public function thereShouldStillBeNoPosts()
    {
        assertCount(0, $this->postsRepository->getAll());
    }

    /**
     * @When I list posts
     */
    public function iListPosts()
    {
        try {
            $this->posts = $this->listPosts->handle();
        } catch (Exception $exception) {
        }
    }

    /**
     * @Then /^I should see (\d+) posts$/
     * @Then I should see no posts
     */
    public function iShouldSeePosts(int $count = 0)
    {
        assertCount($count, $this->posts);
    }

    /**
     * @Given it has no comments
     * @Given there are no comments
     */
    public function itHasNoComments()
    {
        $this->commentsRepository->clear();
    }

    /**
     * @When /^I add "(.*?)" comment to it$/
     */
    public function iAddCommentToIt(string $content)
    {
        $this->addComment->handle(new AddCommentRequest($this->postTitle, $content));
    }

    /**
     * @Then /^it should have (\d+) comment$/
     */
    public function itShouldHaveComment(int $count)
    {
        assertSame($count, $this->commentsRepository->getCountFor($this->postTitle));
    }

    /**
     * @When /^I add "(.*?)" comment to post titled "(.*?)"$/
     */
    public function iAddCommentToPostTitled(string $content, string $postTitle)
    {
        try {
            $this->addComment->handle(new AddCommentRequest($postTitle, $content));
        } catch (Exception $exception) {
        }
    }

    /**
     * @Then /^there should be (\d+) comments$/
     */
    public function thereShouldBeComments(int $count)
    {
        assertCount($count, $this->commentsRepository->getAll());
    }
}
