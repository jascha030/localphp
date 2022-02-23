# LocalPHP

Composer package built on the `symfony/console` component, for the very specific use-case of executing php in the
specific versions that run inside the LocalWP app from your own terminal, mainly for quickly using composer, due to the
fact that LocalWP does not provide the latest versions, and homebrew doesn't like when you want to use older specific
versions.

## Getting Started

### Requirements

* [LocalWP](https://localwp.com/)
* PHP `^8`
* Composer `*` (but preferred `2.2` or later)

### Compatibility

This package is currently only built for and tested on macOS.

### Installation

```shell
composer global require jascha030/localphp
```

## Usage

After installation, the `localphp` command will be made available to your commandline, you must pass one of the
available subcommands documented below.

```shell
localphp [options] [arguments]
```

Use the `localphp list` command to list the available commands.
You can use `localphp help [<subcommand>]` to display help for a specific command.

## Commands

### run

Equivalent of running `php -r [<subcommand>]` with a specified php binary.

**Usage:**

```shell
localphp run [options] [--] [<subcommand>]
```

**Arguments:**

* `subcommand` The php command to execute.

**Options:**

```
-u, --use=USE                    The php version to use.
-l, --list                       List available versions
-s, --silent                     Don't write command output to console.
-w, --working-dir[=WORKING-DIR]  Execute command in a specified directory.
-h, --help                       Display help for the given command. When no command is given display help for the list command
-q, --quiet                      Do not output any message
-V, --version                    Display this application version
--ansi|--no-ansi                 Force (or disable --no-ansi) ANSI output
-n, --no-interaction             Do not ask any interactive question
-v|vv|vvv, --verbose             Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

### composer

Run `composer [subcommand]` with a specified php binary.

**Usage:**

```shell 
localphp composer [options] [--] [<subcommand>]
```

**Arguments:**

* `subcommand` The php command to execute.

**Options:**

```
-u, --use=USE                    The php version to use.
-l, --list                       List available versions
-s, --silent                     Don't write command output to console.
-w, --working-dir[=WORKING-DIR]  Execute command in a specified directory.
-h, --help                       Display help for the given command. When no command is given display help for the list command
-q, --quiet                      Do not output any message
-V, --version                    Display this application version
--ansi|--no-ansi                 Force (or disable --no-ansi) ANSI output
-n, --no-interaction             Do not ask any interactive question
-v|vv|vvv, --verbose             Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

## Development

Clone this repo, and run `composer install` inside the repo.

### Code-style

A code-style is provided in the form of a `php-cs-fixer` configuration in `.php-cs-fixer.dist.php`. For easy execution,
use the provided Composer [script command](https://getcomposer.org/doc/articles/scripts.md).

```shell
composer run format
```

If you have php-cs-fixer installed globally, pass it to the `--config` argument of the `fix` command.

```shell
php-cs-fixer fix --config=.php-cs-fixer.dist.php
```

### Unit-testing

A configuration for `phpunit` is provided in `phpunit.xml`.

For easy execution, use the provided Composer [script command](https://getcomposer.org/doc/articles/scripts.md).

```shell
composer run phpunit
```

If you have phpunit installed globally, and want to use that, pass the config in the `--config` argument.

```shell
phpunit --config phpunit.xml
```

## License

This composer package is an open-sourced software licensed under
the [MIT License](https://github.com/jascha030/localwp/blob/master/LICENSE.md)

> **Note:** to find the right license for your project
> use GitHub's [https://choosealicense.com/](https://choosealicense.com/),
> or read up on any other information, regarding Licensing your project in [their docs' page on licensing](https://docs.github.com/en/github/creating-cloning-and-archiving-repositories/creating-a-repository-on-github/licensing-a-repository).

