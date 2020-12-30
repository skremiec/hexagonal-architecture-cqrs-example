<?php declare(strict_types=1);

namespace BlogSilexApp\Command;

use BlogApp\Interactor\CreatePostInteractor;
use BlogApp\Exception\AlreadyExistsException;
use BlogApp\Request\CreatePostRequest;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePostConsoleCommand extends Command
{
    /** @var CreatePostInteractor */
    private $createPost;

    public function __construct(CreatePostInteractor $createPost)
    {
        parent::__construct();

        $this->createPost = $createPost;
    }

    protected function configure()
    {
        $this
            ->setName('post:create')
            ->setDescription('Creates new blog post')
            ->addArgument('title', InputArgument::REQUIRED, 'Post title')
            ->addArgument('content', InputArgument::REQUIRED, 'Post content')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $request = new CreatePostRequest($input->getArgument('title'), $input->getArgument('content'));

        try {
            $this->createPost->handle($request);
            $message = sprintf('Post <info>%s</info> created', $request->getTitle());
        } catch (InvalidArgumentException $exception) {
            $message = sprintf('<error>Error: %s</error>', $exception->getMessage());
        } catch (AlreadyExistsException $exception) {
            $message = sprintf('<error>Error: %s</error>', $exception->getMessage());
        }

        $output->writeln($message);
    }
}
