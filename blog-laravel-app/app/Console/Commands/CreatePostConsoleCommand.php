<?php

namespace App\Console\Commands;

use BlogApp\Exception\AlreadyExistsException;
use BlogApp\Interactor\CreatePostInteractor;
use BlogApp\Request\CreatePostRequest;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use InvalidArgumentException;

class CreatePostConsoleCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:create {title} {content}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new blog post';

    /**
     * Execute the console command.
     */
    public function handle(CreatePostInteractor $createPost): void
    {
        $request = new CreatePostRequest($this->argument('title'), $this->argument('content'));

        try {
            $createPost->handle($request);
            $this->info(sprintf('Post %s created', $request->getTitle()));
        } catch (InvalidArgumentException | AlreadyExistsException $exception) {
            $this->error(sprintf('Error: %s', $exception->getMessage()));
        }
    }
}
