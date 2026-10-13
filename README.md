<h1 align="center">OpenClinic</h1>

<p align="center">
  Keep patient details, clinical histories, and medical test records together.
</p>

<p align="center">
  <a href="#features">Features</a> ·
  <a href="#getting-started">Getting started</a> ·
  <a href="#roadmap">Roadmap</a> ·
  <a href="#contributing">Contributing</a>
</p>

OpenClinic is an open-source medical records application for clinics and private
practices, built with PHP and MySQL. It brings patient information, medical
problems, and related documents into one browser-based interface.

This fork of [jact/openclinic](https://github.com/jact/openclinic) is for developers
helping bring the existing application to PHP 8.4. The goal is a repeatable local
setup and regression coverage for the workflows already in the codebase.

> [!WARNING]
> **Development only.** This fork still contains the upstream code at `faba5b1`.
> PHP 8.4 compatibility and end-to-end operation are unverified, and known security
> gaps remain. Use synthetic data in an isolated environment. Do not use it in
> production or for patient care.

## Features

The inherited application includes:

- **Patient records:** register and search patients, edit their details, and record social information.
- **Clinical histories:** organize personal and family histories alongside medical problems.
- **Medical tests:** associate test records and uploaded documents with a patient's medical problems.
- **Practice administration:** manage staff, users, settings, themes, database dumps, and log statistics.
- **Translations:** language catalogs in `locale/` support the multilingual interface.

### How the records fit together

A patient record leads to social information, clinical history, and a medical
problems report. Each medical problem can have its own test records and attached
documents. The code also provides a printable medical record.

## Getting started

**Current stack:** PHP, the `mysqli` extension, MySQL, and a PHP-capable web server.
**Target runtime:** PHP 8.4; a supported runtime and platform combination has not
yet been established for this fork.

Clone the source to explore the application:

```sh
git clone https://github.com/HeyItWorked/openclinic.git
cd openclinic
```

The [legacy installation guide](install.html) covers database creation,
`config/database_constants.php`, and the installer in `install/`. Treat it as
historical reference: it has not been validated as a PHP 8.4 setup guide.
There is no verified run command or deployment recipe for this fork yet.

### Find your way around

- **`medical/`**: patient records, histories, problems, and medical tests
- **`admin/` and `auth/`**: administration, sign-in, and access checks
- **`model/` and `model/Query/`**: domain objects and database queries
- **`lib/`, `layout/`, and `home/`**: shared helpers and page rendering
- **`config/` and `install/`**: configuration, database schema, and upgrade scripts

`tests/` stores medical-test attachments. There is no automated regression suite
yet; new test coverage needs a separate location.

## Roadmap

- [ ] **Reproducible setup:** establish PHP 8.4 requirements and a local environment with synthetic fixtures.
- [ ] **Working core flows:** repair legacy form helpers, database error handling, and incompatible PHP calls.
- [ ] **Regression coverage:** add smoke tests and checks for each repaired behavior.
- [ ] **Security review:** address authentication, access controls, request protection, and data handling.

## Contributing

Start with a small, reproducible problem in one of the existing workflows.
For bug reports, include steps to reproduce, expected and actual results, and
your PHP and MySQL versions. Keep pull requests to one change, with a regression
check where possible. Don't include patient data or credentials.

See the inherited [coding guidelines](coding_guidelines.html) and
[changelog](changelog.html) for background on the original application.

## Credits and license

OpenClinic was created by Jose Antonio Chavarría and contributors.

Released under the [GNU General Public License, version 2](LICENSE).
Original copyright and license notices are retained. The software is provided
without warranty.
