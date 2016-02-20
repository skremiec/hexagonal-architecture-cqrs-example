<?php declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use BlogApp\CommandHandler\AddCommentCommandHandler;
use BlogApp\Boundary\CommentsRepository;
use BlogApp\Boundary\PostsRepository;
use BlogApp\CommandHandler\CreatePostCommandHandler;
use BlogApp\Infrastructure\InMemoryPostsQueryRepository;
use BlogApp\Projector\PostsListProjector;
use BlogApp\Query\ListPostsQuery;
use BlogApp\Command\AddCommentCommand;
use BlogApp\Command\CreatePostCommand;
use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class BaseContext implements Context, SnippetAcceptingContext
{
    /** @var EventDispatcher */
    private $eventDispatcher;

    /** @var PostsRepository */
    private $postsRepository;

    /** @var CommentsRepository */
    private $commentsRepository;

    /** @var CreatePostCommandHandler */
    private $createPost;

    /** @var ListPostsQuery */
    private $listPosts;

    /** @var AddCommentCommandHandler */
    private $addComment;

    /** @var string */
    private $postTitle;

    /** @var array */
    private $posts;

    abstract protected function createPostsRepository():PostsRepository;

    abstract protected function createCommentsRepository():CommentsRepository;

    public function __construct()
    {
        $this->eventDispatcher = new EventDispatcher();
        $this->postsRepository = $this->createPostsRepository();
        $this->commentsRepository = $this->createCommentsRepository();
        $postsQueryRepository = new InMemoryPostsQueryRepository();

        $this->createPost = new CreatePostCommandHandler($this->postsRepository, $this->eventDispatcher);
        $this->listPosts = new ListPostsQuery($postsQueryRepository);
        $this->addComment = new AddCommentCommandHandler($this->postsRepository, $this->commentsRepository, $this->eventDispatcher);

        $this->eventDispatcher->addSubscriber(new PostsListProjector($postsQueryRepository));
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
        $this->createPost->handle(new CreatePostCommand($title, ''));
        $this->postTitle = $title;
    }

    /**
     * @When /^I create post titled "(.*?)" with a content: "(.*?)"$/
     */
    public function iCreatePostTitledWithAContent(string $title, string $content)
    {
        try {
            $this->createPost->handle(new CreatePostCommand($title, $content));
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
        $this->addComment->handle(new AddCommentCommand($this->postTitle, $content));
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
            $this->addComment->handle(new AddCommentCommand($postTitle, $content));
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
