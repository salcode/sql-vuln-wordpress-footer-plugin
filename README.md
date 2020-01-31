# SQL Vulnerable Footer

**WARNING:** This plugin should **NEVER** be run in a production environment. It should **only** be run on a local development site.

This plugin exposes an SQL Injection Vulnerability.

## Functionality

When adding `?id={user_id}` to a URL, the `display_name` for the user will be
displayed in the footer.

### Examples

On my local site, adding `?id=1` displays

> User 1 Display Name is Sal Ferrarello

in the footer.

## How to Exploit the SQL Vulnerability

Adding a `UNION SELECT` allows you to access other information, e.g.

### Get Email Addresses

```
?id=1 UNION SELECT user_email as display_name from is_users LIMIT 100 OFFSET 1
```

You can increment the final number to see other email addresses, e.g.

```
?id=1 UNION SELECT user_email as display_name from is_users LIMIT 100 OFFSET 2
```

### Other Database Columns

By replacing `user_email` with another database column in the [WordPress User
table](https://codex.wordpress.org/Database_Description#Table:_wp_users)
(e.g. `user_pass`), you can view the information in that column.

## More Secure Implementations

This plugin includes three functions that get a user's display name,

- `bad_get_display_name()` **default**, has an SQL Injection Vulnerability
- `better_get_display_name()` SQL Injection Vulnerability eliminated
- `best_get_display_name()` uses native WordPress functions instead of querying the database directly, thereby eliminating the vulnerability

## Additional Reading

- [WordPress and SQL Injection](https://salferrarello.com/wordpress-and-sql-injection)

## Contributors

[Sal Ferrarello](https://salferrarello.com/) / [@salcode](https://twitter.com/salcode)
