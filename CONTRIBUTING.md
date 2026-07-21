# Contributing

Thank you for your interest in contributing to the Car List API Laravel SDK.

We welcome bug reports, feature requests, documentation improvements, and pull requests.

---

## Reporting Issues

Before opening an issue:

- Verify you are using the latest SDK version.
- Search existing issues to avoid duplicates.
- Include enough information for us to reproduce the problem.

---

## Pull Requests

Please follow these guidelines:

- Fork the repository.
- Create a feature branch from `main`.
- Keep pull requests focused on a single change.
- Add or update tests where appropriate.
- Update documentation if behavior changes.

---

## Development

Install dependencies:

```bash
composer install
```

Run the test suite:

```bash
composer test
```

Run syntax checks:

```bash
composer lint
```

---

## Coding Standards

This project follows:

- PSR-12
- Semantic Versioning
- Backward compatibility whenever possible

---

## Publishing

1. Create the GitHub repository under the `Codebyray` account.
2. Push the package source.
3. Create and push a semantic version tag such as `v1.0.0`.
4. Submit the GitHub repository to Packagist.

Do not add a `version` property to `composer.json`; Composer and Packagist derive the package version from Git tags.


## Questions

If you're unsure about a change, please open a GitHub Discussion or Issue before beginning work.
