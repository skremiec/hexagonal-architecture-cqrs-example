<?php declare(strict_types=1);

use Behat\Behat\Context\Context;
use BlogApp\Interactor\AddCommentInteractor;
use BlogApp\Boundary\CommentsRepository;
use BlogApp\Boundary\PostsRepository;
use BlogApp\Interactor\CreatePostInteractor;
use BlogApp\Entity\Post;
use BlogApp\Interactor\ListPostsInteractor;
use BlogApp\Request\AddCommentRequest;
use BlogApp\Request\CreatePostRequest;

class BlogContext implements Context
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

    public function __construct(PostsRepository $postsRepository, CommentsRepository $commentsRepository)
    {
        $this->postsRepository = $postsRepository;
        $this->commentsRepository = $commentsRepository;

        $this->createPost = new CreatePostInteractor($this->postsRepository);
        $this->listPosts = new ListPostsInteractor($this->postsRepository, $this->commentsRepository);
        $this->addComment = new AddCommentInteractor($this->postsRepository, $this->commentsRepository);
    }

    /**
     * @Transform /^(\d+)$/
     */
    public function transformStringToInt(string $string): int
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
        assert(count($this->postsRepository->getAll()) === $count);
    }

    /**
     * @Then there should still be no posts
     */
    public function thereShouldStillBeNoPosts()
    {
        assert(count($this->postsRepository->getAll()) === 0);
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
        assert(count($this->posts) === $count);
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
        assert($this->commentsRepository->getCountFor($this->postTitle) === $count);
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
        assert(count($this->commentsRepository->getAll()) === $count);
    }
}
