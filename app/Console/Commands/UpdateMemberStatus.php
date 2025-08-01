<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Carbon\Carbon;

class UpdateMemberStatus extends Command
{
    protected $signature = 'member:update-status';

    protected $description = 'Update status member menjadi non-active jika lebih dari 10 detik sejak created_at';

    public function handle()
    {
        $now = Carbon::now();

        // subMonth() 1 bulan
        $members = Member::where('status', 'active')
            ->where('created_at', '<=', $now->subSeconds(10))
            ->get();

        foreach ($members as $member) {
            $member->status = 'non-active';
            $member->save();

            $this->info("Member ID {$member->id_member} diubah menjadi non-active.");
        }
        
        return 0;
    }
}
