<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Debug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug the issue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $search = '%W20%';

        // Get the total pages count without using union
        $query = Order::where('number', 'like', $search)
            ->orWhereHas('user', static fn ($subquery) => $subquery->where(
                'name',
                'like',
                $search
            ));

        DB::enableQueryLog();

        // Get the total pages using union
        $queryWithUnion = Order::where('number', 'like', $search)->union(
            Order::whereHas(
                'user',
                static fn ($subquery) => $subquery->where('name', 'like', $search)
            )
        );

        // Confirm that both query has the same number of results
        $this->line('Total orders without union: <comment>'.$query->paginate(5)->total().'</comment>');
        $this->line('Total orders with union: <comment>'.$queryWithUnion->paginate(5)->total().'</comment>');

        // Check for the total using getCountForPagination
        $this->line('Count for pagination without union: <comment>'.$query->toBase()->getCountForPagination().'</comment>');
        $this->line('Count for pagination with union: <comment>'.$queryWithUnion->toBase()->getCountForPagination().'</comment>');

        // Show the query used to retrieve the total for a union query
        $this->line('Query used for getCountForPagination with union:');
        $this->comment(collect(DB::getQueryLog())->last()['query']);
    }
}
