# Scheduler
Scheduler is an application that lets you create complete school class timetable for school or university.

Application consists of two element - the php part ([deciuss/scheduler repository](https://github.com/deciuss/scheduler)) which is responsible for user interaction and normalization of provided data.

Second part is written in C ([deciuss/scheduler-calculator](https://github.com/deciuss/scheduler-calculator) repository) and is responsible for calculating timetable with evolution algorithm.

Work is still in progress, but it is already possible to generate a decent timetable.

##Usage

Create dev database schema:

```bash
composer db:dev:reset
```

Load example data:

```bash
php bin/console dev:example:load
```

Calculate timetable for plan (work in progress: for now process ends with normalized data file that have to be used with [deciuss/scheduler-calculator](https://github.com/deciuss/scheduler-calculator) execution)

```bash
php bin/console app:calculate plan_id
```

Import [deciuss/scheduler-calculator](https://github.com/deciuss/scheduler-calculator) result csv file:

```bash
php bin/console app:result:import plan_id example/calculator_data/calculator_output.csv
```

Generated plan can be viewed in the browser:

```url
http://localhost:8080/public/index.php/schedule/show/1/group/all/teacher/all
```
 
##Tests

Create test database schema:

```bash
composer db:test:reset
```

Execute tests:
```bash
composer tests
```
