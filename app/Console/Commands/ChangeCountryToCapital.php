<?php

namespace App\Console\Commands;

use App\Models\ActivityPublished;
use Illuminate\Console\Command;

class ChangeCountryToCapital extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'as:countryCapital';

    /**
     * The console command description.
     *Laravel console command to change the country capital code to small. (eg. NL to nl.)
     * @var string
     */
    protected $description = 'This changes the country code will lower case to upper case in the activity published database.';
    protected $activityPublished;

    /**
     * Create a new command instance.
     *
     * @param ActivityPublished $activityPublished
     */
    public function __construct(ActivityPublished $activityPublished)
    {
        parent::__construct();
        $this->activityPublished = $activityPublished;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $activities = $this->activityPublished->all();

        foreach ($activities as $activity) {
            
            $fileName           = $activity->filename;
            $separatorPosition  = strrpos($fileName, '-');
            $oldCountryCode     = substr($fileName, $separatorPosition + 1);
            $newCountryCode     = strtolower($oldCountryCode);
            $activity->filename = str_replace($oldCountryCode, $newCountryCode, $fileName);
            $activity->save();

            $this->info(sprintf('Updating for ID: %s. Done', $activity->id));
        }
    }
}
