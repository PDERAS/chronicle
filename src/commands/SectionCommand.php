<?php
namespace Pderas\Chronicle\Commands;

use Illuminate\Console\Command;

/* Models */
use Pderas\Chronicle\Models\Section;

class SectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chronicle:section
            {--tag= : The tag of the section}
            {--title= : The title of the section}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a section for the chronicle package for notes to be stored in.';

    /**
     * Execute the console command.
     *
     * @param  \Pderas\Chronicle\Models\Section $section
     * @return void
     */
    public function handle()
    {
        $this->createSection();
    }

     /**
     * Create a authorization code client.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    protected function createSection()
    {
        $title  = $this->option('title') ?: $this->ask('What do you want to title the section:');
        $tag    = $this->option('tag') ?: $this->ask('What tag would you like to use for the section:');

        $section = Section::create([
            'title' =>  $title,
            'tag'   =>  $tag
        ]);

        $this->info('New section created successfully.');
        $this->line('<comment>Section ID:</comment> '.$section->id);
    }
}
?>
