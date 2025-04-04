<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixAnnouncementsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix announcement data issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking announcements...');
        
        // Check if there's a schema issue to fix
        if (Schema::hasTable('announcements')) {
            if (Schema::hasColumn('announcements', 'is_important') && Schema::hasColumn('announcements', 'priority')) {
                $this->info('Converting is_important to priority...');
                
                // Convert is_important to priority
                DB::table('announcements')
                    ->where('is_important', true)
                    ->update(['priority' => 'high']);
                
                $this->info('Conversion completed.');
            }
        }
        
        // Get all published announcements
        $publishedAnnouncements = Announcement::where('status', 'published')->get();
        $this->info("Found {$publishedAnnouncements->count()} published announcements");
        
        $fixed = 0;
        
        foreach ($publishedAnnouncements as $announcement) {
            $wasUpdated = false;
            
            // If published_at is null but status is published, set published_at to creation date
            if (!$announcement->published_at) {
                $announcement->published_at = $announcement->created_at;
                $wasUpdated = true;
                $this->line("- Set published_at date for announcement #{$announcement->id}: '{$announcement->title}'");
            }
            
            // If expired_at exists but is invalid (before published_at)
            if ($announcement->expired_at && $announcement->published_at && $announcement->expired_at < $announcement->published_at) {
                $announcement->expired_at = $announcement->published_at->copy()->addDays(30); // Add 30 days
                $wasUpdated = true;
                $this->line("- Fixed invalid expired_at date for announcement #{$announcement->id}");
            }
            
            if ($wasUpdated) {
                $announcement->save();
                $fixed++;
            }
        }
        
        // Report Results
        if ($fixed > 0) {
            $this->info("Fixed issues with {$fixed} announcements");
        } else {
            $this->info("No issues found that needed fixing");
        }
        
        // Show active announcements
        $activeAnnouncements = Announcement::active()->get();
        $this->info("Currently {$activeAnnouncements->count()} active announcements based on the scope criteria");
        
        if ($activeAnnouncements->count() > 0) {
            $this->table(
                ['ID', 'Title', 'Published At', 'Expired At'],
                $activeAnnouncements->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'published_at' => $item->published_at ? $item->published_at->format('Y-m-d H:i') : 'NULL',
                        'expired_at' => $item->expired_at ? $item->expired_at->format('Y-m-d H:i') : 'NULL',
                    ];
                })
            );
        }
        
        return Command::SUCCESS;
    }
}
