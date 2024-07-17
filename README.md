## Instruction

After cloning the repository, we need to seed the data and run the debug command to see the bug.

```
php artisan migrate:fresh --seed
php artisan debug
```

Everything is located under [app/Console/Commands/Debug.php](https://github.com/chinleung/laravel-bug-52170/blob/main/app/Console/Commands/Debug.php).
